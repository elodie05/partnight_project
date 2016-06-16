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
     * @QueryParam(name="event", requirements="\d+", nullable=true)
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
     * @QueryParam(name="event", requirements="\d+", nullable=true)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newProvisionAction(ParamFetcher $paramFetcher)
    {
    	$eventId = $paramFetcher->get('event');
    	$eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
    	$event = $eventRepository->find($eventId);
    	
    	$token = $this->get('security.token_storage')->getToken();
    	
    	if (null === $token) {
    		throw new AccessDeniedException();
    	}
    	
    	$user = $token->getUser();
    	
        $provision = new Provision();
        $provision->setEvent($event);
        $provision->setUser($user);
        $form = $this->createForm(new ProvisionType(), $provision);

        return $this->render('EventBundle:provision:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postProvisionAction(Request $request)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        $provision = new Provision();
        $provision->setUser($user);

        $form = $this->createForm(new ProvisionType(), $provision);
        $contentType = $request->headers->get('Content-Type');
        $data = json_decode($request->getContent());


        $form->submit((array) $data);

        if ($contentType == 'application/json' && $form->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($provision);
            $em->flush();
            $view = $this->view($provision, 200)->setTemplate('EventBundle:provision:post.html.twig');

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