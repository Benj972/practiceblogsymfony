<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Entity\Video;
use SnowTricks\HomeBundle\Entity\Image;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Category;
use SnowTricks\HomeBundle\Form\Handler\MessageHandler;
use SnowTricks\HomeBundle\Form\Handler\TrickHandler;
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
use SnowTricks\HomeBundle\Form\Handler\AddHandler;
use SnowTricks\HomeBundle\Form\Handler\EditHandler;
use SnowTricks\HomeBundle\Form\Handler\DeleteHandler;

class TricksController extends Controller
{

    public function homeAction($page , EntityManagerInterface $em)
    {

        $listTricks = $em->getRepository(Trick::class)->getTricks($page);

        return $this->render('SnowTricksHomeBundle:Tricks:home.html.twig', array(
            'listTricks' => $listTricks,
            'nbPages'    => ceil(count($listTricks) / 10),
            'page'       => $page,
        )); 
    }

    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
 	public function viewAction(Trick $trick, $page=1, MessageHandler $handler, EntityManagerInterface $em)
  	{
        if($response = $handler->handle($trick)) {
            return $response;
        }

        $listMessages = $em->getRepository(Message::class)->getMessages($page, $trick);
        
        return $this->render('SnowTricksHomeBundle:Tricks:view.html.twig', array(
            'trick' => $trick,
            'listMessages' => $listMessages,
            'nbPages' => ceil(count($listMessages) / 5),
            'page' => $page,
            'form' => $handler->getForm()->createView()
        ));   
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function addAction(TrickHandler $handler)
    {
        return $handler->handle(new Trick(), 'Figure ajoutée avec succès.');
    }

    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
    public function editAction(Trick $trick, TrickHandler $handler) 
    {
        return $handler->handle($trick, 'Figure modifiée avec succès.');
    }
  
    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
    public function deleteAction(Trick $trick, DeleteHandler $handler)
    {
        return $handler->handle($trick, 'Figure supprimée avec succès.');    
    }
    
}