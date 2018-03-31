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
use Doctrine\ORM\EntityManagerInterface;

class TricksController extends Controller
{

    public function homeAction($page , EntityManagerInterface $em)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $nbPerPage = 10;

        $listTricks = $em
            ->getRepository(Trick::class)
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
 	public function viewAction(Trick $trick, $page=1, Request $request, EntityManagerInterface $em)
  	{
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUser($this->getUser());
            $message->setTrick($trick);
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('snow_tricks_home_view', array('slug' => $trick->getSlug(), '_fragment' => 'discussion'));
        }

        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $nbPerPage = 5;

        $listMessages = $em
          ->getRepository(Message::class)
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
            'form' => $form->createView()
        ));   
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $trick = new Trick();
        //second method form nested
        //$video = new Video();
        //$image = new Image();
        //$trick->addVideo($video);
        //$trick->addImage($image);

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUser($this->getUser());
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
    public function editAction(Trick $trick, Request $request, EntityManagerInterface $em) 
    {

        $originalImages = clone $trick->getImages();
        $originalVideos = clone $trick->getVideos();

        $editForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);
      
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
    public function deleteAction(Request $request, Trick $trick, EntityManagerInterface $em)
    {

      if (null === $trick) {
        throw new NotFoundHttpException("La figure n'existe pas.");
      }

      $form = $this->get('form.factory')->create()->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
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