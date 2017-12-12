<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SnowTricks\HomeBundle\Form\LoginType;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SecurityController extends Controller
{
    
    public function loginAction()
    {
    	$authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, [
        	'_username' => $lastUsername,
        ]);

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