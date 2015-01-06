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
use Moteur\ProduitBundle\Form\Type\ProduitType;

set_time_limit(10000);

class DefaultController extends Controller
{
    
    /**
     * @Route("/ajouter")
     */
    public function addAction(){
    	$produit = new Produit();	//Le nouvel utilisateur
    	$form = $this->createForm(new ProduitType(), $produit);	//Le formulaire associé
    	 
    	$request = $this->getRequest();		//Recupère l'état de la requête
    	 
    	//Si on accède à ce controleur via une requête POST alors c'est que l'on a soumis le formulaire
    	if('POST' == $request->getMethod()){
    	
    		//On récupère le formulaire envoyé
    		$form->handleRequest($request);
    	
    		//S'il est valide alors on l'enregistre
    		if ($form->isValid()){
    			/**
    			 *			SAUVEGARDE DU PRODUIT
    			 */
    			$produit->save();
    				
    			/**
    			 * 			INDEXATION DES MOTS DU PRODUIT
    			 */
    			$this->indexDocument($produit);
    			
    			
    			//on affiche la vue adaptée
    		}
    	}
    	//on renvoie vers la vue du formulaire de création de l'utilisateur
    	return $this->render('MoteurProduitBundle:Default:add.html.twig', array('form' => $form->createView()));
    }
    
    
    public function getAction($id_produit, $id_utilisateur){
    	$produit = ProduitQuery::create()->findOneById($id_produit);
    	if($produit){
    		$this->updateNombreVueProduit($id_utilisateur, $id_produit);
    	}
    }
    
    private function updateNombreVueProduit($id_utilisateur, $id_produit){
    	$utilisateurProduit = UtilisateurProduitQuery::create()
    	->filterByUtilisateurId($id_utilisateur)
    	->filterByProduitId($id_produit)
    	->findOne();
    	if($utilisateurProduit == null){
    		$utilisateurProduit = new UtilisateurProduit();
    		$utilisateurProduit
    		->setUtilisateurId($id_utilisateur)
    		->setProduitId($id_produit)
    		->setNote(null)
    		->setAchat(false)
    		->setNombreVisite(1);
    	}
    	else{
    		$utilisateurProduit->setNombreVisite($utilisateurProduit->getNombreVisite()+1);
    	}
    	$utilisateurProduit->save();
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
