<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Form\ChangePasswordType;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordController extends Controller
{
	public function changePasswordAction(Request $request)
	{
	  $changePasswordModel = new ChangePassword();
      $form = $this->createForm(new ChangePasswordType(), $changePasswordModel);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          // perform some action,
          // such as encoding with MessageDigestPasswordEncoder and persist
          return $this->redirect($this->generateUrl('change_passwd_success'));
      }

      return $this->render('SnowTricksHomeBundle:User:changePassword.html.twig', array(
          'form' => $form->createView(),
      ));      
	}
}