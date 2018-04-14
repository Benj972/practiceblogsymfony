<?php

namespace SnowTricks\HomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="SnowTricks\HomeBundle\Repository\TrickRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\Length(
     *      min=2,
     *      max=16,
     *      minMessage = "Le titre doit comporter au moins 2 caractères",
     *      maxMessage = "Le titre ne peut pas dépasser 16 caractères"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(
     *      message = "Le champ ne peut pas être vide")
     */
    private $content;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Image", mappedBy="trick",  cascade={"persist", "remove"}, orphanRemoval=true)
    */
    private $images;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Video", mappedBy="trick", cascade={"persist", "remove"})
    * @Assert\Valid()
    */
    private $videos;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Message", mappedBy="trick", cascade={"persist", "remove"}, orphanRemoval=true)
    * @Assert\Valid()
    */
    private $messages;

    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date", type="datetime")
    * @Assert\DateTime()
    */
    private $date;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Category", inversedBy="tricks", cascade={"persist"})
    */
    private $category;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\User", cascade={"persist"})
    * @Assert\Valid()
    */
    private $user;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->date = new \Datetime();    
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
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
        if($image->getFile() !== null) {
            $this->images[] = $image;
            // We link the image to the figure
            $image->setTrick($this);
            $this->updateDate();
            //return $this->images; 
            //second method form nested
        }
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
        $this->updateDate();
    }
    
    public function getImages()
    {
        return $this->images;
    }

    public function addVideo(Video $video)
    {
        if($video->getUrl() !== null) {
            $this->videos[] = $video;
            // We link the video to the figure
            $video->setTrick($this);
            $this->updateDate();
            //return $this;
            ////second method form nested
        }
    }

    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
        $this->updateDate();
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

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDate(new \DateTime());
    }
}




