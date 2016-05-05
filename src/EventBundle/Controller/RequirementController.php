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
     */
    public function getRequirementsAction(ParamFetcher $paramFetcher) {
        $eventId = $paramFetcher->get('event');
    }

    public function getRequirementAction(Requirement $requirement) {

    }

    public function postRequirementAction(Request $request) {

    }

    public function putRequirementAction() {

    }
}