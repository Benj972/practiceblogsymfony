<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\Trick;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadTrick extends Fixture
{
  public function load(ObjectManager $manager)
  {
  	$trick1 = new Trick;
  	$trick1->setName('Mute');
  	$trick1->setCategory($this->getReference('category1'));
  	$trick1->setUser($this->getReference('user1'));
  	$trick1->setContent('Saisie de la carre frontside de la planche 
      entre les deux pieds avec la main avant.');
  	$manager->persist($trick1);

  	$trick2 = new Trick;
  	$trick2->setName('Sad');
  	$trick2->setCategory($this->getReference('category1'));
  	$trick2->setUser($this->getReference('user2'));
  	$trick2->setContent('Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.');
  	$manager->persist($trick2);

  	$trick3 = new Trick;
  	$trick3->setName('Indy');
  	$trick3->setCategory($this->getReference('category1'));
  	$trick3->setUser($this->getReference('user3'));
  	$trick3->setContent('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.');
  	$manager->persist($trick3);

  	$trick4 = new Trick;
  	$trick4->setName('Stalefish');
  	$trick4->setCategory($this->getReference('category1'));
  	$trick4->setUser($this->getReference('user3'));
  	$trick4->setContent('Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.');
  	$manager->persist($trick4);

  	$trick5 = new Trick;
  	$trick5->setName('Tail grab');
  	$trick5->setCategory($this->getReference('category1'));
  	$trick5->setUser($this->getReference('user1'));
  	$trick5->setContent('Saisie de la partie arrière de la planche, avec la main arrière.');
  	$manager->persist($trick5);

  	$trick6 = new Trick;
  	$trick6->setName('Nose grab');
  	$trick6->setCategory($this->getReference('category1'));
  	$trick6->setUser($this->getReference('user2'));
  	$trick6->setContent('Saisie de la partie avant de la planche, avec la main avant.');
  	$manager->persist($trick6);

  	$trick7 = new Trick;
  	$trick7->setName('Le 180');
  	$trick7->setCategory($this->getReference('category2'));
  	$trick7->setUser($this->getReference('user1'));
  	$trick7->setContent('Un 180 désigne un demi-tour.');
  	$manager->persist($trick7);

  	$trick8 = new Trick;
  	$trick8->setName('Le 360');
  	$trick8->setCategory($this->getReference('category2'));
  	$trick8->setUser($this->getReference('user2'));
  	$trick8->setContent('Trois six pour un tour complet.');
  	$manager->persist($trick8);

  	$trick9 = new Trick;
  	$trick9->setName('Le 540');
  	$trick9->setCategory($this->getReference('category2'));
  	$trick9->setUser($this->getReference('user3'));
  	$trick9->setContent('Cinq quatre pour un tour et demi.');
  	$manager->persist($trick9);

  	$trick10 = new Trick;
  	$trick10->setName('Le 720');
  	$trick10->setCategory($this->getReference('category2'));
  	$trick10->setUser($this->getReference('user3'));
  	$trick10->setContent('Sept deux pour deux tours complets.');
  	$manager->persist($trick10);

  	$trick11 = new Trick;
  	$trick11->setName('Le front flip');
  	$trick11->setCategory($this->getReference('category3'));
  	$trick11->setUser($this->getReference('user1'));
  	$trick11->setContent('Une rotation verticale en avant.');
  	$manager->persist($trick11);

  	$trick12 = new Trick;
  	$trick12->setName('Le back flip');
  	$trick12->setCategory($this->getReference('category3'));
  	$trick12->setUser($this->getReference('user2'));
  	$trick12->setContent('Une rotation verticale en arrière.');
  	$manager->persist($trick12);

  	$trick13 = new Trick;
  	$trick13->setName('Le TricksBen');
  	$trick13->setCategory($this->getReference('category4'));
  	$trick13->setUser($this->getReference('user3'));
  	$trick13->setContent('Le saut étoile');
  	$manager->persist($trick13);

  	$manager->flush();

  	$this->addReference('trick1', $trick1);
    $this->addReference('trick2', $trick2);
    $this->addReference('trick3', $trick3);
    $this->addReference('trick4', $trick4);
    $this->addReference('trick5', $trick5);
    $this->addReference('trick6', $trick6);
    $this->addReference('trick7', $trick7);
    $this->addReference('trick8', $trick8);
    $this->addReference('trick9', $trick9);
    $this->addReference('trick10', $trick10);
    $this->addReference('trick11', $trick11);
    $this->addReference('trick12', $trick12);
    $this->addReference('trick13', $trick13);
  }

  public function getDependencies()
  {
    return array(
    	LoadCategory::class,
    	LoadUser::class,
    );
  }
}