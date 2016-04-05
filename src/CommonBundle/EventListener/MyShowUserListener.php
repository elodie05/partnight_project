<?php
namespace CommonBundle\EventListener;

use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\SecurityContext;


class MyShowUserListener {
	protected $context;
	public function __construct(SecurityContext $context) {
		$this->context = $context;
	}
	public function onShowUser(ShowUserEvent $event) {
		$user = $this->getUser ();
		$event->setUser ( $user );
	}
	protected function getUser() {
		
 return $this->context->getToken ()->getUser ();
		
	}
}
