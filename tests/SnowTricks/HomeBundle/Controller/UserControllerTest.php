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

class UserControllerTest extends WebTestCase
{

	public function testContact()
	{	
        $client = static::createClient();

        $crawler = $client->request('GET', '/request');

        $this->assertEquals(1, $crawler->filter('h2:contains("Mot de passe oublié")')->count());

        $form = $crawler->selectButton('Demandez un nouveau mot de passe')->form();

        $form['request_password[email]'] = 'dede@gmail.fr';

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
                $this->assertSame('dede@gmail.fr', key($message->getTo()));
            }

	    $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Un email a été envoyé à votre boîte aux lettres pour réinitialiser votre mot de passe.")')->count());
	}

    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("S'inscrire")->form();

            $form['user_registration[pseudo]'] = 'test';
            $form['user_registration[avatar][file]']->upload('../../Path/vador.png');
            $form['user_registration[email]'] = 'test@gmail.fr';
            $form['user_registration[plainPassword][first]'] = 'Symfony';
            $form['user_registration[plainPassword][second]'] = 'Symfony';

            $client->submit($form);

            $crawler = $client->followRedirect();
            $this->assertEquals(1, $crawler->filter('html:contains("Welcome")')->count());
    }

    public function testChangePassword()
    {
        $client = static::createClient(array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $crawler = $client->request('GET', '/change', array(), array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("Validez")->form();

            $form['change_password[oldPassword]'] = 'dede2017';
            $form['change_password[newPassword][first]'] = 'dede2018';
            $form['change_password[newPassword][second]'] = 'dede2018';

            $client->submit($form);

            $crawler = $client->followRedirect();
            $this->assertEquals(1, $crawler->filter('html:contains("Le mot de passe est changé avec succès!")')->count());
    }

   /* public function testResetPassword()
    {

    }*/
}