<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form as form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserController extends Controller
{
    public function signInAction(Request $request)
    {
    	$em = $this->getDoctrine ()->getManager ();
    	$userManager = $this->container->get ( 'fos_user.user_manager' );
    	$user = $userManager->createUser ();
    	 //var_dump($user);exit;
    	$form = $this->createForm(new form\UserType(User::class),$user);
    	//$form->handleRequest($request);
    	if ($form->handleRequest ( $request )->isValid ()) {
			$user = $form->getData ();
			$user->setPlainPassword ( $user->getPassword () );
			$userManager->updateUser ( $user );
			
			// Redirection sur la gestion de l'utilisateur
			/*return $this->redirect ( $this->generateUrl ( 'avanzu_admin_profile', array (
					'user_id' => $user->getId () 
			) ) );*/
    	}
        return $this->render('UserBundle:user:signin.html.twig',array(
        		'form' => $form->createView()
        ));
    }
    
    /**
     * 
     * @param Request $request
     * @throws AccessDeniedException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
    	$token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();
    	return $this->render('UserBundle:user:profile.html.twig',array(
    			'user' => $user
    	));
    }
}
