<?php

namespace Social\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SocialContactBundle:Default:index.html.twig', array('name' => $name));
    }
}
