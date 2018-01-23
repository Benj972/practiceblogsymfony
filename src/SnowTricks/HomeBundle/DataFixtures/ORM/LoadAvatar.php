<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\Image;

class LoadAvatar extends AbstractFixture 
{
  public function load(ObjectManager $manager)
  {

  	$image16 = new Image;
	$image16->setUrl('jpeg');
	$image16->setAlt('avatar1');
	$manager->persist($image16);

	$image17 = new Image;
	$image17->setUrl('jpeg');
	$image17->setAlt('avatar2');
	$manager->persist($image17);

	$image18 = new Image;
	$image18->setUrl('jpeg');
	$image18->setAlt('avatar3');
	$manager->persist($image18);

	$manager->flush();

	$this->addReference('image16', $image16);
    $this->addReference('image17', $image17);
    $this->addReference('image18', $image18);
  }

}