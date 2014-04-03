<?php

namespace Social\WallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Social\WallBundle\Entity\Publication;
use Symfony\Component\HttpFoundation\Session\Session;

class WallController extends Controller
{
    public function homeAction()
    {
        $securityContext = $this->container->get('security.context');
        $session = $this->getRequest()->getSession();
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $userId = $session->get('pageUserId');
            if(isset($userId))
            {
                $em = $this->getDoctrine()->getEntityManager();
                $repository = $this->getDoctrine()->getRepository('SocialUserBundle:User');
                $user = $repository->find($userId);

                if($user === null)
                {
                  throw $this->createNotFoundException('User[id='.$userId.'] inexistant.');
                }
                // On récupère la liste des publication du mur de l'utilisateur
                $res = $em->getRepository('SocialWallBundle:Publication')
                             ->findBy(array('idUser' => $userId ), array('date' => 'desc')); 
            }
            return $this->render('SocialWallBundle:Publication:publication.html.twig', array('publication' => $res));
        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }

    //Index de la page
    public function indexAction()
    {
        //Création de la variable session permettant de gérer l'userId de la page affichée
        $securityContext = $this->container->get('security.context');
        $userId = $securityContext->getToken()->getUser()->getId();
        $username = $securityContext->getToken()->getUser()->getUsername();
        $session = $this->getRequest()->getSession();
        $session->set('pageUserId', $userId);
        $session->set('username', $username);
        $session->set('path', $securityContext->getToken()->getUser()->getPath());
        //Redirection vers la page de profil
        return $this->redirect($this->generateUrl('wall_profile'));
    }


    //Gestion de la publication du message
    public function postAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
        	$em = $this->getDoctrine()->getEntityManager();
            $userId =$this->container->get('security.context')->getToken()->getUser()->getId();
        	if(isset($_POST['textaenvoyer']) && $_POST['textaenvoyer']!="Write your message" &&isset($userId))
    		{
                $session = $this->getRequest()->getSession();
                $repository = $this->getDoctrine()->getRepository('SocialUserBundle:User');
    			$msg = new Publication();
    			$msg->setTexte($_POST['textaenvoyer']);
                $msg->setIdUser($session->get('pageUserId'));
                $msg->setIdWriter($repository->find($this->container->get('security.context')->getToken()->getUser()->getId())->getUsername());

    			// Étape 1 : On « persiste » l'entité
        		$em->persist($msg);

    		    // Étape 2 : On « flush » tout ce qui a été persisté avant
    		    $em->flush();
    		}
        	return $this->redirect($this->generateUrl('wall_profile'));
        }	
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }

    //Gestion de l'action Supression ou Modification du message
    public function actionAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            if(isset($_POST['submit']))
            {
                if(isset($_POST['id']) && $_POST['submit']==="delete")
                {
                    $id = $_POST['id'];
                    $em = $this->getDoctrine()->getEntityManager();
                    $repository = $this->getDoctrine()->getRepository('SocialWallBundle:Publication');
                    $p = $repository->find($id);
                    if (!$p) {
                        throw $this->createNotFoundException('No product found for id '.$id);
                    }
                    else
                    {
                        //Si la publication est trouvée, on la supprime de la base
                        $em->remove($p);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('wall_profile'));
                }
                elseif(isset($_POST['id']) && $_POST['submit']==="edit")
                {
                    $id = $_POST['id'];
                    $em = $this->getDoctrine()->getEntityManager();
                    $repository = $this->getDoctrine()->getRepository('SocialWallBundle:Publication');
                    $p = $repository->find($id);
                    if (!$p) {
                        throw $this->createNotFoundException('No product found for id '.$id);
                    }
                    else
                    {
                        return $this->render('SocialWallBundle:Modifier:modify.html.twig', array('msg' => $p));
                    }
                }
            }     
        }   
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }

    //Modifier Message
    public function modifyAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            if(isset($_POST['submit']))
            {
                if(isset($_POST['id']) && isset($_POST['textaenvoyer']) && $_POST['submit']==="Valider")
                {
                    $id = $_POST['id'];
                    $em = $this->getDoctrine()->getEntityManager();
                    $repository = $this->getDoctrine()->getRepository('SocialWallBundle:Publication');
                    $p = $repository->find($id);
                    if (!$p) {
                        throw $this->createNotFoundException('No product found for id '.$id);
                    }
                    else
                    {
                        //Modification d'un post
                        $p->setUpdated();
                        $p->setTexte($_POST['textaenvoyer']);
                        $em->flush();
                    }
                }
            }     
            return $this->redirect($this->generateUrl('wall_profile'));
        }   
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
}
