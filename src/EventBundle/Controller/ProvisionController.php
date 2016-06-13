<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Provision;
use EventBundle\Form\ProvisionType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProvisionController extends FOSRestController
{
    /**
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProvisionsAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');
        $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
        $event = $eventRepository->find($eventId);

        $provisionRepository = $this->getDoctrine()->getRepository('EventBundle:Provision');
        $provisions = $provisionRepository->findByEvent($event);

        $view = $this->view($provisions, 200);

        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newProvisionAction()
    {
        $provision = new Provision();
        $form = $this->createForm(new ProvisionType(), $provision);

        return $this->render('EventBundle:provision:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param Request $request
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postProvisionAction(Request $request, ParamFetcher $paramFetcher)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        $eventId = $paramFetcher->get('event');
        $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
        $event = $eventRepository->find($eventId);

        $provision = new Provision();
        $provision->setEvent($event);
        $provision->setUser($user);

        $form = $this->createForm(new ProvisionType(), $event);
        $contentType = $request->headers->get('content_type');
        $data = json_decode($request->getContent());

        if ($contentType == 'application/json' && $form->submit((array) $data)->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($provision);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $event->getId()), 301);

            return $this->handleView($view);
        }
    }

    /**
     * Delete provision
     *
     * @param Provision $provision
     *
     * @ApiDoc(description="Delete provision")
     */
    public function deleteProvisionAction(Provision $provision)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($provision);
        $entityManager->flush();
    }
}