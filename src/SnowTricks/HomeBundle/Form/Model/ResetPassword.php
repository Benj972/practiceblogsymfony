<?php

namespace SnowTricks\HomeBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

class ResetPassword 
{
    /**
     * @Assert\NotBlank(
     *      message = "Le champ ne peut pas être vide")
     * @Assert\Length(
     *      min=8,
     *      max=16,
     *      minMessage = "Votre mot de passe doit comporter au moins 8 caractères",
     *      maxMessage = "Votre mot de passe ne peut pas dépasser 16 caractères"
     * )
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