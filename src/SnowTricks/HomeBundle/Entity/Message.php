<?php

namespace SnowTricks\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="SnowTricks\HomeBundle\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(
     *      min=2,
     *      max=10,
     *      minMessage = "Le titre doit comporter au moins 2 caractères",
     *      maxMessage = "Le titre ne peut pas dépasser 10 caractères"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Trick", inversedBy="messages")
    * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
    * @Assert\Valid()
    */
    private $trick;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    * @Assert\Valid()
    */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Message
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    public function getTrick()
    {
        return $this->trick;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateDate()
    {
      $this->date = new \DateTime();
    }
}

