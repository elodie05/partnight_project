<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Item;
use EventBundle\Form\ItemType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ItemController extends FOSRestController
{
    /**
     * Get items
     *
     * @QueryParam(name="name", requirements="\w+", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Get items")
     */
    public function getItemsAction(ParamFetcher $paramFetcher)
    {
        $name = $paramFetcher->get('name');
        $eventRepository = $this->getDoctrine()->getManager()->getRepository('EventBundle:Item');

        $items = [];

        if ($name == null) {
            $items = $eventRepository->findAll();
        } else {
            $items = $eventRepository->findLikeName($name);
        }

        $view = $this->view($items, 200);

        return $this->handleView($view);
    }

    /**
     * Get item
     *
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(description="Get item")
     */
    public function getItemAction(Item $item)
    {
        $view = $this->view($item, 200);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postItemAction(Request $request)
    {
        $item = new Item();

        $form = $this->createForm(new ItemType(), $item);
        $contentType = $request->headers->get('Content-Type');
        $data = json_decode($request->getContent());

        $form->submit((array) $data);

        if ($contentType == 'application/json' && $form->isValid() || $form->handleRequest($request)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $view = $this->view($item, 200)->setTemplate('EventBundle:item:post.html.twig');

            return $this->handleView($view);
        }

        throw new BadRequestHttpException();
    }
}