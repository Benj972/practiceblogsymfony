<?php

namespace SnowTricks\HomeBundle\Form\Handler;

/**
 * The EditHandler.
 * Use for manage your form submitions
 */
class EditHandler
{
    private $form;
    private $trick;
    private $em;
    private $originalImages;
    private $originalVideos
    ;
    /**
     * Initialize the handler with the form and the request
     *
     *
     */
    public function __construct($editForm, $trick, $em, $originalImages, $originalVideos)
    {    
        $this->form = $editForm;
        $this->trick = $trick;
        $this->em = $em;
        $this->originalImages = $originalImages;
        $this->originalVideos = $originalVideos;
    }

    /**
    * Process form
    *
    * @return boolean
    */
    public function processEdit()
    {
        if ($this->form->isSubmitted() && $this->form->isValid()) {

            foreach ($this->originalImages as $image) {
                if (false === $this->trick->getImages()->contains($image)) {
                    $image->setTrick(null);
                    $this->em->remove($image);
                }
            }

            foreach ($this->originalVideos as $video) {
                if (false === $this->trick->getVideos()->contains($video)) {
                    $video->setTrick(null);
                    $this->em->remove($video);
                }
            }
            
            $this->em->persist($this->trick);
            $this->em->flush();

            return true;     
        }

        return false;
    }
}