<?php

namespace Social\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function authAction()
    {
    	$securityContext = $this->container->get('security.context');
    	if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
        	return $this->redirect($this->generateUrl('wall_profile'));
        }
        else
        {
        	return $this->render('SocialUserBundle:Login:login.html.twig');
        }
    }

    public function registerAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirect($this->generateUrl('wall_profile'));
        }
        else
        {
            return $this->render('SocialUserBundle:Register:register.html.twig');
        }
    }
}

