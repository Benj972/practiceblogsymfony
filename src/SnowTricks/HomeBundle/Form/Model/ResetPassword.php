<?php

namespace SnowTricks\HomeBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use SnowTricks\HomeBundle\Entity\User;

    class ResetPassword 
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $password;
    /**
     * @param string $password
     */

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /*abstract public function getRoles();

    abstract public function getSalt();

    abstract public function getUsername();

    abstract public function getEmail();

    abstract public function getAvatar();

    abstract public function getToken();

    abstract public function getPlainPassword();*/

}