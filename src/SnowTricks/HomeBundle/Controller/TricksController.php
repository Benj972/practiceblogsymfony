<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\Member;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
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

       $nbPages = ceil(count($listAdverts) / $nbPerPage);
        
      if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");

      return $this->render('SnowTricksHomeBundle:Tricks:home.html.twig', array(
      'listTricks' => $listTricks,
      'nbPages'    => $nbPages,
      'page'       => $page,
    ));

    }
    }


 	public function viewAction($id)
  	{
      $em = $this->getDoctrine()->getManager();
      // On récupère l'annonce $id
      $trick= $em->getRepository('SnowTricksHomeBundle:Trick')->find(7);
      // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
      // ou null si l'id $id n'existe pas, d'où ce if :
      if (null === $trick) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }
    	
      $listImages = $em
       ->getRepository('SnowTricksHomeBundle:Image')
       ->findBy(array('trick' => $trick))
       ;

      $listMessages = $em
       ->getRepository('SnowTricksHomeBundle:Message')
       ->findBy(array('trick' => $trick))
       ;

      $member = $em->getRepository('SnowTricksHomeBundle:Member')->find($id);
  
      return $this->render('SnowTricksHomeBundle:Tricks:view.html.twig', array(
          'trick' => $trick,
          'listImages' => $listImages,
          'listMessages' => $listMessages,
          'member' => $member,
            ));   
    }


    public function addAction(Request $request)
    {

      $em = $this->getDoctrine()->getManager();

    $trick = new Trick();
    $trick->setName('Mute');
    $trick->setContent("Saisie de la carre frontside de la planche 
      entre les deux pieds avec la main avant.");

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

    $em->persist($trick);
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

     public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
    $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );
    return $this->render('SnowTricksHomeBundle:Tricks:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
  }
}