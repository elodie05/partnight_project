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
use EventBundle\Entity\Requirement;
use EventBundle\Entity\Provisions;
use EventBundle\Form\ProvisionsType;


class ProvisionsController extends Controller
{

  
    /**
     * Add provision
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @ParamConverter("requirement", options={"mapping": {"requirement_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function addProvisionAction(Request $request, Event $event, Requirement $requirement){
    	$success = 'false';
    	$page= '';
    	$titre = $this->get('translator')->trans('add_provision');
    	
    	$response = $this->forward('EventBundle:Provisions:addProvisionForm', array(
        		'event'  => $event,
    			'requirement' => $requirement
    	));
    	
    	if($request->getSession()->getFlashBag()->get('add_provision_success')){
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
     * @ParamConverter("requirement", options={"mapping": {"requirement_id": "id"}})
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProvisionFormAction(Request $request,Event $event, Requirement $requirement){
    	$user = $this->get('security.context')->getToken()->getUser();
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$form = $this->createForm(new ProvisionsType());
    	
    	if($form->handleRequest($request)->isSubmitted()){
    		$data = $form->getData();
    		$provision = new Provisions();
    		$provision->setEvent($event);
    		$provision->setUser($user);
    		$provision->setItem($requirement->getItem());
    		$provision->setQuantity($data->getQuantity());
    		$em->persist($provision);
    		$em->flush();
    		/*$isRequirement = $em->getRepository('EventBundle:Requirement')->findOneBy(array('item' => $data->getItem(),'event'=> $event));

    		if($isRequirement){
    			$isRequirement->setQuantity($data->getQuantity());
    			$em->persist($isRequirement);
    		}else{
    			$requirement = new Requirement();
    			$requirement->setEvent($event);
    			$requirement->setItem($data->getItem());
    			$requirement->setQuantity($data->getQuantity());
    			$em->persist($requirement);
    		}*/
    		
    		$em->flush();
    		$request->getSession()->getFlashBag()->add('add_provision_success','success');
    	}
    	
    	return $this->render('EventBundle:provisions:add.html.twig',array(
    			'form' => $form->createView()		
    	));	
    }
    
   
    
    
    /**
     * Delete requirement event
     * @ParamConverter("requirement", options={"mapping": {"requirement_id": "id"}})
     * @param Request $request
     * @param Requirement $requirement
     */
    public function removeRequirementEventAction(Request $request,Requirement $requirement)
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($requirement);
    	$em->flush();
    
    	return new JsonResponse(array(	
    			'success' => 'true'			
    	));
    }
    

    
    
    
}
