<?php

namespace SnowTricks\HomeBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class GenusAdminController extends Controller
{
	
	public function indexAction()
    {
    	$this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('GET OUT!');
        }

    }

    public function newAction(Request $request)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                sprintf('Genus created by you: %s!', $this->getUser()->getEmail())
            );
        }
    }
}