<?php

namespace Moteur\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\ProduitBundle\Model\Produit;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\ProduitBundle\Dictionnaire\Indexation;
use Moteur\ProduitBundle\Dictionnaire\IndexationProduit;

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
    	//*
    	$kernel = $this->get('kernel');
    	$path = $kernel->locateResource('@MoteurProduitBundle/Dictionnaire/');
    	print_r($path);
    	//*
    	$titre = "mon fabuleux titre de roman";
    	$auteur = "Kevin Masseix";
    	$description = "le tout premier roman d'aventure ecrit par moi meme";
    	$indexation = new IndexationProduit($titre, $auteur, $description, $path);
    	print_r($indexation->indexMotPoids);
    	//*/
    	
		//*
		$produit = new Produit();
    	$produit->setTitre($titre);
    	$produit->setAuteur($auteur);
    	$produit->setDescription($description);
    	
    	
    	
    	foreach ($indexation->indexMotPoids as $mot => $poids){
    		$m = new Mot();
    		$m->setMot($mot);
    		
    		$produit_mot = new ProduitMotPoids();
    		$produit_mot->setProduit($produit);
    		$produit_mot->setMot($m);
    		$produit_mot->setPoids($poids);
    		$produit_mot->save();
    	}
    	//*/
    	//return $this->render('MoteurProduitBundle:Default:index.html.twig', array('name' => "produit"));
    }
}
