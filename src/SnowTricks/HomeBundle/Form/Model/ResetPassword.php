<?php

namespace SnowTricks\HomeBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

class ResetPassword 
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $plainPassword;
    /**
     * @param string $password
     */

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

}