<?php


namespace SnowTricks\HomeBundle\SendRequestMail;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SendRequestPasswordMail
{
    
    protected $mailer;
  
    protected $router;
   

    public function __construct(\Swift_Mailer $mailer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }
    /**
     * @param UserDataEvent $event
     */
    public function notifyByEmail($message, UserInterface $user)
    {
        // Sendmail
        $transport = new Swift_SendmailTransport('/usr/sbin/exim -bs');
        
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        $message = \Swift_Message::newInstance()
            ->setSubject('SnowTricks : Récupération de votre mot de passe')
            ->setFrom('ben.gallot972@gmail.com')
            ->setTo('ben.gallot972@gmail.com'/*$user->getEmail()*/)
            ->setBody('<a href="'. $this->router->generate('reset_password'), ['token' => $user->getToken()].'">Cliquez ici pour réinitialiser votre mot de passe</a>,"text/html"');

        $this->mailer->send($message);
    }
}