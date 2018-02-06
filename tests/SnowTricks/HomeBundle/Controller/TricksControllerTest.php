<?php
namespace Tests\SnowTricks\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SnowTricks\HomeBundle\Controller\TricksController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Form\ImageType;
use Symfony\Component\DomCrawler\Crawler;
use SnowTricks\HomeBundle\Form\TrickType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TricksControllerTest extends WebTestCase
{
    private $client = null;
    
    private $secondClient = null;

    public function setUp()
    {
        $this->client = static::createClient(array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->secondClient = static::createClient();
    }

    /*public function testHomepageIsUp()
    {
        
        $crawler = $this->secondClient->request('GET', '/');
        
        $this->assertSame(200, $this->secondClient->getResponse()->getStatusCode());
        $this->assertSame(2, $crawler->filter('h1')->count());
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
  

        $page = 2;
        $nbPerPage = 10;
        $listTricks = $this->em
            ->getRepository(Trick::class)
            ->getTricks($page, $nbPerPage)
        ;
        $tricks = $this->em
            ->getRepository(Trick::class)
            ->findAll()
        ;
        $this->assertSame(count($tricks), count($listTricks));
    }*/

    /*public function testTrickLink()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $linksCrawler = $crawler->selectLink('Sad');
        $link = $linksCrawler->link();
    }*/

    public function testAddTrickWithLogin()
    {
       
        $crawler = $this->client->request('GET', '/add', array(), array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        if ($this->client->getResponse()->getStatusCode() === Response::HTTP_OK) {
          
        $form = $crawler->selectButton('Save')->form();
        
        $img = new UploadedFile(
                'C:/Users/laure.l/Desktop/avatartest.jpg',
                'avatartest.jpg',
                'images/jpeg',
                123
            );

        $values = $form->getPhpValues();

        $form['snowtricks_homebundle_trick[name]'] = 'John';
        $form['snowtricks_homebundle_trick[content]'] = 'john2017ssssssssssssssssssss';
        $form['snowtricks_homebundle_trick[category]'] ->select(1);
        /*$values['snowtricks_homebundle_trick']['images'][0]['file'] = upload($img);*/
        /*$values['snowtricks_homebundle_trick']['videos'][0]['url'] = 'https://www.youtube.com/embed/70g_LGD6Oro';
        $values['snowtricks_homebundle_trick']['videos'][0]['alt'] = 'Test';*/

        $crawler = $this->client->request(
                $form->getMethod(),
                $form->getUri(),
                $values,
                $form->getPhpFiles()
            );

        $this->assertTrue($this->client->getResponse()->isRedirect());
        
        }
    }

    /*public function testAddTrickRedirection()
    {

    }*/

   /* public function testViewTrick()
    {
        $crawler = $this->secondClient->request('GET', '/tricks/1');

        $this->assertSame(200, $this->secondClient->getResponse()->getStatusCode());

    }

    public function testViewTrickWithLogin()
    {
        $crawler = $this->client->request('GET', '/tricks/1', array(), array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Title")')->count());

        if ($this->client->getResponse()->getStatusCode() === Response::HTTP_OK) {
            $form = $crawler->selectButton('Save')->form();

            $form['snowtricks_homebundle_message[title]'] = 'test';
            $form['snowtricks_homebundle_message[content]'] = 'Symfony';
            
            $crawler = $this->client->submit($form);
            $this->assertEquals(
                Response::HTTP_OK,
                $this->client->getResponse()->getStatusCode()
            );
        }
    }
*/
    /*public function testAddMessageTrick()
    {

    }*/

}
?>