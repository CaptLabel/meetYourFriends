<?php

namespace CalendarBundle\Controller;

use CommonBundle\Entity\Stay;
use CommonBundle\Form\StayType;
use CommonBundle\Repository\StayRepository;
use CommonBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction($keysecure)
    {
        $session = new Session();
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
        $ret = $repository->findBy(array('key_secure' => $keysecure));
        if(count($ret) != 1 || $keysecure != $session->get('ks')){
            return $this->redirect($this->generateUrl('calendar_homepage'));
        }
        $stay = new Stay();
        $form = $this->createForm(new StayType(), $stay);
        return $this->render('CalendarBundle:Calendar:layout.html.twig', array(
            'form' => $form->createView(),
            'user' => $ret[0]
        ));
    }

    public function getStayAction($keysecure)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:Stay');
        $myFindStay = $repository->myFindStay($keysecure);
        /** Find all match */
        foreach($myFindStay as $key => $val){
            $city = $val['city'];
            $date_arr = $val['dateArrival'];
            $date_dep = $val['dateDeparture'];
            $item_findMatch = $repository->findMatch($date_arr->format('Y-m-d'), $date_dep->format('Y-m-d'), $city, $keysecure);
            if(count($item_findMatch) > 0){
                $myFindStay[$key]['match'] = $item_findMatch;
            }
        }
        $ret['stay'] = $myFindStay;
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function modifyStayAction($keysecure, $id){
        $ret['status'] = "KO";
        $ret['message'] = "Une erreur est survenue";
        /** @var UserRepository $repository_user */
        $repository_user = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
        $user = $repository_user->findOneBy(array('key_secure' => $keysecure));
        if(!empty($user) && !empty($_POST)){
            $date_arr = $_POST['date_arr'];
            $date_dep = $_POST['date_dep'];
            $city = $_POST['city'];
            /** @var StayRepository $repository_stay */
            $repository_stay = $this->getDoctrine()->getManager()->getRepository('CommonBundle:Stay');
            $test_double = $repository_stay->findDoubleStay($date_arr, $date_dep, $keysecure);
            if($id != "add" || count($test_double) == 0){
                $stay = new Stay();
                if(!empty($id) && $id != "add"){
                    $stay = $repository_stay->myFindStayById($keysecure, $id);
                }
                $ret['match'] = $repository_stay->findMatch($date_arr, $date_dep, $city, $keysecure);
                $em = $this->getDoctrine()->getManager();
                $stay->setCity($city);
                $date_arr = new \DateTime($date_arr);
                $stay->setDateArrival($date_arr);
                $date_dep = new \DateTime($date_dep);
                $stay->setDateDeparture($date_dep);
                $stay->setUser($user);
                $em->persist($stay);
                $em->flush();
                $ret['status'] = "OK";
                $ret['message'] = "";
            }else{
                $ret['message'] = "you can't be in two places at the same time";
            }
        }
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    public function removeStayAction($keysecure, $id){
        $ret['status'] = "KO";
        $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
        $user = $repository->findOneBy(array('key_secure' => $keysecure));
        if(!empty($user) && !empty($id)){
            $repository_stay = $this->getDoctrine()->getManager()->getRepository('CommonBundle:Stay');
            $stay = $repository_stay->myFindStayById($keysecure, $id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($stay);
            $em->flush();
            $ret['status'] = "OK";
        }
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
