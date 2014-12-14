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

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MoteurProduitBundle:Default:index.html.twig', array('name' => "produit"));
    }
    
    /**
     * @Route("/ajouter")
     */
    public function addAction(){
    	$kernel = $this->get('kernel');
    	$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    	
    	$titre = "mon fabuleux titre de roman policier";
    	$auteur = "Kevin Masseix";
    	$description = "le tout premier roman d'aventure ecrit par moi meme";
    	
    	$indexation = new IndexationProduit($titre, $auteur, $description, $path);
    	
		$produit = new Produit();
    	$produit->setTitre($titre);
    	$produit->setAuteur($auteur);
    	$produit->setDescription($description);
    	
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
    }
    
    
    public function getAction($id_produit, $id_utilisateur){
    	$produit = ProduitQuery::create()->findOneById($id_produit);
    	if($produit){
    		$this->updateNombreVueProduit($id_utilisateur, $id_produit);
    	}
    }
    
    public function achatAction($id_produit, $id_utilisateur){
    	$utilisateurProduit = UtilisateurProduitQuery::create()
    		->filterByUtilisateurId($id_utilisateur)
    		->filterByProduitId($id_produit)
    		->findOne();
    	
    	if(!$utilisateurProduit){
    		$utilisateurProduit = new UtilisateurProduit();
    		$utilisateurProduit->setProduitId($id_produit)
    			->setUtilisateurId($id_utilisateur);
    	}
    	if(!$utilisateurProduit->getAchat()){
    		$utilisateurProduit->setAchat(true)->save();
    	}
    }
    
    public function noteAction($id_produit, $note, $id_utilisateur){
    	$utilisateurProduit = UtilisateurProduitQuery::create()
    		->filterByUtilisateurId($id_utilisateur)
    		->filterByProduitId($id_produit)
    		->filterByAchat(true) //Il faut avoir acheté le produit pour pouvoir le noter
    		->findOne();
    	if($utilisateurProduit){
    		$utilisateurProduit->setNote($note)->save();
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
}
