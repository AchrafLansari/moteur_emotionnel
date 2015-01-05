<?php

namespace Moteur\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateur;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery;

class DefaultController extends Controller
{    
    
    
    public function utilisateurAction($id_utilisateur){
    	$t = microtime(true);
    	$scores = ProfilScoreUtilisateurQuery::create()
    	->condition('cond1', 'profil_score_utilisateur.utilisateur_a_id = ?', $id_utilisateur)
    	->condition('cond2', 'profil_score_utilisateur.utilisateur_b_id = ?', $id_utilisateur)
    	->where(array('cond1', 'cond2'), 'or')
    	->orderBy('profil_score_utilisateur.score', 'DESC')
    	->find();
    	print_r($scores);
    	//echo microtime(true) - $t;
    	
    	/*
    	 
    	return $this->render(
    			'MoteurRecommendationBundle:Default:utilisateur.html.twig',
    			array('scores' => $scores)
    	);
    	//*/
    	
    }
}
