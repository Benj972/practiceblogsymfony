<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\Handler\RegisterHandler;
use SnowTricks\HomeBundle\Form\Handler\ChangePasswordHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SnowTricks\HomeBundle\Form\ResetPasswordType;
use SnowTricks\HomeBundle\Form\RequestPasswordType;
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

    public function registerAction(RegisterHandler $handler)
    {
        return $handler->handle(); 
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function changePasswordAction(ChangePasswordHandler $handler)
    {
        $user = $this->getUser();
        return $handler->handle($user);          
    }

    public function resetPasswordAction(Request $request, $token, EntityManagerInterface $em)
    {
            $resetpassword = new ResetPassword;    
            $user = $em->getRepository('SnowTricksHomeBundle:User')->findOneByToken($token);
                    
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
                $user = $em->getRepository(User::class)->findOneByEmail($requestpassword->getEmail());

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
