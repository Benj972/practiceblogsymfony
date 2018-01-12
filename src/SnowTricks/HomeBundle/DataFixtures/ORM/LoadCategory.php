<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadCategory extends AbstractFixture 
{

  public function load(ObjectManager $manager)
  {
  
    $category1 = new Category;
    $category1->setName('Les Grabs');
    $manager->persist($category1);

    $category2 = new Category;
    $category2->setName('Les Rotations');
    $manager->persist($category2);

    $category3 = new Category;
    $category3->setName('Les Flips');
    $manager->persist($category3);

    $category4 = new Category;
    $category4->setName('Hors Catégories');
    $manager->persist($category4);

    $manager->flush();

    $this->addReference('category1', $category1);
    $this->addReference('category2', $category2);
    $this->addReference('category3', $category3);
    $this->addReference('category4', $category4);

  }


}