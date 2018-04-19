<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SnowTricks\HomeBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {   
        return $this->render(
            'SnowTricksHomeBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'form' => $this->createForm(LoginType::class, ['_username' => $authenticationUtils->getLastUsername()])->createView(),
                'error'=> $authenticationUtils->getLastAuthenticationError(),
            )
        );
    }

    public function logoutAction()
    {     
        throw new \Exception('this should not be reached!');
    }

}