<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class LoadUser extends AbstractFixture implements ContainerAwareInterface, DependentFixtureInterface
{

  private $container;


  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }


  public function load(ObjectManager $manager)
  {

  	$user1 = new User;
    $user1->setPseudo('titeuf');
  	$user1->setEmail('titounet@gmail.fr');
  	$user1->setPassword($this->container->get('security.password_encoder')->encodePassword($user1, 'titounet64'));
  	$user1->setAvatar($this->getReference('image16'));
	  $manager->persist($user1);

	  $user2 = new User;
    $user2->setPseudo('dede');
  	$user2->setEmail('dede@gmail.fr');
  	$user2->setPassword($this->container->get('security.password_encoder')->encodePassword($user2, 'dede2017'));
  	$user2->setAvatar($this->getReference('image17'));
	  $manager->persist($user2);

	  $user3 = new User;
    $user3->setPseudo('ben');
  	$user3->setEmail('ben.gallot@gmail.fr');
  	$user3->setPassword($this->container->get('security.password_encoder')->encodePassword($user3, 'benjamin2017'));
  	$user3->setAvatar($this->getReference('image18'));
	  $manager->persist($user3);

	  $manager->flush();

    $this->addReference('user1', $user1);
    $this->addReference('user2', $user2);
    $this->addReference('user3', $user3);

  }

  public function getDependencies()
  {
    return array(
      LoadAvatar::class,
    );
  }
}
