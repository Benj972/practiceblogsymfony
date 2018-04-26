<?php

namespace SnowTricks\HomeBundle\Form\Model;

use SnowTricks\HomeBundle\Entity\User;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Mauvaise valeur pour votre mot de passe actuel"
     * )
     */
    protected $oldPassword;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Le champ ne peut pas être vide")
     * @Assert\Length(
     *      min=8,
     *      max=16,
     *      minMessage = "Votre mot de passe doit comporter au moins 8 caractères",
     *      maxMessage = "Votre mot de passe ne peut pas dépasser 16 caractères"
     * )
     */
    protected $newPassword;

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }


    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
        return $this;
    }
}
