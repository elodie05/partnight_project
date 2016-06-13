<?php

namespace EventBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EventBundle\Entity\Event;

class ProvisionsRepository extends EntityRepository
{
    /**
     * @param Event $event
     * @return array
     */
    public function findByEvent(Event $event)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->innerJoin('p.event', 'e');
        $queryBuilder->andWhere('e.id = eventId');
        $queryBuilder->setParameter('eventId', $event->getId());

        return $queryBuilder->getQuery()->getResult();
    }
}
