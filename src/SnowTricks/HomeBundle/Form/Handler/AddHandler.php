<?php

namespace SnowTricks\HomeBundle\Form\Handler;

/**
 * The AddHandler.
 * Use for manage your form submitions
 */
class AddHandler
{
    private $form;
    private $user;
    private $trick;
    private $em;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form $form
     * @param Request $request
     *
     */
    public function __construct($form, $user, $trick, $em)
    {
        $this->form = $form;
        $this->user = $user;
        $this->trick = $trick;
        $this->em = $em;
    }

    /**
    * Process form
    *
    * @return boolean
    */
    public function processAdd()
    {
       if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->trick->setUser($this->user);
            $this->em->persist($this->trick);
            $this->em->flush();

            return true;
       }

       return false;
    }
}