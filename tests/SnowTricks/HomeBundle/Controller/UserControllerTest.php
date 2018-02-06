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

    	// On vérifie que l'email a bien été envoyé
    if ($profile = $client->getProfile())
    {
        $swiftMailerProfiler = $profile->getCollector('swiftmailer');

        // Seul 1 message doit avoir été envoyé
        $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

        // On récupère le premier message
        $messages = $swiftMailerProfiler->getMessages();
        $message  = array_shift($messages);

        $symblogEmail = $client->getContainer()->getParameter('snow_tricks_home.request_password_mail');
        // On vérifie que le message a été envoyé à la bonne adresse
        $this->assertArrayHasKey($symblogEmail, $message->getTo());
    }

	$crawler = $client->followRedirect();
    $this->assertEquals(1, $crawler->filter('html:contains("Un email a été envoyé à votre boîte aux lettres pour réinitialiser votre mot de passe.")')->count());
	}

}