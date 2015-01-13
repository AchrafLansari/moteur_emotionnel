<?php

namespace Moteur\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;
use Moteur\ProduitBundle\Model\ProduitQuery;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateur;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery;
use Moteur\ProduitBundle\Dictionnaire\IndexationMot;
use Moteur\UtilisateurBundle\Geolocalisation\GeoIp;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Moteur\UtilisateurBundle\Model\Ip;


class BasicController extends Controller
{
	
	

	/**
	 * Affiche le home
	 * Enregistre/ou connecte un nouvel utilisateur
	 *
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction()
	{
		include_once 'functions/functions.php';
		
		
		/**
		 * 
		 * 				Partie : Initialisation de variables
		 * 
		 */
		$flag =false;
		$recommandation_books = null;
		$produits_recommandes = null;
		$session = new Session();
		$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
		$response = new Response();
	
		/**
		 * 
		 * 					Partie : Produits à afficher dans le carroussel
		 * 
		 */
		
        //Récupère aléatoirement les  produits à afficher dans le carroussel
		$sql = "SELECT * FROM PRODUIT ORDER BY RAND() LIMIT 5";
        $connexion = \Propel::getConnection();
        $statement = $connexion->prepare($sql);
        $statement->execute();
        $produits =  $statement->fetchAll();
        
        //Compte le nombre de produits effectivement récupérés
		$books = count($produits);
		
		
		
		
		
		//$response->headers->clearCookie('cookie');
		//$response->send();
		
		/**
		 * 
		 * 					Partie : enregistrement et/ou connexion de l'utilisateur
		 * 
		 */
		if($request->getMethod() == 'POST')
		{
			//Enregistre l'utilisateur et renvoie vrai s'il est connecté
			$flag = $this->enregistreUtilisateur($session, $response);
		}
	
		
		
		/**
		 * 
		 * 					Partie : affichage de produits en fonction du profil de l'utilisateur
		 * 
		 */
		//Vérifie si l'utilisateur s'est déjà enregistré sur la page
		$dejaVu = $request->cookies->has('cookie');
		
 
		//Actions à effectuer si l'utilisateur s'est déjà enregistré
		if($dejaVu || $flag){
			
			//Permet de désactiver l'affichage automatique du menu de connexion
			$flag = true;
			
			//Récupère l'id de l'utilisateur dans la session
			if($session->get('id')){
				
				//Récupère les données d'un utilisateur en fonction de son id
				$user = UtilisateurQuery::create()
					->filterById($session->get('id'))
					->findOne();
			
				//Affiche une liste en fonction de la description de l'utilisateur
				$recommandation_books = $this->listedescriptionAction(recommandation_description($user->getDescription()));
				
				//Affiche une liste de produits en fonction du profil complet de l'utilisateur
				$produits_recommandes = $this->listeAction($session->get('id'), 1, 10);
			}	 
		}
		
