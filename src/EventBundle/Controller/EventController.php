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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use EventBundle\Form\RequirementType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;
use EventBundle\Entity\Requirement;

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
     * Get event
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Get event")
     */
    public function viewAction(Event $event){

    		
        return $this->render('EventBundle:event:view.html.twig',array(
        		'participation'=>'',
                'event' => $event));
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
    	
    	$geocoder = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
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
    	
    	
        $view = $this->view($event, 200)
            ->setTemplate('EventBundle:event:view.html.twig')
            ->setTemplateVar('event');

        return $this->handleView($view);
    }

    /**
     * @return Response
     */
    public function newEventAction()
    {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

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
        $contentType = $request->headers->get('content_type');
        $data = json_decode($request->getContent());

        if ($contentType == 'application/json' && $form->submit((array) $data)->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $event->getId()), 301);

            return $this->handleView($view);
        }
    }

    /**
     * @param Event $event
     * @return Response
     */
    public function editEventAction(Event $event)
    {
        $form = $this->createForm(new EventType(), $event);

        return $this->render('EventBundle:event:edit.html.twig', array(
            'form' => $form->createView(),
            'event' => $event
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
        $contentType = $request->headers->get('content_type');
        $data = json_decode($request->getContent());

        if ($contentType == 'application/json' && $form->submit((array) $data)->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
        }

        return $this->render('EventBundle:event:create.html.twig', array(
            'form' => $form->createView(),
            'action' => 'update',
            'event' => $event
        ));
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
