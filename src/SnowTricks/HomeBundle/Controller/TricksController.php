<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\Member;
use SnowTricks\HomeBundle\Entity\Video;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Category;
use SnowTricks\HomeBundle\Form\TrickType;
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

    $trick = new Trick();
    $form   = $this->get('form.factory')->create(TrickType::class, $trick);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

      $em = $this->getDoctrine()->getManager();
      $em->persist($trick);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Figure bien enregistrée.');
      return $this->redirectToRoute('snow_tricks_home_homepage');
    }
    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('SnowTricksHomeBundle:Tricks:add.html.twig', array('form' => $form->createView(), ));
    }

    public function editAction(Trick $trick, Request $request) 
    {}
    public function deleteAction(Request $request, Trick $trick)
    {}
    public function addmessageAction( Request $request)
    {}
}