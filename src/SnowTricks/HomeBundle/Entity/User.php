<?php

namespace SnowTricks\HomeBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="SnowTricks\HomeBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="On dirait que vous avez déjà un compte!")
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà pris!")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255, unique=true)
     * @Assert\NotBlank(
     *      message = "Le champ ne peut pas être vide")
     * @Assert\Length(
     *      min=2,
     *      max=16,
     *      minMessage = "Votre pseudo doit comporter au moins 2 caractères",
     *      maxMessage = "Votre pseudo ne peut pas dépasser 16 caractères"
     * )
     */
    private $pseudo;

    /**
     * @Assert\NotBlank(
     *      message = "Le champ ne peut pas être vide")
     * @Assert\Email(
     *      message = "Cet email '{{ value }}' n'est pas valide."
     * )
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * The encoded password
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * A non-persisted field that's used to create the encoded password.
     * @var string
     *
     * @Assert\Length(
     *      min=8,
     *      max=16,
     *      minMessage = "Votre mot de passe doit comporter au moins 8 caractères",
     *      maxMessage = "Votre mot de passe ne peut pas dépasser 16 caractères"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
    * @ORM\OneToOne(targetEntity="SnowTricks\HomeBundle\Entity\Image", cascade={"persist"})
    */
    private $avatar;

    /**
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     * @Assert\URL()
     */
    private $token;


    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // give everyone ROLE_USER!
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    	// leaving blank - I don't need/have a password!
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
    
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
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

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }
}