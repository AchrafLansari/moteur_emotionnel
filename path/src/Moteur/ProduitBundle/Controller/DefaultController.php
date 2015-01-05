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

set_time_limit(10000);

class DefaultController extends Controller
{
    
    /**
     * @Route("/ajouter")
     */
    public function addAction(){
    	
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
        
    private function indexDocument($titre, $auteur, $description,$soustitre,$image,$lien){
    	$kernel = $this->get('kernel');
    	$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    	if(!ProduitQuery::create()->filterByTitre($titre)->findOne()){	
	    	$indexation = new IndexationProduit($titre, $auteur, $description, $path,$soustitre,$image,$lien);
	    	
	    	$produit = new Produit();
	    	$produit->setTitre($titre);
	    	$produit->setAuteur($auteur);
	    	$produit->setDescription($description);
                $produit->setSousTitre($soustitre);
                $produit->setImage($image);
                $produit->setLien($lien);
	    	
	    	
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
    
    private function rip_tags($string) {
    	// ----- remove HTML TAGs -----
    	$string = preg_replace ('/<[^>]*>/', ' ', $string);
    
    	// ----- remove control characters -----
    	$string = str_replace("\r", '', $string);    // --- replace with empty space
    	$string = str_replace("\n", ' ', $string);   // --- replace with space
    	$string = str_replace("\t", ' ', $string);   // --- replace with space
    
    	// ----- remove multiple spaces -----
    	$string = trim(preg_replace('/ {2,}/', ' ', $string));
    
    	return $string;
    }
}
