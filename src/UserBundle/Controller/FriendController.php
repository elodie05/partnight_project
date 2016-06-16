<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\User;

class FriendController extends FOSRestController
{
    /**
     * Get events
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get friends"
     * )
     */
    public function getFriendsAction()
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();
        }

        $user = $token->getUser();

        if ($user instanceof User) {
            $view = $this->view($user->getFriends(), 200)
                ->setTemplate('EventBundle:event:list.html.twig')
                ->setTemplateVar('friends');

            return $this->handleView($view);
        }

        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     * @throws BadRequestHttpException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Post event",
     *  parameters={
     *    {"name"="friend", "dataType"="integer", "required"=true, "description"="Friend id"}
     *  },
     *  output="UserBundle\Entity\User"
     * )
     */
    public function postFriendsAction(Request $request)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();

        }

        $user = $token->getUser();
        if ($user instanceof User) {
            $contentTypeJson = $this->get('event.utils.json_content_type')->isJsonContentType($request);

            $data = (array)json_decode($request->getContent());

            $friendId = $contentTypeJson ? $data['friend'] : $request->request->getInt('friend');
            $userRepository = $this->getDoctrine()->getRepository('UserBundle:User');
            $friend = $userRepository->find($friendId);

            if ($user->getFriends()->contains($friend)) {
                throw new BadRequestHttpException();
            } else {
                $user->addFriend($friend);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                if ($request->getRequestFormat() === 'html') {
                    $view = $this->routeRedirectView('fos_user_profile_show');
                } else {
                    $view = $this->view($friend, 200)->setTemplate('UserBundle:friend:post.html.twig');
                }

                return $this->handleView($view);
            }
        }

        throw new AccessDeniedException();
    }

    /**
     * Delete friend
     *
     * @param User $friend
     * @return Response
     * @throws AccessDeniedException
     * @throws BadRequestHttpException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete friend",
     *  requirements={
     *    {
     *      "name"="event",
     *      "dataType"="integer",
     *      "requirement"="\d+",
     *      "description"="Event id"
     *    }
     *  }
     * )
     */
    public function deleteFriendsAction(User $friend)
    {
        $token = $this->get('security.token_storage')->getToken();

        if (null === $token) {
            throw new AccessDeniedException();

        }

        $user = $token->getUser();

        if ($user instanceof User) {
            $em = $this->getDoctrine()->getManager();
            $user->removeFriend($friend);
            $em->persist($user);
            $em->flush();

            return new Response(null, 204);
        }

        throw new BadRequestHttpException();
    }
}