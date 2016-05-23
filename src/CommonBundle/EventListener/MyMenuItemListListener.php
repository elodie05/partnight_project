<?php

namespace CommonBundle\EventListener;


use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Avanzu\AdminThemeBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

class MyMenuItemListListener
{

    public function onSetupMenu(SidebarMenuEvent $event)
    {
        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }

    }


    protected function getMenu(Request $request)
    {
        $earg      = array();
        $rootItems = array(
            $event = new MenuItemModel('event', 'event', '', $earg, 'fa fa-calendar-o'),
            /*$form = new MenuItemModel('forms', 'Forms', 'avanzu_admin_form_demo', $earg, 'fa fa-edit'),
            $widgets = new MenuItemModel('widgets', 'Widgets', 'avanzu_admin_demo', $earg, 'fa fa-th', 'new'),*/
           
        );

        //$event->addChild(new MenuItemModel('my events', 'my_events', 'event_list', $earg));
        $event->addChild(new MenuItemModel('new event', 'new_event', 'new_event', $earg));
        $event->addChild(new MenuItemModel('my events', 'my_events', 'get_events', $earg));
        /*$ui->addChild(new MenuItemModel('ui-elements-general', 'General', 'event_create', $earg))
            ->addChild($icons = new MenuItemModel('ui-elements-icons', 'Icons', 'event_create', $earg));*/

        return $this->activateByRoute($request->get('_route'), $rootItems);

    }

    protected function activateByRoute($route, $items) {

        foreach($items as $item) { /** @var $item MenuItemModel */
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            }
            else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }


}