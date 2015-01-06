<?php

namespace Moteur\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\UtilisateurBundle\Geolocalisation\GeoIp;
use Moteur\UtilisateurBundle\Model;
use Moteur\UtilisateurBundle\Model\Ip;
use Moteur\UtilisateurBundle\Model\IpQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\Interet;
use Moteur\UtilisateurBundle\Model\InteretQuery;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;
use Moteur\UtilisateurBundle\Model\UtilisateurInteret;
use Moteur\UtilisateurBundle\Model\UtilisateurInteretQuery;
use Moteur\ProduitBundle\Model\ProduitQuery;
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\RecommendationBundle\Model\RequeteQuery;

set_time_limit(500);

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MoteurUtilisateurBundle:Default:index.html.twig', array('name' => "nom"));
    }
    
    /**
     * @Route("/ip/ajout")
     */
    public function addIPAction(){
    	
    	
    	//IpQuery::create()->filterByPays($geo->pays);
    	
    	for($i=0; $i<100; $i++){
    		$geo = new GeoIp();
    		
    		if($geo->pays != null){
	    		$ip = new Ip();
	    		$ip->setPays($geo->pays);
	    		$ip->setDepartement($geo->departement);
	    		$ip->setVille($geo->ville);
	    		
	    		$ip->save();
    		}
    	}
    }
    
    /**
     * @Route("/ajout")
     */
    public function addAction(){
    	//Récupère l'ensemble des centres d'interet enregistrés
    	$interets = InteretQuery::create()->find();
    	
    	//Récupère l'ensembles des produits enregistrés
    	$produits = ProduitQuery::create()->find();
    	
    	//Récupère 500 des mots indexés
    	$mots = MotQuery::create()->limit(500)->find();
    	
    	//On génère les utilisateurs
    	for($i=0; $i<10; $i++){		//Permet de générer 10 utilisateurs
	    	
    		//On crée une adresse IP et on récupère les informations sur celle-ci
    		do{
	    		$geo = new GeoIp();
	    		//génère une IP aléatoire afin d'éviter d'enregistrer seulement des IP correspondant au localhost
	    		$geo->setIpadress(rand(0,255).".".rand(0,255).".".rand(0,255).".".rand(0,255));
	    		$geo->geoCheckIP();
	    	}
	    	while ($geo->pays == null);
	    	
	    	//On crée un objet contenant les informations sur l'adresse ip
	    	$ip = new Ip();
	    	$ip->setPays($geo->pays);
		    $ip->setDepartement($geo->departement);
		    $ip->setVille($geo->ville);
	    	
		    //On crée un nouvel utilisateur
	    	$utilisateur = new Utilisateur();
	    	$utilisateur->setNom(substr(sha1(rand(0, 10)), 0, rand(5,15)));		//Le nom fera entre 5 et et 15 caractères
	    	$utilisateur->setPrenom(substr(sha1(rand(0, 10)), 0, rand(5,15)));	//Le prénom fera entre 5 et 15 caractères
	    	$utilisateur->setMail(
	    			substr(sha1(rand(0, 10)), 0, rand(5,15))	//Première partie de l'adresse mail
	    			."@".										//@
	    			substr(sha1(rand(0, 10)), 0, rand(5,10))	//Nom de domaine de l'adresse mail
	    			.".".
	    			substr(sha1(rand(0, 10)), 0, rand(2,4)));	//Extension du nom de domaine de l'adresse mail
	    	$utilisateur->setAge(rand(10, 75));	//Définit un age compris entre 10 et 75
	    	
	    	//ville possible
	    	$liste_ville = array('Paris', 'New York', 'Marseille', 'Lyon', 'Moscou', 'Londres');
	    	
	    	$utilisateur->setVille($liste_ville[rand(0,5)]);	//ville choisie aléatoirement parmi les villes possibles
	    	
	    	$description = "";
	    	for($i=0; $i<rand(0,100); $i++){	//La description aura entre 0 et 100 mots
	    		//Chaque mot comporte entre 2 et 10 caractères
	    		$description .= substr(sha1(rand(0,10000)), 0, rand(2, 10))." ";
	    	}
	    	
	    	$utilisateur->setDescription($description);
	    	$utilisateur->setIpUtilisateur($geo->getIpadress());
	    	$utilisateur->setIp($ip);
	    	
	    	//On sauvegarde le nouvel utilisateur
	    	$utilisateur->save();
	    	
	    	/**
	    	 * 				AJOUTE DES CENTRES D'INTERET A L'UTILISATEUR
	    	 */
	    	
	    	
	    	//On parcours la liste des centres d'intérêts
	    	foreach ($interets as $interet){
		    	if(rand(0, 10) < 3){	//On décide aléatoirement si on ajoute ce centre d'intérêts à l'utilisateur
		    		$utilisateur_interet = new UtilisateurInteret();
		    		$utilisateur_interet->setUtilisateur($utilisateur)
		    							->setInteret($interet)
		    							->setValeur(rand(0, 10))	//on choisit une valeur entre 0 et 10
		    							->save();
		    	}
	    	}
	    	
	    	/**
	    	 * 			CREE DES INTERACTIONS ENTRE L'UTILISATEUR ET LES PRODUITS SAUVEGARDES
	    	 * 
	    	 */
	    	
	    	//On parcours la liste des produits
	    	foreach ($produits as $produit){
	    		if(rand(0, 10) < 5){	//On décide aléatoirement si l'utilisateur a interagit avec ce produit

	    			$visites = rand(0,5);	//s'il a interagit alors on définit aléatoirement un nombre de visite de ce produit
	    			
	    			$achat;
	    			
	    			//s'il a visité au moins une fois le produit alors il peut l'acheter/télécharger
	    			if($visites>0)
	    				$achat = (rand(0, 5)<2) ? true : false;	//On décide alétoirement s'il a acheté/téléchargé le produit
	    			else
	    				$achat = false;	//S'il n'a pas visité le produit alors il ne l'a pas acheté/téléchargé
	    			
	    			$note;
	    			
	    			//S'il a acheté/téléchargé le produit alors il a pu le noter et on décide aléatoirement s'il a souhaité noter le produit
	    			if(!$achat || rand(0,5)<2)
	    				$note = null;	//si ce n'est pas le cas alors la note est null (NE PAS METTRE 0, ça fausserait le scoring)
	    			else
	    				$note = rand(0, 10);	//sinon on décide aléatoirement d'une note comprise entre 0 et 10
	    			
	    			//On crée un modèle recensant les interactions d'un utilisateur avec un produit
	    			$utilisateur_produit = new UtilisateurProduit();
	    			
	    			//On enregistre le modèle ainsi crée dans la base de données
	    			$utilisateur_produit->setNombreVisite($visites)
	    								->setAchat($achat)
	    								->setNote($note)
	    								->setUtilisateur($utilisateur)
	    								->setProduitId($produit->getId())
	    								->save();
	    		}
	    	}
	    	
	    	/**
	    	 * 				CREE DES REQUETES AYANT ETE EFFECTUEES PAR L'UTILISATEUR
	    	 * 				->Simule des recherches de produits ayant été effectuées par l'utilisateur
	    	 * 
	    	 */
	    	
	    	//Réalisation de chaque requête
	      	for($i=0; $i < rand(0,20); $i++){	//On décide aléatoirement du nombre de requêtes effectuées par l'utilisateur
		    	$requete = array();
		    	
		    	foreach ($mots as $mot){
		    		//On décide aléatoirement si le mot fait partie de la requête
		    		if(rand(0,100)<2)
		    			$requete[] = $mot->getId();
		    	}
		    	
		    	//Si la requete n'est pas vide alors on peut l'enregistrer
		    	if(count($requete)>0){
		    		//l'id de la dernière requete effectuée
		    		$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne()->getRequeteId();
		    		
		    		//On enregiste individuellement chaque mot dans la requête
		    		foreach ($requete as $id_mot){
		    			$ajoutRequete = new Requete();
		    			$ajoutRequete->setUtilisateur($utilisateur);
		    			$ajoutRequete->setRequeteId($requete_id + 1);
		    			$ajoutRequete->setMotId($id_mot);
		    			$ajoutRequete->save();
		    		}
		    	}
	    	}
    	}
    }
    
    /**
     * @Route("/interet/creer/{nom}")
     */
    public function createInteretAction($nom){
    	InteretQuery::create()->filterByNom($nom)->findOneOrCreate()->setNom($nom)->save();
    }
    
    /**
     * @Route("/interet/ajout")
     */
    public function addInteretAction(){
    	$interets = InteretQuery::create()->find();
    	$utilisateurs = UtilisateurQuery::create()->find();
    	
    	foreach ($utilisateurs as $utilisateur){
    		foreach ($interets as $interet){
    			if(rand(0,10) < 3){
	    			$utilisateur_interet = new UtilisateurInteret();
	    			UtilisateurInteretQuery::create()
	    				->filterByUtilisateurId($utilisateur->getId())
	    				->filterByInteretId($interet->getId())
	    				->findOneOrCreate()
	    				->setUtilisateurId($utilisateur->getId())
	    				->setInteretId($interet->getId())
	    				->setValeur(rand(0, 10))
	    				->save();
    			}
    		}
    	}
    }
}
