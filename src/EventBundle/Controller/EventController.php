<?php

namespace EventBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;
use EventBundle\Entity as entity;
use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use EventBundle\Form\RequirementType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;
use EventBundle\Entity\Requirement;

class EventController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
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
     * View event
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEventAction(Event $event)
    {
        $view = $this->view($event, 200)
            ->setTemplate('EventBundle:event:view.html.twig')
            ->setTemplateVar('event');

        return $this->handleView($view);
    }

    /**
     * Create event
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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

            $view = $this->view($event, 200);

            return $this->handleView($view);
        }

        return $this->render('EventBundle:event:create.html.twig', array(
            'form' => $form->createView(),
            'action' => 'create',
            'event' => $event
        ));
    }

    /**
     * Update event
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
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
     */
    public function deleteEventAction(Event $event)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
    }

    /**
     * Add requirement for an event
     *
     * @param Request $request
     * @param Event $event
     *
     * @return JsonResponse
     */
    /*
    public function addRequirementEventAction(Request $request, Event $event)
    {
        $success = 'false';
        $page = '';
        $titre = $this->get('translator')->trans('add_requirement');

        $response = $this->forward('EventBundle:Event:addRequirementEventForm', array(
            'event' => $event
        ));

        if ($request->getSession()->getFlashBag()->get('add_requirement_success')) {
            $success = 'true';
        } else {
            $page = $response->getContent();
        }

        return new JsonResponse(array(
                'page' => $page,
                'success' => $success,
                'title' => $titre
            )
        );

    }
    */

    /**
     *
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    /*
    public function addRequirementEventFormAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $items = $em->getRepository('EventBundle:Item')->findAll();

        $form = $this->createForm(new RequirementType(array('items' => $items)));

        if ($form->handleRequest($request)->isSubmitted()) {
            $data = $form->getData();
            $isRequirement = $em->getRepository('EventBundle:Requirement')->findOneBy(array('item' => $data->getItem(), 'event' => $event));

            if ($isRequirement) {
                $isRequirement->setQuantity($data->getQuantity());
                $em->persist($isRequirement);
            } else {
                $requirement = new Requirement();
                $requirement->setEvent($event);
                $requirement->setItem($data->getItem());
                $requirement->setQuantity($data->getQuantity());
                $em->persist($requirement);
            }

            $em->flush();
            $request->getSession()->getFlashBag()->add('add_requirement_success', 'success');
        }

        return $this->render('EventBundle:event:add_requirement.html.twig', array(
            'form' => $form->createView()

        ));

    }
    */

    /**
     * Delete requirement event
     * @ParamConverter("requirement", options={"mapping": {"requirement_id": "id"}})
     * @param Request $request
     * @param Requirement $requirement
     */
    /*
    public function removeRequirementEventAction(Request $request, Requirement $requirement)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($requirement);
        $em->flush();

        return new JsonResponse(array(

            'success' => 'true'

        ));
    }
    */
}
