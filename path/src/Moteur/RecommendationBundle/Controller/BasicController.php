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
    
    /**
     * Affiche une liste recommendant des produits � l'utilisateur en fonction de leurs scores
     * @param unknown $page la page � afficher
     * @param unknown $nombre le nombre de produits � afficher par page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page, $nombre){
    	//Un objet requ�te de Symfony avec les variables globales
    	$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	
    	//Variable stockant la liste des produits correspondants
    	$resultat = array();
    	
    	//Si l'utilisateur est identifi� par un cookie de nom "utilisateur_id" alors on peut r�cup�rer la liste
    	if($request->cookies->has('utilisateur_id'))
    	{
    		//l'id de l'utilisateur connect�
	    	$id = $request->cookies->get('utilisateur_id');
	    		
	    	//la requ�te SQL faisant appel � la proc�dure stock�e charger de classer les produits par score avec l'utilisateur connect�
	    	$sql = "CALL recommander_produits(?,?,?)";
	    	
	    	//pour la pagination : renvoie � partir du $debut-i�me �l�ment dont le score correspond
	    	$debut = ($page-1)*$nombre;
	    	
	    	$connexion = \Propel::getConnection();				//permet de se connecter � la base de donn�e
	    	$statement = $connexion->prepare($sql);				//pr�pare la requ�te
	    	$statement->bindParam(1, $id, \PDO::PARAM_INT);		//lie le 1er parametre avec l'id de l'utilisateur
	    	$statement->bindParam(2, $debut, \PDO::PARAM_INT);	//lie le 2eme parametre avec le debut-i�me �l�ment
	    	$statement->bindParam(3, $nombre, \PDO::PARAM_INT);	//lie le 3eme parametre avec le nombre d'�l�ments � retourner
	    	 
	    	$statement->execute();	//ex�cute la requ�te

	    	//Les r�sultats comprennent les champs suivants : "produit_id", "score", "titre", "auteur"
	    	$resultat = $statement->fetchAll(); //r�cup�re l'ensemble des r�sultats
    	}
    	
    	//renvoie les r�sultats dans la vue adapt�e
    	return $this->render('MoteurRecommendationBundle:Default:index.html.twig', array('resultats' => $resultat, 'page' => $page, 'nombre' => $nombre));
    }
    
}
