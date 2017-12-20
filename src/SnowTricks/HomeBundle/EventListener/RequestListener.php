<?php

namespace SnowTricks\HomeBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use SnowTricks\HomeBundle\Form\Model\RequestPassword;


class UserSubscriber 
{
    private $passwordEncoder;
    private $tokenStorage;
    private $session;


    public function __construct(SendRequestPasswordMailListener $sendRequest, $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->sendrequest = $sendRequest;
    }

    public function processMail(RequestPassword $event)
    {
        if (in_array($event->getUser()->getEmail(), $this->tokenStorage->setToken($securityToken))) {
        // On envoie un e-mail à l'administrateur
        $this->sendRequest->notifyByEmail($event->getEmail(), $event->getUser(), $event->getToken());
        }
    }

}