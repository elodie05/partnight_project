<?php

namespace EventBundle\Controller;


use EventBundle\Entity\Item;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;

class ItemController extends FOSRestController
{
    /**
     * @QueryParam(name="name", requirements="\w+", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getItemAction(Item $item)
    {
        $view = $this->view($item, 200);

        return $this->handleView($view);
    }
}