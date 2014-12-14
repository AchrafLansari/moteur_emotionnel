<?php

namespace Moteur\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MoteurUtilisateurBundle:Default:index.html.twig', array('name' => "nom"));
    }
}
