<?php

namespace SnowTricks\HomeBundle\Form\Handler;

use SnowTricks\HomeBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\HomeBundle\Form\Model\ResetPassword;
use SnowTricks\HomeBundle\Form\Model\RequestPassword;
use SnowTricks\HomeBundle\Form\ResetPasswordType;
use SnowTricks\HomeBundle\Form\RequestPasswordType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Router;
use Twig\Environment;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResetPasswordHandler
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
    public function handle($token)
    {    
        $resetpassword = new ResetPassword; 
        $user = $this->manager->getRepository('SnowTricksHomeBundle:User')->findOneByToken($token);
       

        if($user !== null) {               
            $form = $this->formFactory->create(ResetPasswordType::class, $resetpassword)->handleRequest($this->requestStack->getCurrentRequest());
            $plainPassword = $resetpassword->getPlainPassword();
            
            if($form->isSubmitted() && $form->isValid()) {
                    $user->setToken(null);
                    $user->setPlainPassword($plainPassword);
                    $this->manager->flush();

                    $this->flashBag->add('info', "Votre mot de passe a été réinitialisé. Vous pouvez vous connecter.");    
                    return new RedirectResponse($this->router->generate('snow_tricks_home_homepage', array('_fragment' => 'info')));
            }
        }

        return new Response($this->twig->render('SnowTricksHomeBundle:User:resetPassword.html.twig', array(
            'form'  => $form->createView(),
        )));
    }

    public function requesthandle()
    {    
        $requestpassword = new RequestPassword; 
        
        $form = $this->formFactory->create(RequestPasswordType::class, $requestpassword)->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) { 
                $user = $this->manager->getRepository(User::class)->findOneByEmail($requestpassword->getEmail());

                if($user !== null) {
                        $confirmationtoken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                        $user->setToken($confirmationtoken);
                        $this->manager->flush();
                        $email = $this->container->get('snow_tricks_home.request_password_mail');
                        $message='...';
                        $email->notifyByEmail($message, $user);
                        $this->flashBag->add('info', "Un email vous a été envoyé pour réinitialiser votre mot de passe.");    
                        return new RedirectResponse($this->router->generate('snow_tricks_home_homepage', array('_fragment' => 'info')));
                }

                else {
                        $this->flashBag->add('info', "Vous n'avez pas de compte!");  
                        return new RedirectResponse($this->router->generate('snow_tricks_home_homepage'));
                }      
        }

        return new Response($this->twig->render('SnowTricksHomeBundle:User:requestPassword.html.twig', array(
            'form'  => $form->createView(),
        )));
    }
}