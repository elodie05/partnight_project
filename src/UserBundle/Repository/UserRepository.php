<?php

namespace UserBundle\Repository;

use UserBundle\Entity\User;
use EventBundle\Entity\Event;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
	public function findFriendNotInvited(User $user, Event $event)
	{
		$queryBuilder = $this->createQueryBuilder('u');
		$queryBuilder->innerJoin('u.friends', 'f');
		$queryBuilder->leftJoin('u.participations', 'p');
		$queryBuilder->innerJoin('p.event', 'e');
		$queryBuilder->andWhere('f.id = :userId');
		$queryBuilder->andWhere('e.id != :eventId');
		$queryBuilder->setParameter('userId', $user->getId());
		$queryBuilder->setParameter('eventId', $event->getId());
		
		return $queryBuilder->getQuery()->getResult();
	}
}
