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
    /**
     * Renvoie une liste d'utilisateur avec leurs scores dont les profils correspondent à l'utilisateur identifié par id_utilisateur
     * Plus un score est élevé et plus deux utilisateurs sont proches
     * @param unknown $id_utilisateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function utilisateurAction($id_utilisateur){
    	//Réalise le score entre un utilisateur d'id "id_tuilisateur" et l'ensemble des utilisateurs de la base
    	$scores = ProfilScoreUtilisateurQuery::create()	//la liste des scores entre les utilisateurs
    	
    	//recherche les scores avec les id_utilisateur dont l'id est supérieur à celui de la variable $id utilisateur
    	->condition('cond1', 'profil_score_utilisateur.utilisateur_a_id = ?', $id_utilisateur)  //si l'id utilisateur est sur la première colonne "id util" de la vue en base de donnée
    	
    	//recherche les scores avec les id_utilisateur dont l'id est inférieur à celui de la variable $id utilisateur
    	->condition('cond2', 'profil_score_utilisateur.utilisateur_b_id = ?', $id_utilisateur)  //si l'id utilisateur est sur la seconde colonne "id util" de la vue en base de donnée
    	
    	->where(array('cond1', 'cond2'), 'or')
    	->orderBy('profil_score_utilisateur.score', 'DESC') //trie par score décroissant
    	->limit(10)	//limite le nombre de résultats à 10 utilisateurs
    	->find();	//effectue la recherche
    	
    	//un tableau dont chaque élément associe un utilisateurs avec le score correspondant
    	$utilisateurs_score = NULL;
    	
    	//Pour chaque score il faut récupérer les infos sur l'utilisateur correspondant
    	foreach($scores as $score){
    		$id_second_utilisateur = 0;
			if($score->getUtilisateurAId() == $id_utilisateur)
				$id_second_utilisateur = $score->getUtilisateurBId();
			else $id_second_utilisateur = $score->getUtilisateurAId();
    		
    		$utilisateurs_score[] = array(
    				//ajoute les informations de l'utilisateur
    				UtilisateurQuery::create()->findOneById($id_second_utilisateur),
    				//le score entre les deux utilisateurs
    				$score->getScore()
    		);
    	}
    	
    	//Affiche les résultats dans la vue correspondante
    	return $this->render(
    			'MoteurRecommendationBundle:Default:utilisateur.html.twig',
    			$utilisateurs_score
    	);
    	
    }
}
