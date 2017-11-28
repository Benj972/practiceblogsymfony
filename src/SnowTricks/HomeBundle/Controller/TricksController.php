<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\Member;
use SnowTricks\HomeBundle\Entity\Video;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TricksController extends Controller
{

    public function homeAction($page)
    {
       if ($page < 1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

      $nbPerPage = 3;

      $listTricks = $this->getDoctrine()
       ->getManager()
       ->getRepository('SnowTricksHomeBundle:Trick')
       ->getTricks($page, $nbPerPage)
       ;

       $nbPages = ceil(count($listTricks) / $nbPerPage);
        
      if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }
      return $this->render('SnowTricksHomeBundle:Tricks:home.html.twig', array(
      'listTricks' => $listTricks,
      'nbPages'    => $nbPages,
      'page'       => $page,
    ));

      
    }


 	  public function viewAction(Trick $trick)
  	{
      return $this->render('SnowTricksHomeBundle:Tricks:view.html.twig', array(
          'trick' => $trick,
            ));   
    }


    public function addAction(Request $request)
    {

      $em = $this->getDoctrine()->getManager();

    $category = new Category();
    $category->setName('Les PAs Grabs');

    $trick = new Trick();
    $trick->setName('Mute2');
    $trick->setContent("Saisie de la carre frontside de la planche 
      entre les deux pieds avec la main avant.");

    $trick->setCategory($category);

    $video1 = new Video();
    $video1->setUrl('https://www.youtube.com/embed/6z6KBAbM0MY');
    $video1->setAlt('truc de ouf');

    $video2 = new Video();
    $video2->setUrl('https://www.youtube.com/embed/6z6KBAbM0MY');
    $video2->setAlt('Oops');

    $video1->setTrick($trick);
    $video2->setTrick($trick);

    $image1 = new Image();
    $image1->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $image1->setAlt('Job de rêve');

    $image2 = new Image();
    $image2->setUrl('http://snowforever.free.fr/snowboard/grabs/mute.jpg');
    $image2->setAlt('Coucou du soleil');

    $image1->setTrick($trick);
    $image2->setTrick($trick);

    $member = new Member();
    $member->setPseudo('Ben');
    $member->setLogin('tratata');
    $member->setPassword('bbeenn');
    $member->setDescription('trarratratartratratratratratrarratartrartaaartar');
    $member->setPicture('https://cdn1.iconfinder.com/data/icons/ninja-things-1/1772/ninja-simple-512.png');

    $message1 = new Message();
    $message1->setTitle('Enjoy');
    $message1->setContent('arartataraatatatatatatatatatatatata');
    $message1->setDate(new \DateTime());

    $message2 = new Message();
    $message2->setTitle('Hello');
    $message2->setContent('Hello World');
    $message2->setDate(new \DateTime());

    $message1->setTrick($trick);
    $message2->setTrick($trick);
    $message1->setMember($member);
    $message2->setMember($member);
    
    $em->persist($category);
    $em->persist($trick);
    $em->persist($video1);
    $em->persist($video2);
    $em->persist($image1);
    $em->persist($image2);
    $em->persist($member);
    $em->persist($message1);
    $em->persist($message2);
    
  
    // Étape 2 : On déclenche l'enregistrement
    $em->flush();

    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('snow_tricks_home_view', array('id' => $trick->getId()));
    }
    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('SnowTricksHomeBundle:Tricks:add.html.twig');
    }

    public function editAction(Trick $trick, Request $request) 
    {}
    public function deleteAction(Request $request, Trick $trick)
    {}
    public function addmessageAction( Request $request)
    {}
}