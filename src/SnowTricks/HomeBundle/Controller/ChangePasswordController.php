<?php
/*
namespace SnowTricks\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SnowTricks\HomeBundle\Form\ChangePasswordType;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use SnowTricks\HomeBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class ChangePasswordController extends Controller
{

	public function changePasswordAction(Request $request)
	{
        $changePasswordModel = new ChangePassword();
        $oldPassword = $changePasswordModel->getOldPassword();
        $newPassword = $changePasswordModel->getNewPassword();
        $user = $this->getUser();
        $encoder = $this->container->get('security.password_encoder');
        $oldplainPassword = $encoder->encodePassword($user, $oldPassword);
      
      $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        
          if ($user->getPassword() != $oldplainPassword){
            $request->getSession()->getFlashBag()->add('info', "Wrong old password!");
          } else {
            $newplainPassword = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($newplainPassword);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "Password change successfully!");
          }
          // perform some action,
          // such as encoding with MessageDigestPasswordEncoder and persist
          return $this->redirectToRoute('snow_tricks_home_homepage');
      }

      return $this->render('SnowTricksHomeBundle:User:changePassword.html.twig', array(
          'form' => $form->createView(),
      ));      
	}
}