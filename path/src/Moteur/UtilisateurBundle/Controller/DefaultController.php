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
    	$interets = InteretQuery::create()->find();
    	$produits = ProduitQuery::create()->find();
    	
    	for($i=0; $i<10; $i++){
	    	do{
	    		$geo = new GeoIp();
	    	}
	    	while ($geo->pays == null);
	    	
	    	$ip = new Ip();
	    	$ip->setPays($geo->pays);
		    $ip->setDepartement($geo->departement);
		    $ip->setVille($geo->ville);
	    	
	    	$utilisateur = new Utilisateur();
	    	$utilisateur->setNom(sha1(rand(0, 10)));
	    	$utilisateur->setPrenom(sha1(rand(0, 10)));
	    	$utilisateur->setMail("masseix.kevin@gmail.com");
	    	$utilisateur->setAge(rand(10, 75));
	    	$utilisateur->setVille("Paris");
	    	$utilisateur->setIp($ip);
	    	$utilisateur->save();
	    	
	    	foreach ($interets as $interet){
		    	if(rand(0, 10)<3){
		    		$utilisateur_interet = new UtilisateurInteret();
		    		$utilisateur_interet->setUtilisateur($utilisateur)
		    							->setInteret($interet)
		    							->setValeur(rand(0, 10))
		    							->save();
		    	}
	    	}
	    	
	    	foreach ($produits as $produit){
	    		if(rand(0, 10)<5){

	    			$visites = rand(0,5);
	    			
	    			$achat;
	    			if($visites>0)
	    				$achat = (rand(0, 5)<2) ? true : false;
	    			else
	    				$achat = false;
	    			
	    			$note;
	    			if(!$achat || rand(0,5)<2)
	    				$note = null;
	    			else
	    				$note = rand(0, 10);
	    			
	    			$utilisateur_produit = new UtilisateurProduit();
	    			
	    			echo "Utilisateur : ".$utilisateur->getId()."<br>";
	    			echo "Produit : ".$produit->getId()."<br>";
	    			echo "Visites : ".$visites."<br>";
	    			echo "Achat : ".$achat."<br>";
	    			echo "Note : ".$note."<br><br>";
	    			
	    			$utilisateur_produit->setNombreVisite($visites)
	    								->setAchat($achat)
	    								->setNote($note)
	    								->setUtilisateur($utilisateur)
	    								->setProduitId($produit->getId())
	    								->save();
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
