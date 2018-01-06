<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\UserRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SnowTricks\HomeBundle\Form\ChangePasswordType;
use SnowTricks\HomeBundle\Form\ResetPasswordType;
use SnowTricks\HomeBundle\Form\RequestPasswordType;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use SnowTricks\HomeBundle\Form\Model\ResetPassword;
use SnowTricks\HomeBundle\Form\Model\RequestPassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


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
            $this->addFlash('info', 'Welcome '.$user->getEmail());
            return $this->redirectToRoute('snow_tricks_home_homepage');
        }
        return $this->render('SnowTricksHomeBundle:User:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function changePasswordAction(Request $request)
    {
        $changePasswordModel = new ChangePassword();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        $passwordEncoder = $this->container->get('security.password_encoder');
        $oldplainPassword = $changePasswordModel->getOldPassword();
        $newplainPassword = $changePasswordModel->getNewPassword();
		if (!$passwordEncoder->isPasswordValid($user, $oldplainPassword)) {
            $this->addFlash('info', "Wrong old password!");
          } else {
            $encoded = $passwordEncoder->encodePassword($user, $newplainPassword);
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', "Password change successfully!");
        }

          return $this->redirectToRoute('snow_tricks_home_homepage');
      }

      return $this->render('SnowTricksHomeBundle:User:changePassword.html.twig', array(
          'form' => $form->createView(),
      ));      
    }

    public function resetPasswordAction(Request $request, $token)
    {
            $resetpassword = new ResetPassword;
            $newpassword = $resetpassword->getPlainPassword();
            $user = $this->getDoctrine()->getManager()->getRepository('SnowTricksHomeBundle:User')->findOneByToken($token);
                    
            if($user !== null) {
            $form = $this->createForm(ResetPasswordType::class, $resetpassword);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setToken(null);
                $passwordEncoder = $this->container->get('security.password_encoder');
                $encoded = $passwordEncoder->encodePassword($user, $newpassword);
                $user->setPassword($encoded);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash('info', "Your password has been resetted. You can login now.");  
                return $this->redirectToRoute('snow_tricks_home_homepage');
            }
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
        $user = $this->getDoctrine()
          ->getManager()
          ->getRepository('SnowTricksHomeBundle:User')
          ->findOneByEmail($requestpassword->getEmail());

        if($user !== null) {
                /*$confirmationtoken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());*/
                $user->setToken(2/*$confirmationtoken*/);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $notifyByEmail = $this->container->get('snow_tricks_home.request_password_mail');
        }
                $this->addFlash('info', "A mail has been sent to your mailbox to reset your password.");  
                return $this->redirectToRoute('snow_tricks_home_homepage');
        }

        return $this->render('SnowTricksHomeBundle:User:requestPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
