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
	
	

	public function indexAction()
	{
		include_once 'functions/functions.php';
	
		$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
	
                $sql = "SELECT * FROM PRODUIT ORDER BY RAND() LIMIT 25";
                $connexion = \Propel::getConnection();
                $statement = $connexion->prepare($sql);
                $statement->execute();
                
                $produits =  $statement->fetchAll();
               
	
		$books = count($produits);
		$response = new Response();
		//$response->headers->clearCookie('cookie');
		//$response->send();
	
		$dejaVu = $request->cookies->has('cookie');
		$session = new Session();
		

		$recommandation_books = null;
 
		if($dejaVu){
			if($session->get('id')){
				$user = UtilisateurQuery::create()
				->filterById($session->get('id'))
				->findOne();
				$recommandation_books = $this->listedescriptionAction(recommandation_description($user->getDescription()));
                                
                                
                        }
			$flag = false;
			 
		}else {
			if($request->getMethod() == 'POST')
			{
				$session->start();
	
				// définit des messages dits « flash »
				//$session->getFlashBag()->add('notice', 'Utilisateur Modifier');
				//$session->getFlashBag()->add('error', 'Pas d\'utilisateur');
				 
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$age = $_POST['age'];
				$ville = $_POST['ville'];
				$description = $_POST['description'];
	
				// définit et récupère des attributs de session
	
				$geo = new GeoIp();
				
				do{
					$geo->setIpadress(rand(0,255).".".rand(0,255).".".rand(0,255).".".rand(0,255));
					$geo->geoCheckIP();
				}
				while($geo->pays==null);
				
				$ip = new Ip();
				$ip->setPays($geo->pays);
				$ip->setDepartement($geo->departement);
				$ip->setVille($geo->ville);
				$ip->save();
				
				$utilisateur = new Utilisateur();
				$utilisateur->setNom($nom);
				$utilisateur->setPrenom($prenom);
				$utilisateur->setVille($ville);
				$utilisateur->setAge($age);
				$utilisateur->setDescription($description);
				$utilisateur->setIpUtilisateur($this->container->get('request')->getClientIp());
				$utilisateur->setIp();
				$utilisateur->save();
	
				
	
				$session->set('id',$utilisateur->getId());// je pense qu'il faut le récuperer de la base ou de l'action précedante
	
	
				unset($_POST);
	
				$cookie = new Cookie('cookie', 'utilisateur',time() + 3600 * 24 * 7);
				$response->headers->setCookie($cookie);
				$response->send();

				
			}
			$flag=true;
				
		}
		return $this->render('MoteurRecommendationBundle:User:index.html.twig',array('nb_books' => $books,
				'books' => $produits,'flag'=>$flag,'recommandation_book'=>$recommandation_books));
	}
        
    
    public function bookAction($id)
    {
   
    $produit = new ProduitQuery;
    $row =  $produit->findById($id)->toArray();
    
    if ($row == null) {
        throw $this->createNotFoundException('No book found for id '.$id);
    }
  
    return $this->render('MoteurRecommendationBundle:User:book.html.twig',array(
                     'book' => $row));
    }
    // recherche de produits en utilisant la barre de recherche 
    public function rechercherAction()
    {  
    	$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	$requete = "";
    	$resultats = array();
    	$session = new Session();
        
        
        
    	if ($request->isMethod('POST')) {
    		$kernel = $this->get('kernel');
    		$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    		
                $requete = $_POST['tags'];
                
        
    		$indexation = new IndexationMot($requete, $path);
    		$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne();
    		$requete_id = $requete_id->getRequeteId();
    		
    		
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
    		
    		$requete_id++;
    		$sql = "CALL rechercher_produits_via_requete(?)";
    		 
    		//\Propel::
    		$connexion = \Propel::getConnection();
    		$statement = $connexion->prepare($sql);
    		$statement->bindParam(1, $requete_id, \PDO::PARAM_INT);
    		$statement->execute();
    		
    		$resultat = $statement->fetchAll();
    	}  
        $books = count($resultat);
        
        return $this->render('MoteurRecommendationBundle:User:search.html.twig',array('nb_books' => $books,'books' => $resultat));
    }
    
    /**
     * Affiche une liste recommendant des produits � l'utilisateur en fonction de leurs scores
     * @param unknown $page la page � afficher
     * @param unknown $nombre le nombre de produits � afficher par page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeAction($page, $nombre){
    	//Un objet requ�te de Symfony avec les variables globales
    	$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	$session = new Session();
    	$session->start();
        
    	//Variable stockant la liste des produits correspondants
    	$resultat = array();
    	
    	//Si l'utilisateur est identifi� par la session a s'assurer de la condition
    	if($session->get('id')== null)
    	{
    		//l'id de l'utilisateur connect�
	    	$id = $session->get('id');
	    		
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
    
    public function listedescriptionAction($tab_recommandations){
    	$books = array();
    	 
    	$query = "SELECT DISTINCT p.id AS id, p.titre AS titre, p.image as image, sous_titre
				FROM produit p
				JOIN produit_mot_poids pmp ON pmp.produit_id = p.id
				JOIN mot m ON m.id = pmp.mot_id
				WHERE m.mot
				IN (";
    	
    	$i=0;
    	
    	foreach ($tab_recommandations as $item=>$valeur ){
    		if($i++ != 0)
    			$query .= ",";
    		$query.= "?";
    	}
    	$query .= ") LIMIT 0, 10";
    	 
    	$connexion = \Propel::getConnection();
    	$statement = $connexion->prepare($query);
    	
    	$i = 1;
    	foreach ($tab_recommandations as $item=>$valeur ){
    	    $statement->bindParam($i++, $tab_recommandations[$item]);
    	}

    	$statement->execute();
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
    			'MoteurRecommendationBundle:Default:utilisateur.html.twig',
    			$utilisateurs_score
    	);
    	
    }
}
