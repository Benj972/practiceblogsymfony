<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace SnowTricks\HomeBundle\DiscussionArea;

class Discussion
{
  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  
 public function spacemessageAction($page)
    {

      if ($page < 1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

      $nbPerPage = 10;

      $listMessages = $this->getDoctrine()
       ->getManager()
       ->getRepository('SnowTricksHomeBundle:Message')
       ->getMessages($page, $nbPerPage)
       ;

       $nbPages = ceil(count($listMessages) / $nbPerPage);
        
      if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
      }

       return $this->render('SnowTricksHomeBundle:Tricks:view.html.twig', array(
          'listMessages' => $listMessages,
          'nbPages' => $nbpages,
          'page' => $page,
            ));   

    }
}