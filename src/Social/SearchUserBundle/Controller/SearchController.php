<?php

namespace Social\SearchUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
    	$securityContext = $this->container->get('security.context');
    	if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
        	if(isset($_POST['searchType']))
        	{
        		$term = '%'.$_POST['searchType'].'%';
        		$em = $this->getDoctrine()->getEntityManager();
        		$result = $em->getRepository('SocialUserBundle:User')->createQueryBuilder('o')
					   ->where('o.username LIKE :term')
					   ->setParameter('term', $term)
					   ->getQuery()
					   ->getResult();
				return $this->render('SocialSearchUserBundle::layout.html.twig', array('users'=> $result));
			}
			else
			{
				return $this->redirect($this->generateUrl('wall_profile'));
			}
        }
        else
        {
			return $this->redirect($this->generateUrl('social_login')); 
        }
    }

    public function redirectAction($id, $path, $username)
    {
    	$securityContext = $this->container->get('security.context');
    	if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
    		$session = $this->getRequest()->getSession();
	        $session->set('pageUserId', $id);
	        $session->set('username', $username);
	        $session->set('path', $path);
			return $this->redirect($this->generateUrl('wall_profile'));
        }
        else
        {
			return $this->redirect($this->generateUrl('social_login')); 
        }
    }
}
