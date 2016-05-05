<?php

namespace EventBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;
use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use EventBundle\Form\RequirementType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;

class EventController extends FOSRestController
{
    /**
     * Create event
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postEventAction(Request $request)
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     */
    public function getEventsAction(){
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        $events = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('EventBundle:Event')
            ->findByUser($user);

        $view = $this->view($events, 200)
            ->setTemplate('EventBundle:event:list.html.twig')
            ->setTemplateVar('events');

        return $this->handleView($view);
    }

    /**
     * View event
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEventAction(Event $event){
        $view = $this->view($event, 200)
            ->setTemplate('EventBundle:event:view.html.twig')
            ->setTemplateVar('event');

        return $this->handleView($view);
    }

    /**
     * Update event
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putEventAction(Request $request, Event $event)
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
                'action' => 'update',
                'event' => $event
        ));
    }
    
    /**
     * Delete event
     *
     * @param Request $request
     * @param Event $event
     */
    public function deleteEventAction(Request $request,Event $event)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        
    
        /*return $this->render('EventBundle:event:create.html.twig',array(
                'form' => $form->createView()
        ));*/
    }

    /**
     * Add requirement for an event
     *
     * @param Request $request
     * @param Event $event
     *
     * @return JsonResponse
     */
    public function addRequirementEventAction(Request $request, Event $event){
    	$success = false;
    	$response = $this->forward('EventBundle:Event:addRequirementEventForm', array(
        'event'  => $event
    	));
    	
    	return new JsonResponse(array(
    			'page' => $response->getContent(),
    			'success' => $success
    			
    	    )
    	);
    		
    }
    
    /**
     *
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addRequirementEventFormAction(Event $event){
    	$em = $this->getDoctrine()->getEntityManager();
    	$items = $em->getRepository('EventBundle:Item')->findAll();
    	 
    	$form = $this->createForm(new RequirementType(array('items' => $items)));
    	
    	if($form->isValid()){
    		$data = $form->getData();
    		var_dump($data);exit;
    	}
    	
    	return $this->render('EventBundle:event:add_requirement.html.twig',array(
    			'form' => $form->createView(),
    			'success' => true
    	));
    	
    }
}
