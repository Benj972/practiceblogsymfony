<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use SnowTricks\HomeBundle\Form\ChangePasswordType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChangePasswordHandler
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

    private $container;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ChangePasswordHandler constructor.
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $flashBag
     * @param Environment $twig
     * @param RouterInterface $router
     */
    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack, EntityManagerInterface $manager, FlashBagInterface $flashBag, Environment $twig, RouterInterface $router, ContainerInterface $container, TokenStorageInterface $tokenStorage)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->manager = $manager;
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->router = $router;
        $this->container = $container;
        $this->tokenStorage = $tokenStorage;
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
        $changePasswordModel = new ChangePassword();
        $user = $this->tokenStorage->getToken()->getUser();

        $form = $this->formFactory->create(ChangePasswordType::class, $changePasswordModel)->handleRequest($this->requestStack->getCurrentRequest());
        if($form->isSubmitted() && $form->isValid()) {
            $passwordEncoder = $this->container->get('security.password_encoder');
            $oldplainPassword = $changePasswordModel->getOldPassword();
            $plainPassword = $changePasswordModel->getNewPassword();

              if (!$passwordEncoder->isPasswordValid($user, $oldplainPassword)) {
                    $this->addFlash('info', "Wrong old password!");
                } 

            $user->setPlainPassword($plainPassword);
            $this->manager->persist($user);
            $this->manager->flush();

            $this->flashBag->add('info', "Le mot de passe est changé avec succès!");    
            return new RedirectResponse($this->router->generate('snow_tricks_home_homepage', array('_fragment' => 'info')));
        }

        return new Response($this->twig->render('SnowTricksHomeBundle:User:changePassword.html.twig', array(
            'form'  => $form->createView(),
        )));
    }
}