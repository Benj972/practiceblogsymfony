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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends Controller
{

    public function registerAction(Request $request, EntityManagerInterface $em)
    {
    	$form = $this->createForm(UserRegistrationType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Bienvenue '.$user->getEmail());
            return $this->redirectToRoute('snow_tricks_home_login', array('_fragment' => 'info'));
        }
        return $this->render('SnowTricksHomeBundle:User:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function changePasswordAction(Request $request, EntityManagerInterface $em)
    {
        $changePasswordModel = new ChangePassword();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $passwordEncoder = $this->container->get('security.password_encoder');
            $oldplainPassword = $changePasswordModel->getOldPassword();
            $plainPassword = $changePasswordModel->getNewPassword();

		      if (!$passwordEncoder->isPasswordValid($user, $oldplainPassword)) {
                    $this->addFlash('info', "Wrong old password!");
                } 

            $user->setPlainPassword($plainPassword);
            $em->persist($user);
            $em->flush();

            $this->addFlash('info', "Le mot de passe est changé avec succès!");    
            return $this->redirectToRoute('snow_tricks_home_homepage', array('_fragment' => 'info'));
        }

        return $this->render('SnowTricksHomeBundle:User:changePassword.html.twig', array(
          'form' => $form->createView(),
        ));      
    }

    public function resetPasswordAction(Request $request, $token, EntityManagerInterface $em)
    {
            $resetpassword = new ResetPassword;    
            $user = $this->getDoctrine()->getManager()->getRepository('SnowTricksHomeBundle:User')->findOneByToken($token);
                    
            if($user !== null) {
                    $form = $this->createForm(ResetPasswordType::class, $resetpassword)->handleRequest($request);
                    $plainPassword = $resetpassword->getPlainPassword();

                    if ($form->isSubmitted() && $form->isValid()) {  	
                        $user->setToken(null);
                        $user->setPlainPassword($plainPassword);
                        $em->flush();
                        $this->addFlash('info', "Votre mot de passe a été réinitialisé. Vous pouvez vous connecter.");  
                        return $this->redirectToRoute('snow_tricks_home_homepage', array('_fragment' => 'info'));
                    }
            }
            return $this->render('SnowTricksHomeBundle:User:resetPassword.html.twig', array(
                    'form' => $form->createView(),
            ));      
    }

    public function requestPasswordAction(Request $request, EntityManagerInterface $em)
    {
        $requestpassword = new RequestPassword;
        $form = $this->createForm(RequestPasswordType::class, $requestpassword)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
                $user = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(User::class)
                    ->findOneByEmail($requestpassword->getEmail());

                if($user !== null) {
                        $confirmationtoken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                        $user->setToken($confirmationtoken);
                        $em->flush();
                        $email = $this->container->get('snow_tricks_home.request_password_mail');
                        $message='...';
                        $email->notifyByEmail($message, $user);
                        $this->addFlash('info', "Un email vous a été envoyé pour réinitialiser votre mot de passe.");  
                        return $this->redirectToRoute('snow_tricks_home_homepage', array('_fragment' => 'info'));
                }

                else {
                        $this->addFlash('info', "Vous n'avez pas de compte!");  
                        return $this->redirectToRoute('snow_tricks_home_homepage');
                }      
        }

        return $this->render('SnowTricksHomeBundle:User:requestPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
