<?php

namespace Social\AvatarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AvatarController extends Controller
{
    public function selectAction($filename)
    {
    	$securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
        	$session = $this->getRequest()->getSession();
            $userId = $session->get('pageUserId'); 
            if(isset($userId) && isset($filename))
            {
                $em = $this->getDoctrine()->getEntityManager();
                $repository = $this->getDoctrine()->getRepository('SocialUserBundle:User');
                $user = $repository->find($userId);
                if($user === null)
                {
                  throw $this->createNotFoundException('User[id='.$userId.'] inexistant.');
                }
                $user->setPath($filename);
                $em->persist($user);
                $em->flush();
                
                $session->set('path', $filename);

            }
            return $this->redirect($this->generateUrl('wall_profile'));
        }	
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
}
