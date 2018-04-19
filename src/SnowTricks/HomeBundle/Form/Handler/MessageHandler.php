<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Form\MessageType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Twig\Environment;

class MessageHandler
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
     * @var Router
     */
    private $router;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var MessageType
     */
    private $form;

    /**
     * TrickHandler constructor.
     * @param FormFactory $formFactory
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param FlashBag $flashBag
     * @param Router $router
     * @param TokenStorage $tokenStorage
     */
    public function __construct(FormFactory $formFactory, RequestStack $requestStack, EntityManagerInterface $manager, FlashBag $flashBag, Router $router, TokenStorage $tokenStorage)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->manager = $manager;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(Trick $trick)
    {
        $message = new Message();

        $this->form = $this->formFactory->create(MessageType::class, $message)->handleRequest($this->requestStack->getCurrentRequest());

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $message->setUser($this->tokenStorage->getToken()->getUser());
            $message->setTrick($trick);
            $this->manager->persist($message);
            $this->manager->flush();
            return new RedirectResponse($this->router->generate('snow_tricks_home_view', array('slug' => $trick->getSlug(), '_fragment' => 'discussion')));
        }

        return;
    }

    /**
     * @return MessageType
     */
    public function getForm()
    {
        return $this->form;
    }
}