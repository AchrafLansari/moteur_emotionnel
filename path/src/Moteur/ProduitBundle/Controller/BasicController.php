<?php

namespace Moteur\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\ProduitBundle\Model\Produit;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\ProduitBundle\Dictionnaire\IndexationProduit;
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\ProduitBundle\Model\UtilisateurProduitQuery;
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\ProduitBundle\Model\ProduitQuery;
use Moteur\ProduitBundle\Model\ProduitMotPoidsPeer;
use Propel;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

set_time_limit(10000);

class BasicController extends Controller
{
	/**
     * @Route("/book/{id}", name="_book_show")
     * @Template()
     */
    public function afficherAction($id)
    {	
         $path = "http://it-ebooks-api.info/v1/book/".$id;
         $json = file_get_contents($path);
         $parsed_json = json_decode($json,true);
         $produit = new Produit();
         
        if ($parsed_json["Error"]!="0") {
            throw $this->createNotFoundException('No book found for id '.$id);
        }else {
            $produit->setId($parsed_json['ID']);
            $produit->setImage($parsed_json['Image']);
            $produit->setTitre($parsed_json['Title']);
            $produit->setSousTitre($parsed_json['SubTitle']);
            $produit->setDescription($parsed_json['Description']);
            $produit->setLien($parsed_json['Download']);
            $produit->save();
        }
        
        $session = new Session();
        $session->start();
    	$utilisateur_id = $session->get('id');
    	
    	$utilisateurProduit = UtilisateurProduitQuery::create()
    								->filterByProduit($produit)
    								->filterByUtilisateurId($utilisateur_id)
    								->findOneOrCreate()
    								->setNombreVisite(0);
    	$utilisateurProduit->setNombreVisite($utilisateurProduit->getNombreVisite()+1);
    	$utilisateurProduit->save();
    	
        //return $this->render('MoteurProduitBundle:Produit:afficher.html.twig', array('produit' => $produit, 'visites' => $utilisateurProduit->getNombreVisite(), 'achat' => $utilisateurProduit->getAchat(), 'note' => $utilisateurProduit->getNote(), 'utilisateur' => $utilisateurProduit->getUtilisateurId()));
        
        return $this->render('MoteurRecommendationBundle:User:book.html.twig',array('book' => $parsed_json));
        }
    
    /**
     * Permet de spécifier que l'utilisateur a téléchargé un produit
     * L'interaction entre l'utilisateur et le produit permet d'affiner son score avec ce produit
     * @param unknown $id_produit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function telechargerAction($id_produit){
    	
    	$session = new Session();
    	$session->start();
    	$id_utilisateur = $session->get('id');
    	
    	//Trouve l'enregistrement qui stocke les interactions (nombres de visites, a téléchargé, note) entre un utilisateur et un produit
    	$utilisateurProduit = UtilisateurProduitQuery::create()
    	->filterByUtilisateurId($id_utilisateur)
    	->filterByProduitId($id_produit)
    	->findOne();
    	 
    	//Si l'enregistrement n'existe pas alors on en crée un
    	if(!$utilisateurProduit){
    		$utilisateurProduit = new UtilisateurProduit();
    		$utilisateurProduit->setProduitId($id_produit)
    		->setUtilisateurId($id_utilisateur);
    	}
    	
    	//On sauvegarde en base de donnée que l'utilisateur a téléchargé le produit
    	if(!$utilisateurProduit->getAchat()){
    		$utilisateurProduit->setAchat(true)->save(); // TRUE car le produit a été téléchargé
    	}
    	
    	//On récupère les informations du produit mises à jour
    	$produit = $utilisateurProduit->getProduit();
    	
    	//On affiche les informations sur le produit
    	return $this->render('MoteurProduitBundle:Produit:afficher.html.twig', array('produit' => $produit, 'visites' => $utilisateurProduit->getNombreVisite(), 'achat' => $utilisateurProduit->getAchat(), 'note' => $utilisateurProduit->getNote(), 'utilisateur' => $utilisateurProduit->getUtilisateurId()));
    }

    /**
     * Permet à un utilisateur de noter un produit qu'il a déjà téléchargé
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function noteAction($id_produit){
    	$request = $this->get('request');
    	
        $session = new Session();
        $session->start();
    	/**
    	 * @todo
    	 * Récupérer l'id de l'utilisateur dans les cookies s'il est connecté
    	 * 
    	 */
    	$id_utilisateur = $session->get('id');
        
    	/**
    	 * @todo l'utilisateur ne peut noter le produit que s'il est connecté
    	 */
    	
    	//Vérifie que l'utilisateur essaye de noter le produit via une requête de type POST
    	if ($request->isMethod('POST')) {
    		
    		//Vérifie que l'utilisateur essaye de noter un produit qu'il a déjà téléchargé (acheté)
	    	$utilisateurProduit = UtilisateurProduitQuery::create()
	    	->filterByUtilisateurId($id_utilisateur)
	    	->filterByProduitId($id_produit)
	    	->filterByAchat(true) 		//Il faut avoir téléchargé/acheté le produit pour pouvoir le noter
	    	->findOne();
	    	
	    	//Si la précédente requête a réussi alors on peut ajouter la note de l'utilisateur pour le produit
	    	if($utilisateurProduit){
	    		$utilisateurProduit->setNote($_POST['note'])->save();
	    	}
	    	
	    	//On récupère les informations du produit mises à jour
	    	$produit = $utilisateurProduit->getProduit();
	    	 
	    	//Affiche le produit dans la vue adaptée
	    	return $this->render('MoteurProduitBundle:Produit:afficher.html.twig', array('produit' => $produit, 'visites' => $utilisateurProduit->getNombreVisite(), 'achat' => $utilisateurProduit->getAchat(), 'note' => $utilisateurProduit->getNote(), 'utilisateur' => $utilisateurProduit->getUtilisateurId()));
    	}
    	
