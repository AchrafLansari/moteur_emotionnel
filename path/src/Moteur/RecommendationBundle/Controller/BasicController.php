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
use Moteur\ProduitBundle\Dictionnaire\IndexationMot;

class BasicController extends Controller
{
	//Remplacer l'ID par un cookie
    public function rechercherAction($requete)
    {    	
    	$kernel = $this->get('kernel');
    	$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    	
    	print_r($requete);
    	print_r($requete);
    	
    	$indexation = new IndexationMot($requete, $path);
    	
    	$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne()->getRequeteId();
		$utilisateur_id = rand(1,80);
    	
    	foreach ($indexation->indexRequete as $mot){
    		$m_Req = MotQuery::create()
    		->filterByMot($mot)
    		->findOne();
    			
    		if(!$m_Req){
    			$m_Req = new Mot();
    			$m_Req->setMot($mot)->save();
    		}
    		$ajoutRequete = new Requete();
    		$ajoutRequete->setUtilisateurId($utilisateur_id);
    		$ajoutRequete->setRequeteId($requete_id + 1);
    		$ajoutRequete->setMotId($m_Req->getId());
    		$ajoutRequete->save();
    	}

    	$sql = "CALL rechercher_produits_via_requete(?)";
    	
    	//\Propel::
    	$connexion = \Propel::getConnection();
    	$statement = $connexion->prepare($sql);
    	$statement->bindParam(1, $requete_id, \PDO::PARAM_INT);
    	$statement->execute();

    	$resultat = $statement->fetchAll();
    	
        return $this->render('MoteurRecommendationBundle:Recherche:liste.html.twig', array('resultats' => $resultat));
    }
    
    public function indexAction($page, $nombre){
    	$sql = "CALL recommander_produits(?,?,?)";
    	
    	$id = rand(0,100);
    	$debut = ($page-1)*$nombre;
    	
    	$connexion = \Propel::getConnection();
    	$statement = $connexion->prepare($sql);
    	$statement->bindParam(1, $id, \PDO::PARAM_INT);
    	$statement->bindParam(2, $debut, \PDO::PARAM_INT);
    	$statement->bindParam(3, $nombre, \PDO::PARAM_INT);
    	 
    	$statement->execute();
    	
    	$resultat = $statement->fetchAll();
    	return $this->render('MoteurRecommendationBundle:Default:index.html.twig', array('resultats' => $resultat));
    }
    
}
