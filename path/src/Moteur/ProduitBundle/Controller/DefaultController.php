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
    public function indexAction()
    {
        return $this->render('MoteurProduitBundle:Default:index.html.twig', array('name' => "produit"));
    }
    
    /**
     * @Route("/ajouter")
     */
    public function addAction(){
    	//$url = "http://www.nextinpact.com/news/91393-la-finlande-fiscalisera-copie-privee-des-1er-janvier-2015.htm";
    	
    	$this->indexActuNextInpact();
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
    
    private function indexActuNextInpact(){
    	
    	$urls = array("http://www.nextinpact.com/?page=4", "http://www.nextinpact.com/?page=5");
    	echo "<h1>Indexation</h1>";
    	
    	foreach ($urls as $url){
	    	$html = file_get_contents($url);

	    	echo "<h2>Page : ".$url."</h2>";
	    	
	    	$modele_lien = '/<h2 class="color_title">(.*)<\/h2>/i';
	    	$modele_lien_raw = '/<a href="\/news\/(.*)" title/i';
	    	
	    	preg_match_all($modele_lien, $html, $liens);
	    	 
	    	foreach ($liens[1] as $l){
	    		preg_match($modele_lien_raw, $l, $lien);
	    		if(isset($lien[1])){
	    			$url = "http://www.nextinpact.com/news/".$lien[1];
	    			
	    			$html = file_get_contents($url);
	    			 
	    			$modele_titre = '/<h1 class="color_title" itemprop="name">(.*)<\/h1>/i';
	    			$modele_description = '/<p class="actu_chapeau">(.*)<\/p>/i';
	    			$modele_auteur = '/itemprop="author">(.*)<\/a><\/h3>/i';
	    			 
	    			preg_match($modele_titre, $html, $t_titre);
	    			$titre = isset($t_titre[1]) ? $this->rip_tags($t_titre[1]) : null;
	    			 
	    			preg_match($modele_description, $html, $t_desc);
	    			$description = isset($t_desc[0]) ? $this->rip_tags($t_desc[0]) : null;
	    			 
	    			preg_match($modele_auteur, $html, $t_auteur);
	    			$auteur = isset($t_auteur[1]) ? $this->rip_tags($t_auteur[1]) : null;
	    			
	    			echo "<h3>Lien :</h3>http://www.nextinpact.com/news/".$lien[1]; 
	    			if($titre == null)
	    				echo "Titre NULL<br>";
	    			if($description == null)
	    				echo "Description NULL<br>";
	    			if($auteur == null)
	    				echo "Auteur NULL<br>";
	    			
	    			if($titre != null && $description != null && $auteur != null){
	    				echo "<br>Sauvegarde en cours<br>";
	    				$this->indexDocument($titre, $auteur, $description);
	    			}
	    		}
	    	}
    	}
    }
    
    private function indexDocument($titre, $auteur, $description){
    	$kernel = $this->get('kernel');
    	$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    	 echo $titre."<br>";
    	if(!ProduitQuery::create()->filterByTitre($titre)->findOne()){	
	    	$indexation = new IndexationProduit($titre, $auteur, $description, $path);
	    	
	    	$produit = new Produit();
	    	$produit->setTitre($titre);
	    	$produit->setAuteur($auteur);
	    	$produit->setDescription($description);
	    	
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
    	else echo "<br>FAIL";
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