    	/**
    	 * @todo Renvoyer un message d'erreur en json?
    	 */
    }
    
    public function genererAction(){
        //*
        $form = $this->createFormBuilder()
            ->add('requete', 'text')
            ->add('nombre', 'integer')
            ->add('Generer', 'submit')
            ->getForm();
    	 
    	$request = $this->getRequest();		//Recupère l'état de la requête
    	 //*/
    	//Si on accède à ce controleur via une requête POST alors c'est que l'on a soumis le formulaire
    	if('POST' == $request->getMethod()){
    	
    		//On récupère le formulaire envoyé
    		$form->handleRequest($request);
    	
    		//S'il est valide alors on l'enregistre
    		if ($form->isValid()){
    			/**
    			 *			SAUVEGARDE DU PRODUIT
    			 */
    				//echo "ok";
//*                        
						$url = "http://it-ebooks-api.info/v1/search/";
                        
                        //$query = array("java", "php", "net", "html");
                        
                        //for($q = 0; $q < count($query); $q++){

    						$form_send = $_POST["form"];
    						$nombre = $form_send['nombre'];
    			 			$page = ceil($nombre/10);
    						
                        	for($i = 0; $i <= $page; $i++){
	                        	$json = file_get_contents($url.
	                        			$form_send['requete']
	                        			//$query[$q]
	                        			."/page/".$page);
	                        	$parsed_json = json_decode($json,true);
	                        	
	                        	for($i =0; $i < count($parsed_json['Books']); $i++){
	                        		if($nombre <= 0 )
	                        			break 1;
	                        		
	                        		$id = $parsed_json['Books'][$i]['ID'];
	                        		
	                        		$json_produit = file_get_contents("http://it-ebooks-api.info/v1/book/".$id);
	                        		$parsed_json_produit = json_decode($json_produit, true);
	                        		
	                        		
	                        		//Récupère le titre du document
	                        		$titre = isset($parsed_json['Books'][$i]['Title']) ?
	                        					$parsed_json['Books'][$i]['Title'] : null;
	                        		
	                        		//Optionnel - récupère le sous-titre du document
	                        		$sous_titre = isset($parsed_json['Books'][$i]['SubTitle']) ?
	                        					$parsed_json['Books'][$i]['SubTitle'] : null;
	                        		
	                        		//Récupère la description du produit
	                        		$description = isset($parsed_json['Books'][$i]['Description']) ?
	                        					$parsed_json['Books'][$i]['Description'] : null;
	                        		
	                        		//
	                        		$auteur = isset($parsed_json_produit['Author']) ?
	                        					$parsed_json_produit['Author'] : null;
	                        		
	                        		//Récupère l'image du produit
	                        		$image = isset($parsed_json['Books'][$i]['Image']) ?
	                        					$parsed_json['Books'][$i]['Image'] : null;
	                        		
	                        		//
	                        		$lien = isset($parsed_json_produit['Download']) ?
	                        					$parsed_json_produit['Download'] : null;
	                        		
	                        		$produit = new Produit();
	                        		$produit->setTitre($titre);
	                        		$produit->setSousTitre($sous_titre);
	                        		$produit->setDescription($description);
	                        		$produit->setAuteur($auteur);
	                        		$produit->setImage($image);
	                        		$produit->setLien($lien);
	                        		if($produit->save()){
	                        			$this->indexDocument($produit);
	                        			echo"<br>Nombre de documents restants à sauvegarder : " .--$nombre;
	                        		}
	                        	}	
                        	}
                        //}
  //*/                      
    			
    			
    			
    			
    			//on affiche la vue adaptée
    		}
    	}
    	//on renvoie vers la vue du formulaire de création de l'utilisateur
    	return $this->render('MoteurProduitBundle:Default:generer.html.twig', array('form' => $form->createView()));
    }
    
    private function indexDocument($produit){
    	$kernel = $this->get('kernel');
    	$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    	$indexation = new IndexationProduit($produit->getTitre(),
    			$produit->getAuteur(),
    			$produit->getDescription(),
    			"",
    			$produit->getSousTitre(),
    			$produit->getImage(),
    			$produit->getLien()
    	);
    
    	$con = Propel::getConnection(ProduitMotPoidsPeer::DATABASE_NAME);
    	$con->beginTransaction();
    
    	foreach ($indexation->indexMotPoids as $mot => $poids){
    		$produit_mot = new ProduitMotPoids();
    		$produit_mot->setProduit($produit);
    		 
    		$m = MotQuery::create()->findOneByMot($mot);
    		
    		if($m){
    			$produit_mot->setMotId($m->getId());
    		}
    		else {
    			$m = new Mot();
    			$m->setMot($mot);
    			$produit_mot->setMot($m);
    		}
    		$produit_mot->setPoids($poids);
    		$produit_mot->save();
    	}
    	 
    	$con->commit();
    }
}
