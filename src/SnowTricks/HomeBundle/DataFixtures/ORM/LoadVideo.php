<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\Video;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadVideo extends AbstractFixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager)
  {

	$video1 = new Video;
	$video1->setUrl('https://www.youtube.com/embed/6z6KBAbM0MY');
	$video1->setAlt('Mute');
	$video1->setTrick($this->getReference('trick1'));
	$manager->persist($video1);

  $video2 = new Video;
  $video2->setUrl('https://www.youtube.com/embed/CA5bURVJ5zk');
  $video2->setAlt('Mute');
  $video2->setTrick($this->getReference('trick1'));
  $manager->persist($video2);

  $video3 = new Video;
  $video3->setUrl('https://www.youtube.com/embed/KEdFwJ4SWq4');
  $video3->setAlt('Sad');
  $video3->setTrick($this->getReference('trick2'));
  $manager->persist($video3);

  $video4 = new Video;
  $video4->setUrl('https://www.youtube.com/embed/KE6EbSdtw3U');
  $video4->setAlt('Sad');
  $video4->setTrick($this->getReference('trick2'));
  $manager->persist($video4);

  $video5 = new Video;
  $video5->setUrl('https://www.youtube.com/embed/iKkhKekZNQ8');
  $video5->setAlt('Indy');
  $video5->setTrick($this->getReference('trick3'));
  $manager->persist($video5);

  $video6 = new Video;
  $video6->setUrl('https://www.youtube.com/embed/citAjXAF3qs');
  $video6->setAlt('Stalefish');
  $video6->setTrick($this->getReference('trick4'));
  $manager->persist($video6);

  $video7 = new Video;
  $video7->setUrl('https://www.youtube.com/embed/id8VKl9RVQw');
  $video7->setAlt('Tail Grab');
  $video7->setTrick($this->getReference('trick5'));
  $manager->persist($video7);

  $video8 = new Video;
  $video8->setUrl('https://www.youtube.com/embed/M-W7Pmo-YMY');
  $video8->setAlt('Nose Grab');
  $video8->setTrick($this->getReference('trick6'));
  $manager->persist($video8);

  $video9 = new Video;
  $video9->setUrl('https://www.youtube.com/embed/NQ1MERtpFLQ');
  $video9->setAlt('180');
  $video9->setTrick($this->getReference('trick7'));
  $manager->persist($video9);

  $video10 = new Video;
  $video10->setUrl('https://www.youtube.com/embed/jwRVzcyWpFc');
  $video10->setAlt('360');
  $video10->setTrick($this->getReference('trick8'));
  $manager->persist($video10);

  $video11 = new Video;
  $video11->setUrl('https://www.youtube.com/embed/w66AU3GdfFo');
  $video11->setAlt('540');
  $video11->setTrick($this->getReference('trick9'));
  $manager->persist($video11);

  $video12 = new Video;
  $video12->setUrl('https://www.youtube.com/embed/_yBaOX4299s');
  $video12->setAlt('720');
  $video12->setTrick($this->getReference('trick10'));
  $manager->persist($video12);

  $video13 = new Video;
  $video13->setUrl('https://www.youtube.com/embed/xhvqu2XBvI0');
  $video13->setAlt('Front Flip');
  $video13->setTrick($this->getReference('trick11'));
  $manager->persist($video13);

  $video14 = new Video;
  $video14->setUrl('https://www.youtube.com/embed/W853WVF5AqI');
  $video14->setAlt('Back flip');
  $video14->setTrick($this->getReference('trick12'));
  $manager->persist($video14);

  $video15 = new Video;
  $video15->setUrl('https://www.youtube.com/embed/kAGtH939SUE');
  $video15->setAlt('Chute');
  $video15->setTrick($this->getReference('trick13'));
  $manager->persist($video15);

	$manager->flush();
  }

  public function getDependencies()
  {
    return array(
    	LoadTrick::class,  
    );
  }
}