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
	$image1->setUrl('http://www.hcs.harvard.edu/~husc/gallery/tricks/bmute_1647c.jpg');
	$image1->setAlt('Mute');
	$image1->setTrick($this->getReference('trick1'));
	$manager->persist($image1);

	$image2 = new Image;
	$image2->setUrl('http://www.themountainpulse.com/wp-content/images/2012/12/snowboarer_air.jpg');
	$image2->setAlt('Mute');
	$image2->setTrick($this->getReference('trick1'));
	$manager->persist($image2);

	$image3 = new Image;
	$image3->setUrl('https://cdn-snowboarding.transworld.net/blogs.dir/442/files/2015/06/Melon_Jon_Castro_Back_Fenelon.jpg');
	$image3->setAlt('Sad');
	$image3->setTrick($this->getReference('trick2'));
	$manager->persist($image3);

	$image4 = new Image;
	$image4->setUrl('http://snowforever.free.fr/snowboard/grabs/roastbeef.jpg');
	$image4->setAlt('Sad');
	$image4->setTrick($this->getReference('trick2'));
	$manager->persist($image4);

	$image5 = new Image;
	$image5->setUrl('http://snowforever.free.fr/snowboard/grabs/indy.jpg');
	$image5->setAlt('Indy');
	$image5->setTrick($this->getReference('trick3'));
	$manager->persist($image5);

	$image6 = new Image;
	$image6->setUrl('http://snowforever.free.fr/snowboard/grabs/stalefish.jpg');
	$image6->setAlt('Stalefish');
	$image6->setTrick($this->getReference('trick4'));
	$manager->persist($image6);

	$image7 = new Image;
	$image7->setUrl('http://farm1.static.flickr.com/168/417112490_521bacddce.jpg');
	$image7->setAlt('Tail Grab');
	$image7->setTrick($this->getReference('trick5'));
	$manager->persist($image7);

	$image8 = new Image;
	$image8->setUrl('http://assets1.tribesports.com/system/challenges/images/000/009/305/original/20120127140232-nose-grab-snowboarding.jpg');
	$image8->setAlt('Nose Grab');
	$image8->setTrick($this->getReference('trick6'));
	$manager->persist($image8);

	$image9 = new Image;
	$image9->setUrl('https://coresites-cdn.factorymedia.com/whitelines_new/wp-content/uploads/2013/09/FS540.jpg');
	$image9->setAlt('180');
	$image9->setTrick($this->getReference('trick7'));
	$manager->persist($image9);

	$image10 = new Image;
	$image10->setUrl('http://1.bp.blogspot.com/-4b4wuXZqjGk/TkfY_Tb9LAI/AAAAAAAAAxs/g_KKxyxAuSs/s1600/backside360.jpg');
	$image10->setAlt('360');
	$image10->setTrick($this->getReference('trick8'));
	$manager->persist($image10);

	$image11 = new Image;
	$image11->setUrl('https://snowboarding.transworld.net/wp-content/blogs.dir/442/files/2011/08/How_To_Snowboard_Backside_540_Stalefish_Bjorn_Leines-600x375.jpg');
	$image11->setAlt('540');
	$image11->setTrick($this->getReference('trick9'));
	$manager->persist($image11);

	$image12 = new Image;
	$image12->setUrl('https://media.wired.com/photos/593298eb2a990b06268abcab/2:1/w_2500,c_limit/ff_snowboardingscience_f.jpg');
	$image12->setAlt('720');
	$image12->setTrick($this->getReference('trick10'));
	$manager->persist($image12);

	$image13 = new Image;
	$image13->setUrl('http://cdn.coresites.factorymedia.com/whitelines_new/wp-content/uploads/2012/12/IMG_7636-620x413.jpg');
	$image13->setAlt('Front Flip');
	$image13->setTrick($this->getReference('trick11'));
	$manager->persist($image13);

	$image14 = new Image;
	$image14->setUrl('http://coresites-cdn.factorymedia.com/whitelines_new/wp-content/uploads/2015/12/how-to-backflip-snowboard-800.jpg');
	$image14->setAlt('Back-Flip');
	$image14->setTrick($this->getReference('trick12'));
	$manager->persist($image14);

	$image15 = new Image;
	$image15->setUrl('https://thumbs.dreamstime.com/b/chute-d-extr%C3%A9mit%C3%A9-de-snowboard-18176360.jpg');
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