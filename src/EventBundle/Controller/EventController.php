<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;
use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EventBundle\Form\RequirementType;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends Controller
{
    /**
     * Create event
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
	 * List events
	 * @throws AccessDeniedException
	 */
    public function listAction(){
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

        return $this->render('EventBundle:event:list.html.twig',array(
                'events' => $events
        ));
    }

    /**
     * View event
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Event $event){
        return $this->render('EventBundle:event:view.html.twig',array(
                'event' => $event
        ));
    }

    /**
     * Update event
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Event $event)
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
    /**
     * Add requirement for an event
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
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
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
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