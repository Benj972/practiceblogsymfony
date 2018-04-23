<?php

namespace SnowTricks\HomeBundle\Controller;

use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\Handler\RegisterHandler;
use SnowTricks\HomeBundle\Form\Handler\ChangePasswordHandler;
use SnowTricks\HomeBundle\Form\Handler\ResetPasswordHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    public function registerAction(RegisterHandler $handler)
    {
        return $handler->handle(); 
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function changePasswordAction(ChangePasswordHandler $handler)
    {
        return $handler->handle();          
    }

    public function resetPasswordAction($token, ResetPasswordHandler $handler)
    {        
        return $handler->handle($token);           
    }

    public function requestPasswordAction(ResetPasswordHandler $handler)
    {
        return $handler->requesthandle();
    }
}
