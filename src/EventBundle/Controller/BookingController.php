<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EventBundle\Entity\Event;
use EventBundle\Entity\Booking;

class BookingController extends Controller
{

    /**
     * @ParamConverter("event", options={"mapping": {"event_id": "id"}})
     * @param Request $request
     * @param Event $event
     */
    public function BookingSleepAction(Request $request,Event $event)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	$sleep = $request->request->get('sleep');
    	if($sleep == 'add'){
    		$booking = new Booking();
    		$booking->setEvent($event);
    		$booking->setUser($user);
    		$em->persist($booking);
    	}else{
    		$booking = $em->getRepository('EventBundle:Booking')->findOneBy((array('user'=>$user,'event'=>$event)));
    		$em->remove($booking);
    	}    	

    	$em->flush();

    
    	return new JsonResponse(array(	
    			'success' => 'true'			
    	));
    }
    
    
   
    
}
