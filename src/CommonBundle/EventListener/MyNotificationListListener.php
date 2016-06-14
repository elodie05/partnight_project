<?php
namespace CommonBundle\EventListener;

use Avanzu\AdminThemeBundle\Event\NotificationListEvent;
use CommonBundle\Model\NotificationModel;
use Symfony\Component\Security\Core\SecurityContext;
use EventBundle\Entity\Participation;
use Doctrine\ORM\EntityManager;

class MyNotificationListListener {
	
	protected $context;
	protected $em;

	
	public function __construct(SecurityContext $context, EntityManager $em)
	{
		$this->context = $context;
		$this->em = $em;
	}

 public function onListNotifications(NotificationListEvent $event) {

        foreach($this->getNotifications() as $notify){
            $event->addNotification($notify);
        }

    }

    protected function getNotifications() {
    	$user = $this->context->getToken()->getUser();
    	$notif = array();
    	$participations = $this->em->getRepository('EventBundle:Participation')->findBy(array('user'=>$user,'response'=>null));
    	
    	foreach ($participations as $participation){
    		$notif[] = new NotificationModel('Nouvelle invitation de '.$participation->getEvent()->getUser(),$participation->getId());
    	}
    	
    	return $notif;
    	
        /*return array(
            new NotificationModel('some notification'),
            new NotificationModel('some more notices', 'success'),
        );*/
    }

}