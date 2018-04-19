<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Entity\Trick;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Router;
use Twig\Environment;

class DeleteHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Router
     */
    private $router;

    /**
     * TrickHandler constructor.
     * @param FormFactory $formFactory
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param FlashBag $flashBag
     * @param Environment $twig
     * @param Router $router
     */
    public function __construct(FormFactory $formFactory, RequestStack $requestStack, EntityManagerInterface $manager, FlashBag $flashBag, Environment $twig, Router $router)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->manager = $manager;
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param Trick $trick
     * @param string $validatedMessage
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(Trick $trick, string $validatedMessage)
    {
        $form = $this->formFactory->create()->handleRequest($this->requestStack->getCurrentRequest());
        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->remove($trick);
            $this->manager->flush();
            $this->flashBag->add('info', $validatedMessage);
            return new RedirectResponse($this->router->generate('snow_tricks_home_homepage', array('_fragment' => 'info')));
        }

        return new Response($this->twig->render('SnowTricksHomeBundle:Tricks:delete.html.twig', array(
            'trick' => $trick,
            'form'  => $form->createView(),
        )));
    }
}