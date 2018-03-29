<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\Member;
use SnowTricks\HomeBundle\Entity\Video;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Category;
use SnowTricks\HomeBundle\Form\TrickType;
use SnowTricks\HomeBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
 	public function viewAction(Trick $trick, $page=1, Request $request)
  	{
        $user = $this->getUser();
        $message = new Message();

        $form = $this->get('form.factory')->create(MessageType::class, $message);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $message->setUser($user);
            $message->setTrick($trick);
            $message->setDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('snow_tricks_home_view', array('slug' => $trick->getSlug(), '_fragment' => 'discussion'));
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
        $user = $this->getUser();
        $trick = new Trick();

        //second method form nested
        //$video = new Video();
        //$image = new Image();
        //$trick->addVideo($video);
        //$trick->addImage($image);

        $form = $this->get('form.factory')->create(TrickType::class, $trick);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $trick->setUser($user);
            $trick->setDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Figure bien enregistrée.');
            return $this->redirectToRoute('snow_tricks_home_homepage',array('_fragment' => 'info'));
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('SnowTricksHomeBundle:Tricks:add.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView(),
        ));
    }

    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
    public function editAction(Trick $trick, Request $request) 
    {

        $em = $this->getDoctrine()->getManager();
        $originalImages = new ArrayCollection();
        $originalVideos = new ArrayCollection();

        foreach ($trick->getImages() as $image) {
            $originalImages->add($image);
        }

        foreach ($trick->getVideos() as $video) {
            $originalVideos->add($video);
        }

        $editForm = $this->createForm(TrickType::class, $trick);

        $editForm->handleRequest($request);
      
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            foreach ($originalImages as $image) {
                if (false === $trick->getImages()->contains($image)) {
                    $image->setTrick(null);
                    $em->remove($image);
                }
            }

            foreach ($originalVideos as $video) {
                if (false === $trick->getVideos()->contains($video)) {
                    $video->setTrick(null);
                    $em->remove($video);
                }
            }
            
            $trick->setDate(new \DateTime('now'));
            $em->persist($trick);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Figure bien modifiée.');
            return $this->redirectToRoute('snow_tricks_home_homepage', array('_fragment' => 'info'));
        }

        return $this->render('SnowTricksHomeBundle:Tricks:edit.html.twig', array(
            'trick' => $trick,
            'form'   => $editForm->createView(),
        ));
    }
  
    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
    public function deleteAction(Request $request, Trick $trick)
    {
         
      $em = $this->getDoctrine()->getManager();

      if (null === $trick) {
        throw new NotFoundHttpException("La figure n'existe pas.");
      }

      $form = $this->get('form.factory')->create();

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           $em->remove($trick);
           $em->flush();
           $request->getSession()->getFlashBag()->add('info', 'La figure a bien été supprimée.');
           return $this->redirectToRoute('snow_tricks_home_homepage', array('_fragment' => 'info'));
      }

      return $this->render('SnowTricksHomeBundle:Tricks:delete.html.twig', array(
           'trick' => $trick,
           'form'   => $form->createView(),
      ));
    }
    
}