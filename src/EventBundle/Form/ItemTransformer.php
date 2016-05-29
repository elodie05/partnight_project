<?php

namespace EventBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use EventBundle\Entity\Item;

class ItemTransformer implements DataTransformerInterface {
	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	/**
	 * 
	 * @see \Symfony\Component\Form\DataTransformerInterface::transform()
	 */
	public function transform($item) {
		if (null === $item) {
			return '';
		}

		return $item->getId ();
	}

	/*
	 * 
	 * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
	 */
	public function reverseTransform($name) {
		if (! $name) {
			return;
		}

		$item = $this->em->getRepository ( 'EventBundle:Item' )
			->createQueryBuilder ( 'i' )
			->where ( "i.name = ?1" )->setParameter ( 1, $name )->getQuery ()->getResult ();

		if (null == $item) {
		
			$newitem = new Item();
			$newitem->setName($name);
			$this->em->persist($newitem);
			$this->em->flush();

			return $newitem;
		}else{
			return $item [0];
		}

		
	}
}
