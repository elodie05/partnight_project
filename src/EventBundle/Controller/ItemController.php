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
     * @QueryParam(name="name", requirements="\w+", description="Not strict filter by name", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get items",
     *  filters={
     *    {"name"="name", "dataType"="string"}
     *  }
     * )
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
     * @ApiDoc(
     *  resource=true,
     *  description="Get item",
     *  requirements={
     *    {
     *      "name"="item",
     *      "dataType"="integer",
     *      "requirement"="\d+",
     *      "description"="Item id"
     *    }
     *  }
     * )
     */
    public function getItemAction(Item $item)
    {
        $view = $this->view($item, 200);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws BadRequestHttpException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Post item",
     *  input="EventBundle\Form\ItemType",
     *  output="EventBundle\Entity\Item"
     * )
     */
    public function postItemAction(Request $request)
    {
        $item = new Item();

        $form = $this->createForm(new ItemType(), $item);
        $contentTypeJson = $this->get('event.utils.json_content_type')->isJsonContentType($request);
        $data = json_decode($request->getContent());

        if ($contentTypeJson) {
            $form->submit((array) $data);
        } else {
            $form->handleRequest($request);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            if ($request->getRequestFormat() === 'html') {
                $view = $this->routeRedirectView('get_events');
            } else {
                $view = $this->view($item, 200)->setTemplate('EventBundle:item:post.html.twig');
            }

            return $this->handleView($view);
        }

        throw new BadRequestHttpException();
    }
}