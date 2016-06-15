<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Document;
use EventBundle\Form\DocumentType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends FOSRestController
{
    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDocumentsAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');
        $eventRepository = $this->getDoctrine()->getRepository('DocumentRepository');
        $event = $eventRepository->find($eventId);

        $documentRepository = $this->getDoctrine()->getRepository('EventBundle:Document');
        $documents = $documentRepository->findByEvent($event);

        $view = $this->view($documents, 200)
            ->setTemplate('EventBundle:document:list.html.twig')
            ->setTemplateVar('events');

        return $this->handleView($view);
    }

    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newDocumentAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');
        $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
        $event = $eventRepository->find($eventId);

        $document = new Document();
        $document->setEvent($event);
        $form = $this->createForm(new DocumentType(), $document);

        return $this->render('EventBundle:event:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDocumentAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(new DocumentType(), $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            $view = $this->routeRedirectView('get_event', array('event' => $document->getEvent()->getId()), 301);

            return $this->handleView($view);
        }
    }
}