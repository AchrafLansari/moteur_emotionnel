<?php

namespace Moteur\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
use Symfony\Component\HttpFoundation\Request;

class BasicController extends Controller
{
	
     /**
     * @Route("/search/", name="_search")
     * @Template()
     */
    public function rechercherAction()
    {  
    	$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	$requete = "";
    	$resultats = array();
    	$session = new Session();
        
        
        
    	if ($request->isMethod('POST')) {
    		$kernel = $this->get('kernel');
    		$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    		
                
                $url = "http://it-ebooks-api.info/v1/search/";
                $json = file_get_contents($url.$_POST['tags']);
                $parsed_json = json_decode($json,true);
    		$requete = $_POST['tags'];
    		
                /*if ($parsed_json['Total'] == "0"){
        
                return new Response('Nothing Found!');
        
        
                }*/
        
    		$indexation = new IndexationMot($requete, $path);
    		 
    		$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne()->getRequeteId();
    		$utilisateur_id = $session->get('id');
    		
                
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
    		 
    	}  
        $books = count($parsed_json['Books']);
        return $this->render('UserBundle:User:index.html.twig',array('nb_books' => $books,'books' => $parsed_json['Books'],'flag'=>false));
        //return $this->render('MoteurRecommendationBundle:Recherche:liste.html.twig', array('resultats' => $resultat, 'requete' => $requete));
    }
    
    public function indexAction($page, $nombre){
    	$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	
    	$resultat = array();
    	
    	if($request->cookies->has('utilisateur_id'))
    	{
	    	$id = $request->cookies->get('utilisateur_id');
	    		
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
    	}
    	
    	
    	return $this->render('MoteurRecommendationBundle:Default:index.html.twig', array('resultats' => $resultat, 'page' => $page, 'nombre' => $nombre));
    }
    
}
