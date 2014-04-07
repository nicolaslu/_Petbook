<?php

namespace Social\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Social\UserBundle\Entity\User as User;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$securityContext = $this->container->get('security.context');
		if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY') ){
			$contact_list = $securityContext->getToken()->getUser()->getFriends();
		
			return $this->render('SocialContactBundle::layout.html.twig', array('contact_list'=>$contact_list));
		}else{
			return $this->redirect($this->generateUrl('social_login')); 
		}        
    }
	
	public function addAction(){
		$securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
			$message = "";
            $session = $this->getRequest()->getSession();
			$repository = $this->getDoctrine()->getManager()->getRepository('SocialUserBundle:User');
			
			if(isset($_POST['add_contact_field'])){
			
				$usernameToBeAdded = $_POST['add_contact_field'];
				
				$friendToBeAdded = $repository->findOneBy(array('usernameCanonical'=>strtolower($usernameToBeAdded)));
				if($friendToBeAdded == null){
					$message = "User not found";
				}else{
					$current_user = $repository->findOneBy(array('username'=>$session->get('username')));
					if(!$current_user->isFriendWith($friendToBeAdded)){
						$current_user->addFriend($friendToBeAdded);
						$em = $this->getDoctrine()->getManager();
						$em->persist($current_user);
						$em->flush();
						$message = $_POST['add_contact_field']." has been added to your contact list.";
					}else{
						$message = $_POST['add_contact_field']." is already in your contact list.";
					}
				}
			}
			return $this->redirect($this->generateUrl('social_contact_list', array('message' => $message)));

        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
	}

	public function deleteAction(){
		if(isset($_POST['delete_contact_field'])){
			
			$message = "";
            $session = $this->getRequest()->getSession();
			$repository = $this->getDoctrine()->getManager()->getRepository('SocialUserBundle:User');
			
			$usernameToBeRemoved = $_POST['delete_contact_field'];
			
			$friendToBeRemoved = $repository->findOneBy(array('id'=>$usernameToBeRemoved));
			if($friendToBeRemoved == null){
				$message = "User not found";
			}else{
				$current_user = $repository->findOneBy(array('username'=>$session->get('username')));
				if($current_user->isFriendWith($friendToBeRemoved)){
					$current_user->removeMyFriend($friendToBeRemoved);
					$em = $this->getDoctrine()->getManager();
					$em->persist($current_user);
					$em->flush();
					$message = $friendToBeRemoved->getUsername()." has been removed from your contact list.";
				}else{
					$message = $friendToBeRemoved->getUsername()." is not in your contact list.";
				}
			}
		}
		return $this->redirect($this->generateUrl('social_contact_list', array('message' => $message), true));
	}
}
