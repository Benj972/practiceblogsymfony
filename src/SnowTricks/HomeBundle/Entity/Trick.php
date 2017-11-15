<?php

namespace SnowTricks\HomeBundle\Entity;

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
    * @ORM\OneToOne(targetEntity="SnowTricks\HomeBundle\Entity\Image", cascade={"persist", "remove"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $image;

    /**
    * @ORM\OneToOne(targetEntity="SnowTricks\HomeBundle\Entity\Video", cascade={"persist", "remove"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $video;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Category", cascade={"persist"})
    */
    private $category;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Member", cascade={"persist"})
    */
    private $member;


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


      public function setImage(Image $image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

      public function setVideo(Video $video)
    {
        $this->video = $video;
    }

    public function getVideo()
    {
        return $this->video;
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
}

