<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Entity\Message;
use SnowTricks\HomeBundle\Entity\Trick;
use SnowTricks\HomeBundle\Form\MessageType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class MessageHandler
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var MessageType
     */
    private $form;

    /**
     * TrickHandler constructor.
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param RouterInterface $router
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack, EntityManagerInterface $manager, RouterInterface $router, TokenStorageInterface $tokenStorage)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->manager = $manager;
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
            return new RedirectResponse(
                $this->router->generate('snow_tricks_home_view', array('slug' => $trick->getSlug(), '_fragment' => 'discussion'))
            );
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
