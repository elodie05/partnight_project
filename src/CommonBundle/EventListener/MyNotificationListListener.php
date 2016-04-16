<?php
namespace CommonBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\NotificationListEvent;
use CommonBundle\Model\NotificationModel;

class MyNotificationListListener {

 public function onListNotifications(NotificationListEvent $event) {

        foreach($this->getNotifications() as $notify){
            $event->addNotification($notify);
        }

    }

    protected function getNotifications() {
        return array(
            new NotificationModel('some notification'),
            new NotificationModel('some more notices', 'success'),
        );
    }

}