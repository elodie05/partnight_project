<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Participation;
use EventBundle\Form\ParticipationType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;



class ParticipationController extends FOSRestController
{
    /**
     * Get participations
     *
     * @QueryParam(name="event", requirements="\d+", description="Filter by event id", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get events",
     *  filters={
     *    {"name"="event", "dataType"="integer"}
     *  }
     * )
     */
    public function getParticipationsAction(ParamFetcher $paramFetcher)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        $eventId = $paramFetcher->get('event');
        $participationRepository = $this->getDoctrine()->getRepository('EventBundle:Participation');

        $participations = [];

        if (null !== $eventId) {
            $eventRepository = $this->getDoctrine()->getRepository('EventBundle:Event');
            $event = $eventRepository->find($eventId);
            $participations = array_merge($participations, $participationRepository->findByEvent($event));
        } else {
            $participations = array_merge($participations, $participationRepository->findByUser($user));
        }

        $view = $this->view($participations, 200);

        return $this->handleView($view);
    }

    /**
     * @param Participation $participation
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get participation",
     *  requirements={
     *    {
     *      "name"="participation",
     *      "dataType"="integer",
     *      "requirement"="\d+",
     *      "description"="Participation id"
     *    }
     *  }
     * )
     */
    public function getParticipationAction(Participation $participation)
    {
        $view = $this->view($participation, 200);

        return $this->handleView($view);
    }

    /**
     * @QueryParam(name="event", requirements="\d+", nullable=true)
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newParticipationAction(ParamFetcher $paramFetcher)
    {
        $eventId = $paramFetcher->get('event');
        $event = $this->getDoctrine()->getRepository('EventBundle:Event')->findOneBy(array('id' => $eventId));

        $em = $this->getDoctrine()->getManager();
       

        //$usersToInvite = $em->getRepository('UserBundle:User')->findFriendNotInvited($user, $event);
        
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $friends = $user->getFriends();
        $users = array();
        $participations = $em->getRepository('EventBundle:Participation')->findBy(array('event' => $event));
        
        /*if($participations != null){
        	foreach ($participations as $p){
        		if($friends->contains($p->getUser())){
        			//echo 'ok';
        		}else{
        			array_push($users, $p->getUser());
        		}
        	}
        	 
        }else{*/
        	$users = $friends;
      /*  }*/
        

        $participation = new Participation();
        $participation->setEvent($event);

        $form = $this->createForm(new ParticipationType(), $participation);

        return $this->render('EventBundle:participation:new.html.twig', array(
            'form' => $form->createView(),'usersToInvite' => $users,'event' => $event
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws BadRequestHttpException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Post participation",
     *  input="EventBundle\Form\ParticipationType",
     *  output="EventBundle\Entity\Participation"
     * )
     */
    public function postParticipationAction(Request $request)
    {
        $participation = new Participation();

        $form = $this->createForm(new ParticipationType(), $participation);
        $contentTypeJson = $this->get('event.utils.json_content_type')->isJsonContentType($request);
        $data = json_decode($request->getContent());

        if ($contentTypeJson) {
            $form->submit((array) $data);
        } else {
            $form->handleRequest($request);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            if ($request->getRequestFormat() === 'html') {
                $view = $this->routeRedirectView('get_event', array('event' => $participation->getEvent()->getId()));
            } else {
                $view = $this->view($participation, 200)->setTemplate('EventBundle:participation:post.html.twig');
            }

            return $this->handleView($view);
        }

        throw new BadRequestHttpException();
    }

    /**
     * @param Request $request
     * @param Participation $participation
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws BadRequestHttpException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Put participation",
     *  input="EventBundle\Form\ParticipationType",
     *  output="EventBundle\Entity\Participation",
     *  requirements={
     *    {
     *      "name"="participation",
     *      "dataType"="integer",
     *      "requirement"="\d+",
     *      "description"="Participation id"
     *    }
     *  }
     * )
     */
    public function putParticipationAction(Request $request, Participation $participation)
    {

        $form = $this->createForm(new ParticipationType(), $participation);
        $contentTypeJson = $this->get('event.utils.json_content_type')->isJsonContentType($request);
        $data = json_decode($request->getContent());

        if ($contentTypeJson) {
            $form->submit((array) $data);
        } else {
            $form->handleRequest($request);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();
            $view = $this->view($participation, 200)->setTemplate('EventBundle:participation:put.html.twig');

            return $this->handleView($view);
        }
        
        throw new BadRequestHttpException();
    }
    
   
}
