<?php

namespace CalendarBundle\Controller;

use CommonBundle\Entity\User;
use CommonBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        if ($request->isMethod('POST')) {
            $user = $request->get('user');
            $email = $user['email'];
            $password = $user['password'];
            $repository = $this->getDoctrine()->getManager()->getRepository('CommonBundle:User');
            $ret = $repository->findBy(array('email' => $email, 'password' => $password));
            if (count($ret) == 1) {
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
            $timestamp = 1234567890;
            $date_time = \Date('dmY', $timestamp);
            $key = $user->getEmail() . $date_time;
            $hash = hash('sha256', $key);
            $user->setKeySecure($hash);
            $em->persist($user);
            $em->flush();
            //return $this->redirect('CalendarBundle:Form:login.html.twig');

            return $this->redirect($this->generateUrl('calendar_homepage'));





        } else {
            return $this->render('CalendarBundle:Form:register.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }
}
