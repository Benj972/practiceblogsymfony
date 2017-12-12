<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Form\UserRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    public function registerAction(Request $request)
    {
    	$form = $this->createForm(UserRegistrationType::class);
    	$form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Welcome '.$user->getEmail());
            return $this->redirectToRoute('snow_tricks_home_homepage');
        }
        return $this->render('SnowTricksHomeBundle:User:register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}