<?php

namespace EventBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;
use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use EventBundle\Form\RequirementType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;
use EventBundle\Entity\Requirement;
use EventBundle\Form\CommentType;
use UserBundle\Entity\User;

class EventController extends FOSRestController
{
    /**
     * Get events
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     *
     * @ApiDoc(description="Get events")
     */
    public function getEventsAction()
    {
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
     * @param Event $event
     * @return Response
     *
     * TODO: Correction
     */
    public function getEventAction(Event $event)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	/*$geocoder = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
    	$adress = $event->getLocation();
    	
    	// Requête envoyée à l'API Geocoding
    	$url_adress = utf8_encode($adress);
    	$url_adress = urlencode($adress);
    	$query = sprintf($geocoder, $url_adress);
    	 
    	$result = json_decode(file_get_contents($query));
    	$json = $result->results[0];
    	 
    	$lat = (string) $json->geometry->location->lat;
    	$long = (string) $json->geometry->location->lng;    	 
    	
    	$event->setLat($lat);
    	$event->setLng($long);
    	$em->persist($event);
    	$em->flush();  	
    	*/
    	$user = $this->get('security.context')->getToken()->getUser();
    	 
    	$participations = $em->getRepository('EventBundle:Participation')->findBy(array('event'=>$event));
    	$participation = $em->getRepository('EventBundle:Participation')->findOneBy(array('user' => $user,'event'=>$event));

    	$nbSleepBooking = count($em->getRepository('EventBundle:Participation')->findBy(array('sleep' => true,'event'=>$event)));
    	$comments = $em->getRepository('EventBundle:Comment')->findBy(array('event'=>$event));
    	 
    	$formComment = $this->createForm(new CommentType());
    	$sleepAvailable = $event->getSleepAvailable() - $nbSleepBooking;
    	 
    	if(!empty($participation)){
    		if($participation->getSleep() == 1){
    			$sleepBooking = 'yes';
    		}else{
    			$sleepBooking = 'no';
    		}
    	}else{
    		$sleepBooking ='';
    	}

    	
        $view = $this->view($event, 200)
            ->setTemplate('EventBundle:event:view.html.twig')
            ->setTemplateVar('event')
        	->setTemplateData(array('participations' => $participations,
    			'booking' => $sleepBooking,
    			'sleepAvailable' => $sleepAvailable,
    			'comments' => $comments,
    			'formComment' => $formComment->createView(),
        	    'participation' => $participation,
        			'event' => $event
        	));

        return $this->handleView($view);
    }

    /**
     * @return Response
     */
    public function newEventAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);
        
      /*  $geocoder = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
        $adress = $event->getLocation();
        $url_adress = utf8_encode($adress);
        $url_adress = urlencode($adress);
        $query = sprintf($geocoder, $url_adress);
        
        $result = json_decode(file_get_contents($query));
        $json = $result->results[0];
        
        $lat = (string) $json->geometry->location->lat;
        $long = (string) $json->geometry->location->lng;
         
        $event->setLat($lat);
        $event->setLng($long);
        
        A METTRE A LA CREATION
        
        */

        return $this->render('EventBundle:event:create.html.twig', array(
            'form' => $form->createView(),
            'action' => 'create',
            'event' => $event
        ));
    }

    /**
     * Post event
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Post event")
     */
    public function postEventAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $event = new Event();
        $event->setUser($user);

        $form = $this->createForm(new EventType(), $event);
        $contentType = $request->headers->get('Content-Type');
        $data = json_decode($request->getContent());

        $form->submit((array) $data);

        if ($contentType == 'application/json' && $form->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
           
            
            $em->persist($event);
            $em->flush();
            $view = $this->view($event, 200)->setTemplate('EventBundle:event:post.html.twig');

            return $this->handleView($view);

        }
        


        throw new BadRequestHttpException();
    }

    /**
     * @param Event $event
     * @return Response
     */
    public function editEventAction(Event $event)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
    	
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new form\EventType(),$event);
        
 		$usersToInvite = $em->getRepository('UserBundle:User')->findFriendNotInvited($user, $event);
        
        $participations = $em->getRepository('EventBundle:Participation')->findBy(array('event'=>$event));

        return $this->render('EventBundle:event:edit.html.twig', array(
            'form' => $form->createView(),
            'event' => $event,
        	'participations' => $participations,
        	'usersToInvite' => $usersToInvite
        ));
    }

    /**
     * Update event
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Put event")
     */
    public function putEventAction(Request $request, Event $event)
    {
        $form = $this->createForm(new EventType(), $event);
        $contentType = $request->headers->get('Content-Type');
        $data = json_decode($request->getContent());

        if ($contentType == 'application/json') {
            $form->submit((array) $data);
        } else {
            $form->handleRequest($request);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $view = $this->view($event, 200)->setTemplate('EventBundle:event:edit.html.twig');
            return $this->handleView($view);
  
            //TODO FAIRE REDIRECTION EN JS 
        }

        throw new BadRequestHttpException();
    }

    /**
     * @param Event $event
     *
     * @ApiDoc(description="Delete event")
     */
    public function deleteEventAction(Event $event)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
    }
}
