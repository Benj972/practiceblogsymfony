<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;



class LoadUser extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, DependentFixtureInterface
{

  private $container;


  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }


  public function load(ObjectManager $manager)
  {

  	$user1 = new User;
  	$user1->setEmail('titounet@gmail.fr');
  	$user1->setPassword($this->container->get('security.password_encoder')->encodePassword($user1, 'titounet64'));
  	$user1->setAvatar('https://upload.wikimedia.org/wikipedia/commons/5/5f/Rilley_elf_south_park_avatar.png');
	$manager->persist($user1);

	$user2 = new User;
  	$user2->setEmail('dede@gmail.fr');
  	$user2->setPassword($this->container->get('security.password_encoder')->encodePassword($user2, 'dede2017'));
  	$user2->setAvatar('https://vignette.wikia.nocookie.net/adventuretimewithfinnandjake/images/3/35/South_Park_Avatar_Wallpaper1600x1200.png/revision/latest?cb=20140424015749');
	$manager->persist($user2);

	$user3 = new User;
  	$user3->setEmail('ben.gallot972@gmail.fr');
  	$user3->setPassword($this->container->get('security.password_encoder')->encodePassword($user3, 'benjamin2017'));
  	$user3->setAvatar('https://vignette1.wikia.nocookie.net/southpark/images/3/3f/South_Park_Avatar_Wallpaper800x600.png/revision/latest?cb=20111107043648');
	$manager->persist($user3);

	$manager->flush();

    $this->addReference('user1', $user1);
    $this->addReference('user2', $user2);
    $this->addReference('user3', $user3);

  }

  public function getDependencies()
    {
        return array(
            LoadCategory::class,
        );
    }
}
