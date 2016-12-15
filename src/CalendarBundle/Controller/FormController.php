<?php

namespace CalendarBundle\Controller;

use CommonBundle\Entity\User;
use CommonBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use CommonBundle\Tools\Functions as Functions;


class FormController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = new Session();
        //var_dump($session->get('ks'));
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        if ($request->isMethod('POST')) {
            $user = $request->get('user');
            $email = $user['email'];
            $password = $user['password'];
            $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
            $ret = $repository->findBy(array('email' => $email, 'password' => Functions::createPass($password)));
            if (count($ret) == 1) {
                $session = new Session();
                $session->set('ks', $ret[0]->getKeySecure());
                return $this->redirect($this->generateUrl('calendar_display', array('keysecure' => $ret[0]->getKeySecure())));
            } else {
                return $this->render('CalendarBundle:Form:login.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        } else {
            return $this->render('CalendarBundle:Form:login.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function registrerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $pass = $user->getPassword();
            $email = $user->getEmail();
            $user->setKeySecure(Functions::createKeySecure($email));
            $user->setPassword(Functions::createPass($pass));
            $user->setKeyAvatar(Functions::createKeyAvatar($email));
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('calendar_homepage'));
        } else {
            return $this->render('CalendarBundle:Form:register.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }
}
