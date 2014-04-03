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
        		$term = $_POST['searchType'].'%';
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

    public function redirectAction()
    {
    	$securityContext = $this->container->get('security.context');
    	if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
        	if(isset($_POST['userId']) && isset($_POST['path']) && isset($_POST['username']))
        	{
        		$id = $_POST['userId'];
        		$path = $_POST['path'];
        		$username = $_POST['username'];
        		$session = $this->getRequest()->getSession();
		        $session->set('pageUserId', $id);
		        $session->set('username', $username);
		        $session->set('path', $path);
			}
			return $this->redirect($this->generateUrl('wall_profile'));
        }
        else
        {
			return $this->redirect($this->generateUrl('social_login')); 
        }
    }
}
