<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Comment;
use EventBundle\Form\CommentType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommentController extends FOSRestController
{
    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get comments",
     *  filters={
     *    {"name"="event", "dataType"="integer"}
     *  }
     * )
     */
    public function getCommentsAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');
        $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
        $event = $eventRepository->find($eventId);

        $commentRepository = $this->getDoctrine()->getRepository('EventBundle:Comment');
        $comments = $commentRepository->findByEvent($event);

        $view = $this->view($comments, 200)
            ->setTemplate('EventBundle:comment:list.html.twig');

        return $this->handleView($view);
    }

    /**
     * @QueryParam(name="event", requirements="\d+", nullable=false)
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newCommentAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');
        $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
        $event = $eventRepository->find($eventId);

        $comment = new Comment();
        $comment->setEvent($event);
        $form = $this->createForm(new CommentType(), $comment);

        return $this->render('EventBundle:comment:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws BadRequestHttpException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Post comment",
     *  input="EventBundle\Form\CommentType",
     *  output="EventBundle\Entity\Comment"
     * )
     */
    public function postCommentAction(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        $contentTypeJson = $contentTypeJson = $this->get('event.utils.json_content_type')->isJsonContentType($request);
        $data = json_decode($request->getContent());

        if ($contentTypeJson) {
            $form->submit((array) $data);
        } else {
            $form->handleRequest($request);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            if ($request->getRequestFormat() === 'html') {
                $view = $this->routeRedirectView('get_event', array('event' => $comment->getEvent()->getId()));
            } else {
                $view = $this->view($comment, 200)->setTemplate('EventBundle:comment:post.html.twig');
            }

            return $this->handleView($view);
        }

        throw new BadRequestHttpException();
    }
}