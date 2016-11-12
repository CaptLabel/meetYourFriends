<?php

namespace CalendarBundle\Service;

class Service
{
    public function getStay()
    {


        $repository = $this->getDoctrine()

            ->getManager()

            ->getRepository('OCPlatformBundle:Advert')

        ;



        return "coucou";
    }
}