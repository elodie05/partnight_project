<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;
use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EventController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
    	$user = $this->get('security.context')->getToken()->getUser();
    	$event = new Event();
    	$event->setUser($user);
    	
    	$form = $this->createForm(new EventType(),$event);
    	
    	if($form->handleRequest($request)->isValid())
		{
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($event);
    		$em->flush();
    		
    		//return $this->redirect('event_view');
    	}
    
        return $this->render('EventBundle:event:create.html.twig',array(
        		'form' => $form->createView(),
				'action' => 'create',
				'event' => $event
        ));
    }
    
    /**
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @param Request $request
     * @param User $user
     */
    public function listAction(Request $request, User $user){

    	$events = $this
    	->getDoctrine()
    	->getManager()
    	->getRepository('EventBundle:Event')
    	->findByUser($user);

    	return $this->render('EventBundle:event:list.html.twig',array(
    			'events' => $events
    	));
    }

    /**
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, Event $event){
    	//var_dump($event);
        //die($event->getLocation());
        $em = $this->getDoctrine()->getEntityManager();

        return $this->render('EventBundle:event:view.html.twig',array(
                'event' => $event
        ));
    }
    
    /**
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function updateAction(Request $request,Event $event)
    {
    	$user = $this->get('security.context')->getToken()->getUser();
    	$em = $this->getDoctrine()->getEntityManager();
    	$form = $this->createForm(new form\EventType(),$event);
    	 
    	if($form->handleRequest($request)->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($event);
    		$em->flush();
    	}
    
    	return $this->render('EventBundle:event:create.html.twig',array(
    			'form' => $form->createView(),
    			'action' => 'update'
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
