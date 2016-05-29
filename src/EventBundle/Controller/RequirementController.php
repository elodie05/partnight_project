<?php

namespace EventBundle\Controller;


use EventBundle\Entity\Event;
use EventBundle\Entity\Requirement;
use EventBundle\Form\RequirementType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirementController extends FOSRestController
{

    /**
     * @QueryParam(name="event", requirements="\d+")
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRequirementsAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');

        $entityManager = $this->getDoctrine()->getManager();
        $eventRepository = $entityManager->getRepository('EventBundle:Event');
        $requirementRepository = $entityManager->getRepository('EventBundle:Requirement');

        $event = $eventRepository->find($eventId);
        $requirements = $requirementRepository->findBy(array('event' => $event));

        $view = $this->view($requirements, 200);

        return $this->handleView($view);

    }

    /**
     * @param Requirement $requirement
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRequirementAction(Requirement $requirement) {
        $view = $this->view($requirement, 200);

        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newRequirementAction(Event $event)
    {
        $requirement = new Requirement();
        $form = $this->createForm($this->get('event.form.requirement_type'), $requirement);

        return $this->render('EventBundle:requirement:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postRequirementAction(Request $request) {
        $requirement = new Requirement();
        $form = $this->createForm($this->get('event.form.requirement_type'), $requirement);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($requirement);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $requirement->getEvent()->getId()), 301);

            return $this->handleView($view);
        }
    }

    /**
     * @param Requirement $requirement
     * @return Response
     */
    public function deleteRequirementAction(Requirement $requirement) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($requirement);
        $em->flush();

        return new Response(null, 204);
    }
}