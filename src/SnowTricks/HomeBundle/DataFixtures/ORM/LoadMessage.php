<?php

namespace SnowTricks\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\HomeBundle\Entity\Message;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadMessage extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $message1 = new Message;
        $message1->setTitle('Mute');
        $message1->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message1->setDate(new \DateTime('2018-01-09'));
        $message1->setTrick($this->getReference('trick1'));
        $message1->setUser($this->getReference('user3'));
        $manager->persist($message1);

        $message2 = new Message;
        $message2->setTitle('Mute');
        $message2->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message2->setDate(new \DateTime('2018-01-10'));
        $message2->setTrick($this->getReference('trick1'));
        $message2->setUser($this->getReference('user1'));
        $manager->persist($message2);

        $message3 = new Message;
        $message3->setTitle('Mute');
        $message3->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message3->setDate(new \DateTime('2018-01-10'));
        $message3->setTrick($this->getReference('trick1'));
        $message3->setUser($this->getReference('user2'));
        $manager->persist($message3);

        $message4 = new Message;
        $message4->setTitle('Grab');
        $message4->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message4->setDate(new \DateTime('2018-01-10'));
        $message4->setTrick($this->getReference('trick2'));
        $message4->setUser($this->getReference('user3'));
        $manager->persist($message4);

        $message5 = new Message;
        $message5->setTitle('Grab');
        $message5->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message5->setDate(new \DateTime('2018-01-11'));
        $message5->setTrick($this->getReference('trick3'));
        $message5->setUser($this->getReference('user1'));
        $manager->persist($message5);

        $message6 = new Message;
        $message6->setTitle('Grab');
        $message6->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message6->setDate(new \DateTime('2018-01-12'));
        $message6->setTrick($this->getReference('trick3'));
        $message6->setUser($this->getReference('user2'));
        $manager->persist($message6);

        $message7 = new Message;
        $message7->setTitle('Grab');
        $message7->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message7->setDate(new \DateTime('2018-01-11'));
        $message7->setTrick($this->getReference('trick3'));
        $message7->setUser($this->getReference('user1'));
        $manager->persist($message7);

        $message8 = new Message;
        $message8->setTitle('Grab');
        $message8->setContent('Acciverunt concedentes retroque vicos casu abierat recreati relictum sedibus opulentos timor planitie concedentes planitie in.');
        $message8->setDate(new \DateTime('2018-01-11'));
        $message8->setTrick($this->getReference('trick4'));
        $message8->setUser($this->getReference('user1'));
        $manager->persist($message8);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
        LoadUser::class,
        LoadTrick::class,
        );
    }
}
