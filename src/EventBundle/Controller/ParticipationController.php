<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form\ParticipationType;
use EventBundle\Entity\Participation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EventBundle\Entity\Event;

class ParticipationController extends Controller
{
	/**
	 * 
	 * @param Request $request
	 * @param unknown $notifyid
	 */
    public function notificationAction(Request $request,$notifyid)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$participation = $em->getRepository('EventBundle:Participation')->find($notifyid);
    	$event = $participation->getEvent();
    	
    	$participations = $em->getRepository('EventBundle:Participation')->findBy(array('event'=>$event));

    	
        return $this->render('EventBundle:event:view.html.twig',array(
        		'event' => $event,
        		'participation' => $participation,
        		'participations' => $participations
        ));
    }
    
    /**
     * @ParamConverter("participation", options={"mapping": {"participation_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function ResponseAction(Request $request,Participation $participation)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$response = $request->request->get('response');
    	$participation->setResponse($response);
    	$em->persist($participation);
    	$em->flush();
    	
    	//var_dump($event);exit;
    	    	/*$em = $this->getDoctrine()->getManager();
    	$em->remove($requirement);
    	$em->flush();*/
    
    	return new JsonResponse(array(	
    			'success' => 'true'			
    	));
    }
    
    
    /**
     * Add participations
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addParticipationsEventAction(Request $request, Event $event){
    	$success = 'false';
    	$page= '';
    	$titre = $this->get('translator')->trans('add_participations');
    
    	$response = $this->forward('EventBundle:Participation:addParticipationsEventForm', array(
    			'event'  => $event
    	));
    
    	if($request->getSession()->getFlashBag()->get('add_participations_success')){
    		$success = 'true';
    	}else{
    		$page = $response->getContent();
    	}
    
    	return new JsonResponse(array(
    			'page' => $page,
    			'success' => $success,
    			'title' => $titre
    	)
    			);
    
    }
    
    /**
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addParticipationsEventFormAction(Request $request,Event $event){
    	$userIn = $this->get('security.context')->getToken()->getUser();
    	$em = $this->getDoctrine()->getEntityManager();
    	$friends = $userIn->getFriends();
    	$users = array();
    	$participations = $em->getRepository('EventBundle:Participation')->findBy(array('event' => $event));
    	 
    	if($participations != null){
    		foreach ($participations as $p){
    			if($friends->contains($p->getUser())){
    				//echo 'ok';
    			}else{
    				array_push($users, $p->getUser());
    			}
    		}
    		 
    	}else{
    		$users = $friends;
    	}
    	 
    	 
    	
    	$form = $this->createForm(new ParticipationType(array('users' => $users)));
    
    	if($form->handleRequest($request)->isSubmitted()){
    		$data = $form->getData();
    		foreach($data['users'] as $user){
    			$participation = new Participation();
    			$participation->setEvent($event);
    			$participation->setUser($user);
    			$participation->setResponse(null);
    			$em->persist($participation);
    		}
    
    		$em->flush();
    		$request->getSession()->getFlashBag()->add('add_participations_success','success');
    	}
    
    	return $this->render('EventBundle:participation:add_participations.html.twig',array(
    			'form' => $form->createView()
    	));
    }
    
}
