<?php

namespace SnowTricks\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Video
 *
 * @ORM\Table(name="trick_video")
 * @ORM\Entity(repositoryClass="SnowTricks\HomeBundle\Repository\VideoRepository")
 */
class Video
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
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\Url(
     *      message = "L'url '{{ value }}' n'est pas valide",
     * )
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Le champ ne peut pas être vide")
     */
    private $alt;

    /**
    * @ORM\ManyToOne(targetEntity="SnowTricks\HomeBundle\Entity\Trick", inversedBy="videos", cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)
    * @Assert\Valid()
    */
    private $trick;

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
     * Set url
     *
     * @param string $url
     *
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Video
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
    * @param Trick $trick
    */
    public function setTrick(?Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    /**
    * @return Trick
    */
    public function getTrick()
    {
        return $this->trick;
    }
}
