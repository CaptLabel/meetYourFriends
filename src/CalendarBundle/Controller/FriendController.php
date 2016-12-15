<?php

namespace CalendarBundle\Controller;

use CommonBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class FriendController extends Controller
{
    public function indexAction($keysecure)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
        $user = $repository->findOneBy(array('key_secure' => $keysecure));
        $session = new Session();
        if(empty($user) || $keysecure != $session->get('ks')){
            return $this->redirect($this->generateUrl('calendar_homepage'));
        }

        $friend_list = $user->getFriends()->toArray();
        return $this->render('CalendarBundle:Friend:layout.html.twig', array(
            'user' => $user,
            'friend_list' => $friend_list,
        ));
    }
    public function searchAction($keysecure, $val)
    {
        $ret['status'] = "KO";
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
        $user = $repository->findOneBy(array('key_secure' => $keysecure));
        if(empty($user)){
            return new Response("Invalid");
        }

        $ret['status'] = "OK";
        $ret['res_search'] = $repository->searchFriends($keysecure, $val);


        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    public function addAction($keysecure, $id)
    {
        $ret['status'] = "KO";
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');

        /** @var User $user */
        $user = $repository->findOneBy(array('key_secure' => $keysecure));
        if(empty($user)){
            return new Response("Invalid");
        }
        $new_friend = $repository->findOneBy(array('id' => $id));
        if(empty($new_friend)){
            return new Response("Invalid");
        }
        $em = $this->getDoctrine()->getManager();
        $user->addFriend($new_friend);
        try{
            $em->persist($user);
            $em->flush();
            $ret['status'] = "OK";
        }catch (\Exception $e){
            $ret['status'] = "KO";
            $ret['message'] = "Duplicate key";
        }

        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    public function deleteAction($keysecure, $id)
    {
        $ret['status'] = "KO";
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
        /** @var User $user */
        $user = $repository->findOneBy(array('key_secure' => $keysecure));
        if(empty($user)){
            return new Response("Invalid");
        }
        $new_friend = $repository->findOneBy(array('id' => $id));
        if(empty($new_friend)){
            return new Response("Invalid");
        }
        $em = $this->getDoctrine()->getManager();
        $user->removeFriend($new_friend);
        $em->persist($user);
        $em->flush();
        $ret['status'] = "OK";
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
