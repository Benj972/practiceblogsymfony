<?php


namespace SnowTricks\HomeBundle\EventListener;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SendRequestPasswordMailListener
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;
   
    /**
     *
     * @var RouterInterface $router
     */
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

        $message = \Swift_Message::newInstance()
            ->setSubject('SnowTricks : Récupération de votre mot de passe')
            ->setFrom('ben@hotmail.fr')
            ->setTo($user->getEmail())
            ->setBody('<a href="'. $this->router->generate('reset_password').'">Cliquez ici pour réinitialiser votre mot de passe</a>');

        $this->mailer->send($message);

    }
}