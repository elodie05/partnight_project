<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form as form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use UserBundle\Entity\User as UserBun;

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
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @param Request $request
     * @throws AccessDeniedException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request, UserBun $user)
    {
    	$token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $userIn = $token->getUser();
        if($userIn->getFriends()->contains($user)){
        	$friend = 'yes';
        }else{
        	$friend = 'no';
        }
    	return $this->render('UserBundle:user:profile.html.twig',array(
    			'user' => $user,
    			'friend' => $friend
    	));
    }
    
    /**
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @param Request $request
     * @param UserBundle\Entity\User $user
     * @return \UserBundle\Controller\JsonResponse
     */
    public function addFriendAction(Request $request,UserBun $user){
    	$em = $this->getDoctrine()->getManager();
    	$token = $this->get('security.token_storage')->getToken();
    	
    	if (null === $token) {
    		throw new AccessDeniedException();
    	}
    	
    	$userIn = $token->getUser();
    	
    	$userIn->addFriend($user);
    	$em->persist($userIn);
    	
    	$user->addFriend($userIn);
    	$em->persist($user);
    	
    	
    	$em->flush();
    	
    	return new JsonResponse(array(
    			'success' => 'true'
    	));
    }
    
    /**
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @param Request $request
     * @param UserBundle\Entity\User $user
     * @return \UserBundle\Controller\JsonResponse
     */
    public function removeFriendAction(Request $request,UserBun $user){
    	$em = $this->getDoctrine()->getManager();
    	$token = $this->get('security.token_storage')->getToken();
    	 
    	if (null === $token) {
    		throw new AccessDeniedException();
    	}
    	 
    	$userIn = $token->getUser();
    	 
    	$userIn->removeFriend($user);
    	$em->persist($userIn);
    	 
    	$user->removeFriend($userIn);
    	$em->persist($user);
    	 
    	 
    	$em->flush();
    	 
    	return new JsonResponse(array(
    			'success' => 'true'
    	));
    }
}
