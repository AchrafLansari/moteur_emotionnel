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
	
		$path =  $this->get('kernel')->locateResource("@MoteurRecommendationBundle/Data/data.txt");
		$data = tokenization(utf8_decode(file_get_contents($path)),"\n",0,1);
		/*$url = "http://it-ebooks-api.info/v1/search/";
		$parsed_json['Total'] = "0";
	
		while ($parsed_json['Total'] == "0"){
			$query= rtrim($data[rand(0,count($data)-1)]);
			 
			$json = file_get_contents($url.$query);
			$parsed_json = json_decode($json,true);
	
		}*/
		
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
	
		//$request->cookies->get("mycookie");
		$session = new Session();
		

		$recommandation_books = null;
                $flag = true;
	
		if($dejaVu){
			if($session->get('id')){
				$user = UtilisateurQuery::create()
				->filterById($session->get('id'))
				->findOne();
				$recommandation_books = recommandations_articles(recommandation_description($data,$user->getDescription()));
                                
                                
                        }
			$flag = false;
			 
		}else {
			if($request->getMethod() == 'POST')
			{
				$session->start();
	
				// définit des messages dits « flash »
				$session->getFlashBag()->add('notice', 'Utilisateur Modifier');
				$session->getFlashBag()->add('error', 'Pas d\'utilisateur');
				 
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
                
		//return $this->render('UserBundle:User:index.html.twig');
		return $this->render('MoteurRecommendationBundle:User:index.html.twig',array('nb_books' => $books,
				'books' => $produits,'flag'=>$flag,'recommandation_book'=>null));
	}
        
    
    public function bookAction($id)
    {
    /* $path = "http://it-ebooks-api.info/v1/book/".$id;
        
        
     $json = file_get_contents($path);
     $parsed_json = json_decode($json,true);*/
        
    $produit = new ProduitQuery;
    $row =  $produit->findById($id)->toArray();
    
    if ($row == null) {
        throw $this->createNotFoundException('No book found for id '.$id);
    }
  
    // faites quelque chose, comme passer l'objet $product à un template
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
                /*$url = "http://it-ebooks-api.info/v1/search/";
                $json = file_get_contents($url.$_POST['tags']);
                $parsed_json = json_decode($json,true);
    		$nb_books = $parsed_json['Total'];
               if ($parsed_json['Total'] == "0"){
                return new Response('Nothing Found!');
                }*/
        
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
        //return $this->render('MoteurRecommendationBundle:Recherche:liste.html.twig', array('resultats' => $resultat, 'requete' => $requete));
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
