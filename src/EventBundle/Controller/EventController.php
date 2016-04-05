<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;

class EventController extends Controller
{
    public function createAction(Request $request)
    {
    	$user = $this->get('security.context')->getToken()->getUser();
    	$event = new entity\Event();
    	$event->setUser($user);
    	
    	$form = $this->createForm(new form\EventType(),$event);
    	
    	if($form->handleRequest($request)->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($event);
    		$em->flush();
    	}
    
        return $this->render('EventBundle:event:create.html.twig',array(
        		'form' => $form->createView()
        ));
    }
    
    public function viewAction(Request $request){
    	
    }
}
