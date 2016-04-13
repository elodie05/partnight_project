<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;
use EventBundle\Entity\Event;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    
    /**
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @param Request $request
     * @param User $user
     */
    public function listAction(Request $request, User $user){
    	//var_dump($user);
    	
    	$repository = $this
    	->getDoctrine()
    	->getManager()
    	->getRepository('EventBundle:Event')
    	;
    	//die($user->getLastName());
    	//$events = $em->getRepository('EventBundle:Event')->findByUser($user);
    	$events = $repository->findAll();

    	foreach ($events as $event){
    		echo $event->getDescription();
    	}
    	die('ok');
    }
    
    /**
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function viewAction(Request $request, Event $event){
    	var_dump($event);
    }
    
    /**
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function updateAction(Request $request,Event $event)
    {
    	$user = $this->get('security.context')->getToken()->getUser();
    	 
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
    
    /**
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function removeAction(Request $request,Event $event)
    {

    		$em = $this->getDoctrine()->getManager();
    		$em->remove($event);
    		$em->flush();
    	
    
    	/*return $this->render('EventBundle:event:create.html.twig',array(
    			'form' => $form->createView()
    	));*/
    }
    
    
    
}
