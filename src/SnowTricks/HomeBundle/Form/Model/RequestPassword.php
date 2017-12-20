<?php


namespace SnowTricks\HomeBundle\Form\Model;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\EventDispatcher\Event;

class RequestPassword extends Event
{
    /**
     *
     * @var UserInterface
     */
    private $user;
    /**
     * @Assert\NotBlank
     */

    private $identifier; 

   
    private $token;


    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}