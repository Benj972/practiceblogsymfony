<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Form\UserRegistrationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class RegisterHandler
{
    /**
     * @var FormFactoryInterface
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
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * TrickHandler constructor.
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $flashBag
     * @param Environment $twig
     * @param RouterInterface $router
     */
    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack, EntityManagerInterface $manager, FlashBagInterface $flashBag, Environment $twig, RouterInterface $router)
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
    public function handle()
    {
        $form = $this->formFactory->create(UserRegistrationType::class)->handleRequest($this->requestStack->getCurrentRequest());
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->manager->persist($user);
            $this->manager->flush();
            $this->flashBag->add('info', 'Bienvenue '.$user->getEmail());
            return new RedirectResponse($this->router->generate('snow_tricks_home_login', array('_fragment' => 'info')));
        }

        return new Response($this->twig->render('SnowTricksHomeBundle:User:register.html.twig', array(
            'form'  => $form->createView(),
        )));
    }
}