<?php

namespace SnowTricks\HomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Image", mappedBy="trick")
    */
    private $images;

    /**
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Video", mappedBy="trick")
    */
    private $videos;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Category", cascade={"persist"})
    */
    private $category;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Member", cascade={"persist"})
    */
    private $member;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
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

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setMember(Member $member)
    {
        $this->member = $member;
    }

    public function getMember()
    {
        return $this->member;
    }

    
    public function addImage(Image $image)
    {
        $this->images[] = $image;
        // We link the image to the figure
        $image->setTrick($this);
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
    }

    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }
    
    public function getVideos()
    {
        return $this->videos;
    }
}

