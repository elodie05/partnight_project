<?php

namespace CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$last_users = $em->getRepository('UserBundle:User')->findAll();
    	/* A revoir pour récupérer les 10 derniers inscrits */
    	
        return $this->render('CommonBundle:home:home.html.twig',array(
        		'last_users' => $last_users
        ));
    }
}
