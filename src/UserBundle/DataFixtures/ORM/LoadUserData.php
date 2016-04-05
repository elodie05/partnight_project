<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface {
	

public function load(ObjectManager $manager)
{
	$userAdmin = new User();
	$userAdmin->setUsername('elodie');
	$userAdmin->setPassword('elodie');
	$userAdmin->setEmail('elodiecantrel@gmail.com');

	$manager->persist($userAdmin);
	$manager->flush();
}

}