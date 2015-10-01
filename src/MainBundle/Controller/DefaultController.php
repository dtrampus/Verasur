<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
       $em = $this->getDoctrine()->getManager();
       $tanks = $em->getRepository('MainBundle:Tank')->calculateGraphic();
       
        return $this->render('MainBundle:Default:index.html.twig', array(
                    'tanks' => $tanks
        ));
    }
}
