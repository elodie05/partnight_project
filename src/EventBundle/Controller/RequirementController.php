<?php

namespace EventBundle\Controller;


use EventBundle\Entity\Event;
use EventBundle\Entity\Requirement;
use EventBundle\Form\RequirementType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequirementController extends FOSRestController
{
    /**
     * Get requirements
     *
     * @QueryParam(name="event", requirements="\d+")
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Get requirements")
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
     * Get requirement
     *
     * @param Requirement $requirement
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Get requirement")
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
        $requirement->setEvent($event);
        $form = $this->createForm($this->get('event.form.requirement_type'), $requirement);

        return $this->render('EventBundle:requirement:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Post requirement")
     */
    public function postRequirementAction(Request $request) {
    	
        $requirement = new Requirement();
      
        $form = $this->createForm($this->get('event.form.requirement_type'), $requirement);
        $contentTypeJson = $this->get('event.utils.json_content_type')->isJsonContentType($request);
        $data = json_decode($request->getContent());

        if ($contentTypeJson) {
            $form->submit((array) $data);
        } else {
            $form->handleRequest($request);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($requirement);
            $em->flush();

            if ($request->getRequestFormat() === 'html') {
                $view = $this->routeRedirectView('edit_event', array('event' => $requirement->getEvent()->getId()));
            } else {
                $view = $this->view($requirement, 200)->setTemplate('EventBundle:requirement:post.html.twig');
            }

            return $this->handleView($view);
        }

        throw new BadRequestHttpException();
    }

    /**
     * @param Requirement $requirement
     * @return Response
     *
     * @ApiDoc(description="Delete requirement")
     */
    public function deleteRequirementAction(Requirement $requirement) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($requirement);
        $em->flush();

        return new Response(null, 204);
    }
}