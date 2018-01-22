<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\Image;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadImage extends AbstractFixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager)
  {

	$image1 = new Image;
	$image1->setUrl('jpeg');
	$image1->setAlt('Mute.jpg');
	$image1->setTrick($this->getReference('trick1'));
	$manager->persist($image1);

	$image2 = new Image;
	$image2->setUrl('jpeg');
	$image2->setAlt('Mute.jpg');
	$image2->setTrick($this->getReference('trick1'));
	$manager->persist($image2);

	$image3 = new Image;
	$image3->setUrl('jpeg');
	$image3->setAlt('Sad.jpg');
	$image3->setTrick($this->getReference('trick2'));
	$manager->persist($image3);

	$image4 = new Image;
	$image4->setUrl('jpeg');
	$image4->setAlt('Sad.jpg');
	$image4->setTrick($this->getReference('trick2'));
	$manager->persist($image4);

	$image5 = new Image;
	$image5->setUrl('jpeg');
	$image5->setAlt('Indy');
	$image5->setTrick($this->getReference('trick3'));
	$manager->persist($image5);

	$image6 = new Image;
	$image6->setUrl('jpeg');
	$image6->setAlt('Stalefish');
	$image6->setTrick($this->getReference('trick4'));
	$manager->persist($image6);

	$image7 = new Image;
	$image7->setUrl('jpeg');
	$image7->setAlt('Tail Grab');
	$image7->setTrick($this->getReference('trick5'));
	$manager->persist($image7);

	$image8 = new Image;
	$image8->setUrl('jpeg');
	$image8->setAlt('Nose Grab');
	$image8->setTrick($this->getReference('trick6'));
	$manager->persist($image8);

	$image9 = new Image;
	$image9->setUrl('jpeg');
	$image9->setAlt('180');
	$image9->setTrick($this->getReference('trick7'));
	$manager->persist($image9);

	$image10 = new Image;
	$image10->setUrl('jpeg');
	$image10->setAlt('360');
	$image10->setTrick($this->getReference('trick8'));
	$manager->persist($image10);

	$image11 = new Image;
	$image11->setUrl('jpeg');
	$image11->setAlt('540');
	$image11->setTrick($this->getReference('trick9'));
	$manager->persist($image11);

	$image12 = new Image;
	$image12->setUrl('jpeg');
	$image12->setAlt('720');
	$image12->setTrick($this->getReference('trick10'));
	$manager->persist($image12);

	$image13 = new Image;
	$image13->setUrl('jpeg');
	$image13->setAlt('Front Flip');
	$image13->setTrick($this->getReference('trick11'));
	$manager->persist($image13);

	$image14 = new Image;
	$image14->setUrl('jpeg');
	$image14->setAlt('Back-Flip');
	$image14->setTrick($this->getReference('trick12'));
	$manager->persist($image14);

	$image15 = new Image;
	$image15->setUrl('jpeg');
	$image15->setAlt('chute');
	$image15->setTrick($this->getReference('trick13'));
	$manager->persist($image15);

	$manager->flush();
  }

  public function getDependencies()
  {
    return array(
    	LoadTrick::class,
    );
  }
}