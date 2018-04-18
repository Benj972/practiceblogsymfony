<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SnowTricks\HomeBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SecurityController extends Controller
{
    
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, ['_username' => $lastUsername])->handleRequest($request);
        
        return $this->render(
            'SnowTricksHomeBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'form' => $form->createView(),
                'error'=> $error,
            )
        );
    }

    public function logoutAction()
    {     
        throw new \Exception('this should not be reached!');
    }

}