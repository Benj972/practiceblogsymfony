<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\Member;
use SnowTricks\HomeBundle\Entity\Video;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Category;
use SnowTricks\HomeBundle\Form\TrickType;
use SnowTricks\HomeBundle\Form\TrickEditType;
use SnowTricks\HomeBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TricksController extends Controller
{

    public function homeAction($page)
    {
      if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

      $nbPerPage = 10;

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


 	  public function viewAction(Trick $trick, $page=1, Request $request)
  	{
      
      $message = new Message();

      $form = $this->get('form.factory')->create(MessageType::class, $message);

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $message->setUser($user = $this->getUser());
        $message->setTrick($trick);
        $message->setDate(new \DateTime('now'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
      }

      if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

      $nbPerPage = 5;

      $listMessages = $this->getDoctrine()
       ->getManager()
       ->getRepository('SnowTricksHomeBundle:Message')
       ->getMessages($page, $nbPerPage, $trick)
       ;

       $nbPages = ceil(count($listMessages) / $nbPerPage);
        
      if ($page > $nbPages) {
        //throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        $nbPages = 1;
      }

       return $this->render('SnowTricksHomeBundle:Tricks:view.html.twig', array(
          'trick' => $trick,
          'listMessages' => $listMessages,
          'nbPages' => $nbPages,
          'page' => $page,
          'message' => $message,
          'form' => $form->createview()
          ));   
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function addAction(Request $request)
    {
    
      $trick = new Trick();
      //second method form nested
      //$video = new Video();
      //$image = new Image();
      //$trick->addVideo($video);
      //$trick->addImage($image);

      $form = $this->get('form.factory')->create(TrickType::class, $trick);

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


    public function editAction($id, Request $request) 
    {
      $em = $this->getDoctrine()->getManager();

      $trick = $em->getRepository('SnowTricksHomeBundle:Trick')->find($id);

      if (null === $trick) {
        throw new NotFoundHttpException("La figure ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create(TrickEditType::class, $trick);

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->flush();
          $request->getSession()->getFlashBag()->add('info', 'Figure bien modifiée.');
          return $this->redirectToRoute('snow_tricks_home_homepage');
      }

      return $this->render('SnowTricksHomeBundle:Tricks:edit.html.twig', array(
      'trick' => $trick,
      'form'   => $form->createView(),
      ));
    }


    public function deleteAction(Request $request, $id)
    {
         
      $em = $this->getDoctrine()->getManager();

      $trick = $em->getRepository('SnowTricksHomeBundle:Trick')->find($id);

      if (null === $trick) {
        throw new NotFoundHttpException("La figure ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create();

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->remove($trick);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'La figure a bien été supprimée.');
        return $this->redirectToRoute('snow_tricks_home_homepage');
      }

      return $this->render('SnowTricksHomeBundle:Tricks:delete.html.twig', array(
      'trick' => $trick,
      'form'   => $form->createView(),
      ));
    }
    
}