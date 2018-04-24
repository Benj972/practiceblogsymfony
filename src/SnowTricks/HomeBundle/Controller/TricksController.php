<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Form\Handler\MessageHandler;
use SnowTricks\HomeBundle\Form\Handler\TrickHandler;
use SnowTricks\HomeBundle\Form\Handler\DeleteHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;

class TricksController extends Controller
{
    public function homeAction($page, EntityManagerInterface $em)
    {
        $listTricks = $em->getRepository(Trick::class)->getTricks($page);

        return $this->render(
            'SnowTricksHomeBundle:Tricks:home.html.twig',
            array(
                'listTricks' => $listTricks,
                'nbPages' => ceil(count($listTricks) / 10),
                'page' => $page,
            )
        );
    }

    /**
     * @ParamConverter("trick", options={"mapping": {"slug":"slug"}})
     */
    public function viewAction(Trick $trick, MessageHandler $handler, EntityManagerInterface $em, $page = 1)
    {
        if ($response = $handler->handle($trick)) {
            return $response;
        }

        $listMessages = $em->getRepository(Message::class)->getMessages($page, $trick);

        return $this->render(
            'SnowTricksHomeBundle:Tricks:view.html.twig',
            array(
                'trick' => $trick,
                'listMessages' => $listMessages,
                'nbPages' => ceil(count($listMessages) / 5),
                'page' => $page,
                'form' => $handler->getForm()->createView(),
            )
        );
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
