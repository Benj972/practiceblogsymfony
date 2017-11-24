<?php

namespace SnowTricks\HomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="SnowTricks\HomeBundle\Repository\CategoryRepository")
 */
class Category
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
    * @ORM\OneToMany(targetEntity="SnowTricks\HomeBundle\Entity\Trick", mappedBy="category")
    */
    private $tricks;


    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

     /** Get id
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
     * @return Category
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

    public function addTrick(Trick $trick)
    {
        $this->tricks[] = $trick;
        // We link the trick to the category
        $trick->setCategory($this);
    }

    public function removeTrick(Trick $trick)
    {
        $this->tricks->removeElement($trick);
    }
    
    public function getTricks()
    {
        return $this->tricks;
    }



}

