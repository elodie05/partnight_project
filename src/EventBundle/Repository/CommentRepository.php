<?php

namespace EventBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EventBundle\Entity\Event;

class CommentRepository extends EntityRepository
{
    /**
     * @param Event $event
     * @return array
     */
    public function findByEvent(Event $event)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->innerJoin('c.event', 'e');
        $queryBuilder->andWhere('e.id = :eventId');
        $queryBuilder->setParameter('eventId', $event->getId());

        return $queryBuilder->getQuery()->getResult();
    }
}