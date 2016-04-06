<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form as form;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function signInAction(Request $request)
    {
    	$em = $this->getDoctrine ()->getManager ();
    	$userManager = $this->container->get ( 'fos_user.user_manager' );
    	$user = $userManager->createUser ();
    	 //var_dump($user);exit;
    	$form = $this->createForm(new form\UserType(),$user);
    	//$form->handleRequest($request);
    /*if ($form->handleRequest ( $request )->isValid ()) {
			$user = $form->getData ();
			$user->setPlainPassword ( $user->getPassword () );
			$userManager->updateUser ( $user );
			
			// Redirection sur la gestion de l'utilisateur
			return $this->redirect ( $this->generateUrl ( 'user_setting_tab_main', array (
					'user_id' => $user->getId () 
			) ) );*/
        return $this->render('UserBundle:user:signin.html.twig',array(
        		'form' => $form->createView()
        ));
    }
}
