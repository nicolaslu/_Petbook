<?php

namespace Social\AvatarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AvatarController extends Controller
{
    public function indexAction()
    {
        return $this->render('SocialAvatarBundle::layout.html.twig');
    }

    public function selectAction()
    {
    	$securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
        	//Redirection vers la page de photos
        }	
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
}
