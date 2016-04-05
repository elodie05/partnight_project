<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form as form;

class EventController extends Controller
{
    public function indexAction(Request $request)
    {
    	$form = $this->createForm(new form\EventType());
    
        return $this->render('EventBundle:event:index.html.twig',array(
        		'form' => $form->createView()
        ));
    }
}