		//Retourne la vue
		return $this->render('MoteurRecommendationBundle:User:index.html.twig',array('nb_books' => $books,
				'books' => $produits,'flag'=>$flag,'recommandation_book'=>$recommandation_books, 'produits_profil'=>$produits_recommandes));
	}
        
    /**
     * Permet d'enregistrer/de connecter un utilisateur 
     * @param unknown $session
     * @param unknown $response
     * @return boolean
     */
	private function enregistreUtilisateur($session, $response){
		$session->start();
		
		//Initialise les différents éléments de l'utilisateur eb fonction du formulaire
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$age = $_POST['age'];
		$ville = $_POST['ville'];
		$description = $_POST['description'];
		/**
		 * @todo Il faut toujouts pouvoir ajouter un ou plusieurs centres d'intérêt pour la recommendation!!! (checkbox?)
		 */
		
		//Permet de créer les différentes composantes d'une adresse ip
		$geo = new GeoIp();
		
		//Crée une adresse IP aléatoirement
		$geo->setIpadress(rand(0,255).".".rand(0,255).".".rand(0,255).".".rand(0,255));
		
		//Liste des géolocalisations possibles
		$geolocalisation = array(
				array('pays' => 'France',
						array('region' => 'Ile-de-france',
								array('Paris', 'Saint-Denis', 'Montreuil')
						),
						array('region' => 'Rhone-Alpes',
								array('Lyon', 'Saint-Etienne', 'Grenoble')
						)
				),
				array('pays' => 'Etats-Unis',
						array('region' => 'New-York',
								array('Manhattan', 'Bronx', 'Brooklyn')
						),
						array('region' => 'Californie',
								array('Los Angeles', 'San Diego', 'San Jose')
						)
				),
				array('pays' => 'Allemagne',
						array('region' => 'Bavière',
								array('Munich', 'Augsbourg', 'Nuremberg')
						),
						array('region' => 'Saxe',
								array('Dresde', 'Leipzig', 'Chemnitz')
						)
				)
		);
		
		//On détermine une géolocalisation aléatoirement parmi la liste des géolocalisations possible
		$pays_geo = $geolocalisation[rand(0,2)];
		$region_geo = $pays_geo[rand(0,1)];
		$ville_geo = $region_geo[0][rand(0,2)];
		$geo->pays = $pays_geo['pays'];
		$geo->departement = $region_geo['region'];
		$geo->ville = $ville_geo;
		
		
		try {
			//Sauvegarde des paramètres de l'adresse IP
			$ip = new Ip();
			$ip->setPays($geo->pays);
			$ip->setDepartement($geo->departement);
			$ip->setVille($geo->ville);
			$ip->save();
			
			//Sauvegarde de l'utilisateur
			$utilisateur = new Utilisateur();
			$utilisateur->setNom($nom);
			$utilisateur->setPrenom($prenom);
			$utilisateur->setVille($ville);
			$utilisateur->setAge($age);
			$utilisateur->setDescription($description);
			$utilisateur->setIpUtilisateur($this->container->get('request')->getClientIp());
			$utilisateur->setIp();
			$utilisateur->save();
			
			
			/**
			 * @todo
			*/
			// je pense qu'il faut le récuperer de la base ou de l'action précédente
			$session->set('id',$utilisateur->getId());
			
			unset($_POST);
			
			$cookie = new Cookie('cookie', 'utilisateur',time() + 3600 * 24 * 7);
			$response->headers->setCookie($cookie);
			$response->send();
			
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
    
    
    /**
     * Recherche de produits en utilisant la barre de recherche
     * 
     * @todo Recherche disponible seulement pour les utilisateurs connectés? Ou alors si non connecté id_util = null
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rechercherAction()
    {  
    	//Initialise les variables
    	$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	$requete = "";
    	$resultats = array();
    	$books = 0;
    	$session = new Session();
        
        //Si la requete est un POST
    	if ($request->isMethod('POST')) {
    		
    		//Chargement du module permettant d'indexer la requete
    		$kernel = $this->get('kernel');
    		$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    		
    		//Récupération de la requête
            $requete = $_POST['tags'];
            
            //Récupère le dernier id d'une requête effectuée
    		$indexation = new IndexationMot($requete, $path);
    		$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne();
    		$requete_id = $requete_id->getRequeteId();
    		
    		//Id de la requête actuelle
    		$requete_id++;
    		
    		//Récupère l'id de l'utilisateur connecté
    		$utilisateur_id = $session->get('id');
    		
                
    		//Indexe chaque mot de la requête dans la base de donnéee
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
    			$ajoutRequete->setRequeteId($requete_id);
    			$ajoutRequete->setMotId($m_Req->getId());
    			$ajoutRequete->save();
    		}
    		
    		//Requete SQL
    		$sql = "CALL rechercher_produits_via_requete(?)";
    		 
    		//Récupération des produits correspondants à la requete
    		$connexion = \Propel::getConnection();
    		$statement = $connexion->prepare($sql);
    		$statement->bindParam(1, $requete_id, \PDO::PARAM_INT);
    		$statement->execute();
    		
    		$resultat = $statement->fetchAll();
    		
    		//On compte le nombre de produits
    		$books = count($resultat);
    	}  
        
        //retourne la vue adaptée
        return $this->render('MoteurRecommendationBundle:User:search.html.twig',array('nb_produits' => $books,'produits' => $resultat));
    }
    
    /**
     * Affiche une liste recommendant des produits à l'utilisateur en fonction de leurs scores
     * @param unknown $page la page à afficher
     * @param unknown $nombre le nombre de produits à afficher par page
     * @return multitype;
     */
    private function listeAction($id, $page = 1, $nombre = 10){
	    		
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
	    	return $statement->fetchAll(); //r�cup�re l'ensemble des r�sultats
	}
    
    /**
     * Affiche 10 produits en fonction de la description de l'utilisateur
     * @param unknown $tab_recommandations
     * @return multitype:
     */
    private function listedescriptionAction($tab_recommandations){
    	$books = array();
    	 
    	//requete SQL pour récupérer les produits
    	$query = "SELECT DISTINCT p.id AS id, p.titre AS titre, p.image as image, sous_titre
				FROM produit p
				JOIN produit_mot_poids pmp ON pmp.produit_id = p.id
				JOIN mot m ON m.id = pmp.mot_id
				WHERE m.mot
				IN (";
    	
    	$i=0;
    	
    	//construction de la requete
    	foreach ($tab_recommandations as $item=>$valeur ){
    		if($i++ != 0)
    			$query .= ",";
    		$query.= "?";
    	}
    	$query .= ") LIMIT 0, 10";
    	 
    	$connexion = \Propel::getConnection();
    	$statement = $connexion->prepare($query);
    	
    	//Liaison de chaque mot de la description avec la requete
    	$i = 1;
    	foreach ($tab_recommandations as $item=>$valeur ){
    	    $statement->bindParam($i++, $tab_recommandations[$item]);
    	}

    	//exécution de la requete
    	$statement->execute();
    	
    	//récupération des résultats
    	$resultats = $statement->fetchAll();
    	return $resultats;
    	
    }
     /**
     * Renvoie une liste d'utilisateur avec leurs scores dont les profils correspondent � l'utilisateur identifi� par id_utilisateur
     * Plus un score est �lev� et plus deux utilisateurs sont proches
     * @param unknown $id_utilisateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function utilisateurAction($id_utilisateur){
        
        //$id_utilisateur = $session->get('id');
        
    	//R�alise le score entre un utilisateur d'id "id_tuilisateur" et l'ensemble des utilisateurs de la base
    	$scores = ProfilScoreUtilisateurQuery::create()	//la liste des scores entre les utilisateurs
    	
    	//recherche les scores avec les id_utilisateur dont l'id est sup�rieur � celui de la variable $id utilisateur
    	->condition('cond1', 'profil_score_utilisateur.utilisateur_a_id = ?', $id_utilisateur)  //si l'id utilisateur est sur la premi�re colonne "id util" de la vue en base de donn�e
    	
    	//recherche les scores avec les id_utilisateur dont l'id est inf�rieur � celui de la variable $id utilisateur
    	->condition('cond2', 'profil_score_utilisateur.utilisateur_b_id = ?', $id_utilisateur)  //si l'id utilisateur est sur la seconde colonne "id util" de la vue en base de donn�e
    	
    	->where(array('cond1', 'cond2'), 'or')
    	->orderBy('profil_score_utilisateur.score', 'DESC') //trie par score d�croissant
    	->limit(10)	//limite le nombre de r�sultats � 10 utilisateurs
    	->find();	//effectue la recherche
    	
    	//un tableau dont chaque �l�ment associe un utilisateurs avec le score correspondant
    	$utilisateurs_score = NULL;
    	
    	//Pour chaque score il faut r�cup�rer les infos sur l'utilisateur correspondant
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
        
    	//Affiche les r�sultats dans la vue correspondante
    	return $this->render(
    			'MoteurRecommendationBundle:User:utilisateur.html.twig',array('utilisateurs_score'=>
    			$utilisateurs_score)
    	);
    	
    }
}
