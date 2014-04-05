<?php

namespace Social\PhotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Social\PhotoBundle\Entity\Photo;
use Social\PhotoBundle\Entity\Album;
use Doctrine\ORM\EntityRepository;


class PhotoController extends Controller
{
    public function indexAction()
    {
    	$session = $this->getRequest()->getSession();
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            
            $userId = $session->get('pageUserId');
            if(isset($userId))
            {
                $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('SocialPhotoBundle:Album');
				
				$listeAlbums = $repository->findBy(array('auteur' => $userId),
                                     array('date' => 'desc')
								);
            }
			return $this->render('SocialPhotoBundle::layout.html.twig', array('listeAlbums' => $listeAlbums));

        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
	
	public function allAction($name)
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $session = $this->getRequest()->getSession();
            $userId = $session->get('pageUserId');
            if(isset($userId))
            {
                $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('SocialPhotoBundle:Photo');
				
				$listePhotos = $repository->findBy(array('idAlbum' => $name),
                                     array('date' => 'desc')
								);            
			}
			return $this->render('SocialPhotoBundle::layout_album.html.twig', array('listePhotos' => $listePhotos));

        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
	
	public function supAction($id, $type)
    {
        $securityContext = $this->container->get('security.context');
		$em = $this->getDoctrine()->getManager();
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {  
            $userId = $securityContext->getToken()->getUser()->getId();
            if(isset($userId))
            {
                $repository = $this->getDoctrine()->getRepository('SocialPhotoBundle:'.$type);
				$p = $repository->find($id);
				
				if (!$p) {
					throw $this->createNotFoundException('No product found for id '.$id);
				}
				else
				{
					$em->remove($p);
					$em->flush();
				}       
			}
            return $this->redirect($this->generateUrl('add_message', array('name'=>'The '.$type.' has been deleted')));  

        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }

	public function oneAction($id)
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            
            $userId = $securityContext->getToken()->getUser()->getId();
            if(isset($userId))
            {
                
					$photo = $this->getDoctrine()
					->getManager()
					->find('SocialPhotoBundle:Photo', $id);
			}
			return $this->render('SocialPhotoBundle::layout_photo.html.twig', array('photo' => $photo));

        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
	
	public function messageAction($name)
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
			return $this->render('SocialPhotoBundle::layout_message.html.twig', array('name' => $name));
        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
	

    public function addAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            
            $userId = $securityContext->getToken()->getUser()->getId();
            if(isset($userId))
            {
				$photo = new Photo;
                $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('SocialPhotoBundle:Album');
				
				$listealbums = $repository->findAll();
				if(!$listealbums)
				{
					return $this->redirect($this->generateUrl('create_album'));
				}

				
				// J'ai raccourci cette partie, car c'est plus rapide à écrire !
				$form = $this->createFormBuilder($photo)
							 ->add('name',        'text')
							 ->add('description',       'text')
							 ->add('image',     'file')

							->add('idAlbum','entity', array(
								'class'=>'SocialPhotoBundle:Album',
								'query_builder' => function(EntityRepository $er) {
								        $securityContext = $this->container->get('security.context');

								    $userId = $securityContext->getToken()->getUser()->getId();

									return $er->createQueryBuilder('u')
									->where('u.auteur=:pp')
									->setParameter('pp', $userId);
								},
								'property' => 'name',
								'multiple' => false,
							))		
							 ->getForm();

				// On récupère la requête
				$request = $this->get('request');

				// On vérifie qu'elle est de type POST
				if ($request->getMethod() == 'POST') {
				  // On fait le lien Requête <-> Formulaire
				  // À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
				  $form->bind($request);
				  

				  // On vérifie que les valeurs entrées sont correctes
				  // (Nous verrons la validation des objets en détail dans le prochain chapitre)
				  if ($form->isValid()) {
					// On l'enregistre notre objet $article dans la base de données
					
					$photo->upload();
					$photo->setAuteur($userId);
					$photo->setDate (new \Datetime);
					$em = $this->getDoctrine()->getManager();
					$em->persist($photo);
					$em->flush();

					// On redirige vers la page de visualisation de l'article nouvellement créé
				  }
				  return $this->redirect($this->generateUrl('add_message', array('name'=>'The picture has been added')));  

				}

				// À ce stade :
				// - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
				// - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

				 return $this->render('SocialPhotoBundle::layout_add.html.twig', array(
				  'form' => $form->createView(),
				));
            }
        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }
	
	public function createalbumAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            
            $userId = $securityContext->getToken()->getUser()->getId();
            if(isset($userId))
            {
				$album = new Album;

				// J'ai raccourci cette partie, car c'est plus rapide à écrire !
				$form = $this->createFormBuilder($album)
							 ->add('name',        'text')
							 ->getForm();

				// On récupère la requête
				$request = $this->get('request');

				// On vérifie qu'elle est de type POST
				if ($request->getMethod() == 'POST') {
				  // On fait le lien Requête <-> Formulaire
				  // À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
				  $form->bind($request);
				  

				  // On vérifie que les valeurs entrées sont correctes
				  // (Nous verrons la validation des objets en détail dans le prochain chapitre)
				  if ($form->isValid()) {
					// On l'enregistre notre objet $article dans la base de données
					
					$album->setDate (new \Datetime);
					$album->setAuteur($userId);
					$em = $this->getDoctrine()->getManager();
					$em->persist($album);
					$em->flush();

					// On redirige vers la page de visualisation de l'article nouvellement créé
				  }
				  return $this->redirect($this->generateUrl('add_message', array('name'=>'The album has been created')));  

				}

				// À ce stade :
				// - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
				// - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

				 return $this->render('SocialPhotoBundle::layout_create_album.html.twig', array(
				  'form' => $form->createView(),
				));
            }
        }
        else
        {
            return $this->redirect($this->generateUrl('social_login'));  
        }
    }

}
