<?php

namespace SnowTricks\HomeBundle\Security;


use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
	private $formFactory;
	private $em;
	private $router;
    private $passwordEncoder;

	public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $em, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder)
    {
    	$this->formFactory = $formFactory;
    	$this->em = $em;
    	$this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/login' && $request->isMethod('POST');
    }
    
	public function getCredentials(Request $request)
    {
    	$isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
    	if (!$isLoginSubmit) {
            // skip authentication
            return;
        }

        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );
        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
    	$username = $credentials['_username'];

    	return $this->em->getRepository('SnowTricksHomeBundle:User')
            ->findOneBy(['email' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
    	$password = $credentials['_password'];

    	if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }
        
        return false;
    }

    protected function getLoginUrl()
    {
    	return $this->router->generate('snow_tricks_home_login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->getFlashBag()->add('info', 'Vous êtes bien enregistré');
            return null;
        }

    }

    protected function getDefaultSuccessRedirectUrl()
    {
    }
}