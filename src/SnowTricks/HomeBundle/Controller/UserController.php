<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Form\UserRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SnowTricks\HomeBundle\Form\ChangePasswordType;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\Model\ResetPassword;
use SnowTricks\HomeBundle\Form\Model\RequestPassword;
use SnowTricks\HomeBundle\Form\ResetPasswordType;
use SnowTricks\HomeBundle\Form\RequestPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use SnowTricks\HomeBundle\Event\HomeEvents;

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

    public function resetPasswordAction(Request $request)
    {
            $resetpassword = new ResetPassword;

            $form = $this->createForm(ResetPasswordType::class, $resetpassword);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->getFlashBag()->add('info', "Your password has been resetted. You can login now.");  
            return $this->redirectToRoute('snow_tricks_home_homepage');
            }

            return $this->render('SnowTricksHomeBundle:User:resetPassword.html.twig', array(
            'form' => $form->createView(),
            ));      
    }

    public function requestPasswordAction(Request $request)
    {
        $requestpassword = new RequestPassword;

        $form = $this->createForm(RequestPasswordType::class, $requestpassword);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if{ 
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $event = new RequestPassword();
                // On déclenche l'évènement
                $this->get('event_dispatcher')->dispatch(HomeEvents::NEW_ACCOUNT_CREATED, $event);
                $request->getSession()->getFlashBag()->add('info', "A mail has been sent to your mailbox to reset your password.");  
                return $this->redirectToRoute('snow_tricks_home_homepage');
            }
        }

        return $this->render('SnowTricksHomeBundle:User:requestPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}