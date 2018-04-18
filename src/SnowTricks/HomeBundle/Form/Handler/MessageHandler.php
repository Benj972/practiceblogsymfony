<?php

namespace SnowTricks\HomeBundle\Form\Handler;

/**
 * The MessageHandler.
 * Use for manage your form submitions
 */
class MessageHandler
{
    private $form;
    private $user;
    private $trick;
    private $em;
    private $message;

    /**
     * Initialize the handler with the form and the request
     *
     *
     */
    public function __construct($form, $user, $trick, $em, $message)
    {
        
        $this->form = $form;
        $this->user = $user;
        $this->trick = $trick;
        $this->em = $em;
        $this->message = $message;
    }

    /**
    * Process form
    *
    * @return boolean
    */
    public function process()
    {
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->message->setUser($this->user);
            $this->message->setTrick($this->trick);
            $this->em->persist($this->message);
            $this->em->flush();

            return true;
        }

        return false;
    }
}