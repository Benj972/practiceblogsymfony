<?php


namespace SnowTricks\HomeBundle\SendRequestMail;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SnowTricks\HomeBundle\Entity\User;
use Symfony\Component\Templating\EngineInterface;

class SendRequestPasswordMail
{
    
    protected $mailer;
  
    protected $router;
       

    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
             
    }
    /**
     * @param UserDataEvent $event
     */
    public function notifyByEmail($message, UserInterface $user)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('SnowTricks : Récupération de votre mot de passe')
            ->setFrom('ben.gallot972@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->templating->render('SnowTricksHomeBundle:User:request_password_mail.html.twig',
                [
                'username' => $user->getUsername(),
                'request_link' => $this->router->generate('snow_tricks_home_reset_password',
                    ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL)
            ])
        );
            /*->setBody('<a href="'. $this->router->generate('snow_tricks_home_reset_password', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL).'">Cliquez ici pour réinitialiser votre mot de passe</a>,"text/html"');*/

        $this->mailer->send($message);
    }
}