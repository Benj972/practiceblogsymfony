<?php

namespace Tests\SnowTricks\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SnowTricks\HomeBundle\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SnowTricks\HomeBundle\Form\ChangePasswordType;
use SnowTricks\HomeBundle\Form\ResetPasswordType;
use SnowTricks\HomeBundle\Form\RequestPasswordType;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use SnowTricks\HomeBundle\Form\Model\ResetPassword;
use SnowTricks\HomeBundle\Form\Model\RequestPassword;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use SnowTricks\HomeBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

class UserControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("S'inscrire")->form();

            $form['user_registration[pseudo]'] = 'test';
            $form['user_registration[avatar][file]']->upload(__DIR__."/ImgTests/avatar_snow.jpg");
            $form['user_registration[email]'] = 'test@gmail.fr';
            $form['user_registration[plainPassword][first]'] = 'Symfony2018';
            $form['user_registration[plainPassword][second]'] = 'Symfony2018';

            $client->submit($form);

            $crawler = $client->followRedirect();
            $this->assertEquals(1, $crawler->filter('html:contains("Bienvenue")')->count());
    }

    public function testChangePassword()
    {
        $client = static::createClient(array(), array(
        'PHP_AUTH_USER' => 'ben.gallot@gmail.fr',
        'PHP_AUTH_PW'   => 'benjamin2017',
        ));

        $crawler = $client->request('GET', '/change', array(), array(), array(
        'PHP_AUTH_USER' => 'ben.gallot@gmail.fr',
        'PHP_AUTH_PW'   => 'benjamin2017',
        ));

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("Validez")->form();

            $form['change_password[oldPassword]'] = 'benjamin2017';
            $form['change_password[newPassword][first]'] = 'benjamin2018';
            $form['change_password[newPassword][second]'] = 'benjamin2018';

            $client->submit($form);

            $crawler = $client->followRedirect();
            $this->assertEquals(1, $crawler->filter('html:contains("Le mot de passe est changé avec succès!")')->count());
    }

    public function testContact()
    {   
        $client = static::createClient();

        $crawler = $client->request('GET', '/request');

        $this->assertEquals(1, $crawler->filter('h2:contains("Mot de passe oublié")')->count());

        $form = $crawler->selectButton('Demandez un nouveau mot de passe')->form();

        $form['request_password[email]'] = 'ben.gallot@gmail.fr';

        $client->submit($form);

        $client->enableProfiler();
           // On vérifie que l'email a bien été envoyé
            if ($profile = $client->getProfile())
            {
                $swiftMailerProfiler = $profile->getCollector('swiftmailer');

                // Seul 1 message doit avoir été envoyé
                $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

                // On récupère le premier message
                $messages = $swiftMailerProfiler->getMessages();
                $message  = array_shift($messages);

                $this->assertSame('SnowTricks : Récupération de votre mot de passe', $message->getSubject());
                $this->assertSame('ben.gallot@gmail.fr', key($message->getTo()));
            }

        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Un email vous a été envoyé pour réinitialiser votre mot de passe.")')->count());
    }

    public function testResetPassword()
    {
        $client = static::createClient();

        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $this->em->getRepository('SnowTricksHomeBundle:User')->findOneBy(array('email'=>'test@gmail.fr'));
        $token = $user->setToken(3);
        $this->em->persist($user);
        $this->em->flush();
        
        $crawler = $client->request('GET', '/reset/3');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("Validez")->form();
            $form['reset_password[plainPassword][first]'] = 'reset2018';
            $form['reset_password[plainPassword][second]'] = 'reset2018';

        $client->submit($form);
        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
            );
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Votre mot de passe a été réinitialisé. Vous pouvez vous connecter.")')->count());
    }
}
