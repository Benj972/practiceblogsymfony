<?php
namespace Tests\SnowTricks\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SnowTricks\HomeBundle\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SnowTricks\HomeBundle\Repository\UserRepository;
use SnowTricks\HomeBundle\Form\LoginType;
use SnowTricks\HomeBundle\Entity\User;

class SecurityControllerTest extends WebTestCase
{
	public function testLoginAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        
        $this->assertSame(200, $client->getResponse()->getStatusCode());

     	$form = $crawler->selectButton('Se connecter')->form();
        $form['login[_username]'] = 'dede@gmail.fr';
        $form['login[_password]'] = 'dede2017';
        $crawler = $client->submit($form);
		
        $crawler = $client->followRedirect(); 
        $this->assertEquals(1, $crawler->filter('html:contains("Vous êtes bien enregistré")')->count());
    }

    public function testLogoutAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/logout');
        
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("SnowTricks")')->count());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

?>