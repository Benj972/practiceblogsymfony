<?php

namespace SnowTricks\HomeBundle\Form\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RequestPassword
{
    /**
     * @var UserInterface
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "Cet email '{{ value }}' n'est pas valide."
     * )
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
