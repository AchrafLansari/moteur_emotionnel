<?php

namespace Moteur\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MoteurRecommendationBundle:Default:index.html.twig', array('name' => "nom"));
    }
    
    /**
     * @Route("/recherche/{requete}")
     */
    public function rechercheAction($requete)
    {
    	$mots;
    	$tok = strtok($requete, "+");
    	while($tok !== false){
    		$m = new Mot();
    		$m->setMot($tok);
    		$mots[] = $m;
    		$tok = strtok("+");
    	}
    	print_r($mots);
    	
    	$score = new ProfilScoreRequeteUtilisateurProduit();
    	//TO DO
    }
}
