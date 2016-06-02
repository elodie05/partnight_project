<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Item;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
}