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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use SnowTricks\HomeBundle\EventListener\SendRequestPasswordMailListener;

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
        $oldPassword = $changePasswordModel->getOldPassword();
        $newPassword = $changePasswordModel->getNewPassword();
        
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $user = $this->getUser();
        $encoder = $this->container->get('security.password_encoder');
        $oldplainPassword = $encoder->encodePassword($changePasswordModel, $oldPassword);

          if ($user->getPassword() != $oldplainPassword){
            $this->addFlash('info', "Wrong old password!");
          } else {
            $newplainPassword = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($newplainPassword);
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

    public function resetPasswordAction(Request $request)
    {
            $resetpassword = new ResetPassword;
            $newpassword = $resetpassword->getPassword();

            $form = $this->createForm(ResetPasswordType::class, $resetpassword);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $passwordEncoder = $this->container->get('security.password_encoder');
                $password = $passwordEncoder->encodePassword($resetpassword, $newpassword);
                $user->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
           
                $securityToken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($securityToken);

                $this->addFlash('info', "Your password has been resetted. You can login now.");  
                return $this->redirectToRoute('snow_tricks_home_homepage');
            }

            return $this->render('SnowTricksHomeBundle:User:resetPassword.html.twig', array(
            'form' => $form->createView(),
            ));      
    }

    public function requestPasswordAction(Request $request)
    {
        $requestpassword = new RequestPassword;
        /*$identifier = $requestpassword->getIdentifier();*/

        $form = $this->createForm(RequestPasswordType::class, $requestpassword);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
        $user = $this->getDoctrine()
          ->getManager()
          ->getRepository('SnowTricksHomeBundle:User')
          ->findOneByEmail($form->getData()["email"]);

        if($user !== null) {
                $user->setToken(bcrypt(time()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $notifyByEmail = $this->container->get('snow_tricks_home.request_password_mail');
                /*$this->mail->notifyByEmail($user->getEmail(),$user->getToken());*/
        }
                $this->addFlash('info', "A mail has been sent to your mailbox to reset your password.");  
                return $this->redirectToRoute('snow_tricks_home_homepage');
        }

        return $this->render('SnowTricksHomeBundle:User:requestPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
