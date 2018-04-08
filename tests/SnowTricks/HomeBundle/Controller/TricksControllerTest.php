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

    public function testHomepageIsUp($page=1)
    {   
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $totalTricks = $this->em->getRepository(Trick::class)->findAll();
        $nbPerPage = 10;
        $totalPage = ceil(count($totalTricks) / $nbPerPage);

        $listTricks = $this->em->getRepository(Trick::class)->getTricks($page, $nbPerPage);
        $this->assertEquals(count($listTricks), count($totalTricks));

        $nbPages = ceil(count($listTricks) / $nbPerPage);
        $this->assertEquals($nbPages, $totalPage); 

        $crawler = $this->secondClient->request('GET', '/');
        $this->assertSame(200, $this->secondClient->getResponse()->getStatusCode());
        $this->assertSame(2, $crawler->filter('h1')->count());
        $this->assertEquals($nbPerPage, $crawler->filter('h3')->count());

        /* for lisTricks=13 and nbPages=2*/
        $crawler = $this->secondClient->request('GET', '/2');
        $this->assertSame(200, $this->secondClient->getResponse()->getStatusCode());
        $nbtrickslastpage = fmod(count($listTricks), $nbPerPage);
        $this->assertEquals($nbtrickslastpage, $crawler->filter('h3')->count());
    }

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
        $this->assertEquals(1, $crawler->filter('html:contains("Ajouter une figure")')->count());

        if ($this->client->getResponse()->getStatusCode() === Response::HTTP_OK) {
            
            $extract = $crawler->filter('input[name="trick[_token]"]')
                ->extract(array('value'));
            $csrfToken = $extract[0];
            $form = $crawler->selectButton('Enregistrez')->form();

            $formData = $form->getPhpValues();

            $formData = [
                "trick" => [
                    "name" => "testtrick",
                    "content" => "Hello world ",
                    "category" => 1,
                    '_token' => $csrfToken,
                    "videos" => [
                        [
                            "alt" => "top",
                            "url" => "https://www.youtube.com/embed/n0F6hSpxaFc"
                        ],
                        [
                            "alt" => "top",
                            "url" => "https://www.youtube.com/embed/n0F6hSpxaFc"
                        ]
                    ]
                ]
            ];

            $image1 = new UploadedFile(
                        __DIR__."/ImgTests/vador.png",
                        'vador.png',
                        'image/png',
                        123
                    );

            $image2 = new UploadedFile(
                        __DIR__."/ImgTests/mataiea.jpeg",
                        'mataiea.jpeg',
                        'image/jpeg',
                        123
                    );

            $filesData = $form->getPhpFiles();

            $filesData = [
                "trick" => [
                    "images" => [
                        ["file" => $image1],
                        ["file" => $image2]
                    ]
                ]
            ];

            $crawler = $this->client->request('POST', '/add', $formData, $filesData);
            
            $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
            );

            $crawler = $this->client->followRedirect();

            $this->assertEquals(1, $crawler->filter('html:contains("Figure bien enregistrée.")')->count());
        }
         
    }

    public function testAddTrickWithoutLogin()
    {
        $crawler = $this->secondClient->request('GET', '/add');

        $crawler = $this->secondClient->followRedirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Se Connecter")')->count());
    }

    public function testViewTrick()
    {
        $crawler = $this->secondClient->request('GET', '/tricks/indy');

        $this->assertSame(200, $this->secondClient->getResponse()->getStatusCode());
    }
    
    public function testViewTrickWithLogin()
    {
        $crawler = $this->client->request('GET', '/tricks/indy', array(), array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Titre Message")')->count());

        if ($this->client->getResponse()->getStatusCode() === Response::HTTP_OK) {
            $form = $crawler->selectButton('Envoyez')->form();

            $form['snowtricks_homebundle_message[title]'] = 'test';
            $form['snowtricks_homebundle_message[content]'] = 'MessageTest';
            
            $crawler = $this->client->submit($form);
            $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
            );
            $crawler = $this->client->followRedirect();

            $this->assertEquals(1, $crawler->filter('html:contains("MessageTest")')->count());
        }
    }
 
    public function testTricksDeleted()
    {
        $crawler = $this->client->request('GET', '/delete/mute', array(), array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
   
        $this->assertEquals(1, $crawler->filter('html:contains("Supprimer une annonce")')->count());
        $extract = $crawler->filter('input[name="form[_token]"]')
        ->extract(array('value'));
        $csrfToken = $extract[0];
        $form = $crawler->selectButton('Supprimer')->form();
        
        $form['form[_token]'] = $csrfToken;

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("La figure a bien été supprimée.")')->count());
    }
    
    public function testEditTrickWithLogin()
    {
        $crawler = $this->client->request('GET', '/edit/sad', array(), array(), array(
        'PHP_AUTH_USER' => 'dede@gmail.fr',
        'PHP_AUTH_PW'   => 'dede2017',
        ));

        $this->assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertEquals(1, $crawler->filter('html:contains("Modifier une figure")')->count());

        if ($this->client->getResponse()->getStatusCode() === Response::HTTP_OK) {
            
            $extract = $crawler->filter('input[name="trick[_token]"]')
                ->extract(array('value'));
            $csrfToken = $extract[0];    

            $form = $crawler->selectButton('Enregistrez')->form();

            $formData2 = $form->getPhpValues();

            $formData2 = [
                "trick" => [
                    "name" => "sadmodifié",
                    "content" => "Hello world ",
                    "category" => 1,
                    '_token' => $csrfToken,
                    "videos" => [
                        [
                            "alt" => "topedit",
                            "url" => "https://www.youtube.com/embed/7CjRlQuqGTQ"
                        ]
                    ]
                ]
            ];

            $image = new UploadedFile(
                        __DIR__."/ImgTests/ski.jpeg",
                        'ski.jpeg',
                        'image/jpeg',
                        123
                    );

            $filesData2 = $form->getPhpFiles();

            $filesData2 = [
                "trick" => [
                    "images" => [
                        ["file" => $image],
                    ]
                ]
            ];

            $crawler = $this->client->request('POST', '/edit/sad', $formData2, $filesData2);

            $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
            );

            $crawler = $this->client->followRedirect();

            $this->assertEquals(1, $crawler->filter('html:contains("Figure bien modifiée.")')->count());
        }

    }
}
?>