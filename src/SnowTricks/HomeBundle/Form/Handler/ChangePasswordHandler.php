<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use SnowTricks\HomeBundle\Form\ChangePasswordType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Router;
use Twig\Environment;

class ChangePasswordHandler
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

    private $container;

    /**
     * ChangePasswordHandler constructor.
     * @param FormFactory $formFactory
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param FlashBag $flashBag
     * @param Environment $twig
     * @param Router $router
     */
    public function __construct(FormFactory $formFactory, RequestStack $requestStack, EntityManagerInterface $manager, FlashBag $flashBag, Environment $twig, Router $router, ContainerInterface $container)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->manager = $manager;
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param Trick $trick
     * @param string $validatedMessage
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle($user)
    {
        $changePasswordModel = new ChangePassword();
        
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