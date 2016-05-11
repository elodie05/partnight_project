<?php

namespace EventBundle\Controller;


use EventBundle\Entity\Requirement;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

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

    public function postRequirementAction(Request $request) {

    }

    public function putRequirementAction() {

    }

    public function deleteRequirementAction() {

    }
}