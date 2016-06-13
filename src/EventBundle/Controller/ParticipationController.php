<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Event;
use EventBundle\Entity\Participation;
use EventBundle\Form\ParticipationType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ParticipationController extends FOSRestController
{
    /**
     * @param Request $request
     * @param $notifyid
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * TODO: Supprimer
     */
    public function notificationAction(Request $request,$notifyid)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$participation = $em->getRepository('EventBundle:Participation')->find($notifyid);
    	$event = $participation->getEvent();
    	
        return $this->render('EventBundle:event:view.html.twig',array(
        		'event' => $event,
        		'participation' => $participation
        ));
    }

    /**
     * Get participations
     *
     * @QueryParam(name="event", requirements="\d+", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     *
     * @ApiDoc(description="Get participations")
     */
    public function getParticipationsAction(ParamFetcher $paramFetcher)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        $eventId = $paramFetcher->get('event');
        $participationRepository = $this->getDoctrine()->getRepository('EventBundle:Participation');

        $participations = [];

        if (null !== $eventId) {
            $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
            $event = $eventRepository->find($eventId);
            $participations = array_merge($participations, $participationRepository->findByEvent($event));
        } else {
            $participations = array_merge($participations, $participationRepository->findByUser($user));
        }

        $view = $this->view($participations, 200);

        return $this->handleView($view);
    }

    /**
     * @param Participation $participation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getParticipationAction(Participation $participation)
    {
        $view = $this->view($participation, 200);

        return $this->handleView($view);
    }

    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param Request $request
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postParticipationAction(Request $request, ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');

        $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
        $event = $eventRepository->find($eventId);

        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        $participation = new Participation();
        $participation->setUser($user);
        $participation->setEvent($event);

        $form = $this->createForm(new ParticipationType(), $participation);
        $contentType = $request->headers->get('content_type');
        $data = json_decode($request->getContent());

        if ($contentType == 'application/json' && $form->submit((array) $data)->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $event->getId()), 301);

            return $this->handleView($view);
        }
    }

    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param Request $request
     * @param Participation $participation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putParticipationAction(Request $request, Participation $participation)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new ParticipationType(), $participation);
        $contentType = $request->headers->get('content_type');
        $data = json_decode($request->getContent());

        if ($contentType == 'application/json' && $form->submit((array) $data)->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $participation->getEvent()->getId()), 301);

            return $this->handleView($view);
        }
    }
}
