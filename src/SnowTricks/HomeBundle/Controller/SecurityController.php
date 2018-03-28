<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SnowTricks\HomeBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, [
        	'_username' => $lastUsername,
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $request->getSession()->getFlashBag()->add('info', 'Vous êtes bien enregistré');
            return $this->redirectToRoute('snow_tricks_home_homepage', array('_fragment' => 'info'));
        }

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