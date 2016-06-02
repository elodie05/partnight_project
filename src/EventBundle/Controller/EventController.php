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
    public function getEventAction(Event $event)
    {
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

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $event->getId()), 301);

            return $this->handleView($view);
        }
    }

    /**
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

        if ($form->handleRequest($request)->isValid()) {
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
    
    /**
     * 
     */
    public function autocompleteAction(Request $request)
    {
    	$items = array();
    	$term = trim(strip_tags($request->get('term')));
    
    	$em = $this->getDoctrine()->getManager();
    	
    	$entities = $em->getRepository('EventBundle:Item')->createQueryBuilder('i')
    	->where('i.name LIKE :name')
    	->setParameter('name', '%'.$term.'%')
    	->getQuery()
    	->getResult();
    
    	foreach ($entities as $entity)
    	{
    		$items[] = $entity->getName();
    	}
    
    	$response = new JsonResponse();
    	$response->setData($items);
    
    	return $response;
    }
}
