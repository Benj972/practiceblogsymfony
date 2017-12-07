<?php

namespace SnowTricks\HomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="SnowTricks\HomeBundle\Repository\TrickRepository")
 */
class Trick
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Image", mappedBy="trick", cascade={"persist"})
    * @Assert\Valid()
    */
    private $images;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Video", mappedBy="trick", cascade={"persist"})
    * @Assert\Valid()
    */
    private $videos;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Message", mappedBy="trick")
    * @Assert\Valid()
    */
    private $messages;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Category", inversedBy="tricks", cascade={"persist"})
    * @Assert\Valid()
    */
    private $category;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\UserBundle\Entity\User", cascade={"persist"})
    * @Assert\Valid()
    */
    private $user;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Trick
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Trick
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
    * @param Category $category
    */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
    * @return Category
    */
    public function getCategory()
    {
        return $this->category;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    
    public function addImage(Image $image)
    {
        $this->images[] = $image;
        // We link the image to the figure
        $image->setTrick($this);
        //return $this->images; 
        //second method form nested
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }
    
    public function getImages()
    {
        return $this->images;
    }

     public function addVideo(Video $video)
    {
        $this->videos[] = $video;
        // We link the video to the figure
        $video->setTrick($this);
        //return $this;
        ////second method form nested
    }

    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }
    
    public function getVideos()
    {
        return $this->videos;
    }

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
        // We link the message to the figure
        $message->setTrick($this);
    }

    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);
    }
    
    public function getMessages()
    {
        return $this->messages;
    }
}

