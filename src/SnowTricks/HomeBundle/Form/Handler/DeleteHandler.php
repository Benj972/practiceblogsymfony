<?php

namespace SnowTricks\HomeBundle\Form\Handler;

/**
 * The DeleteHandler.
 * Use for manage your form submitions
 */
class DeleteHandler
{
   
    private $form;
    private $trick;
    private $em;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form $form
     * @param Request $request
     *
     */
    public function __construct($form, $trick, $em)
    {
        $this->form = $form;
        $this->trick = $trick;
        $this->em = $em;
    }

    /**
    * Process form
    *
    * @return boolean
    */
    public function processDelete()
    {
       if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->em->remove($this->trick);
            $this->em->flush();

            return true;
       }

       return false;
    }
}