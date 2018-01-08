<?php


namespace SnowTricks\HomeBundle\Form\Model;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RequestPassword 
{
    /**
     *
     * @var UserInterface
     */
    private $email;

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
}