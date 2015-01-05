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
        
        return $this->render('UserBundle:User:book.html.twig',array('book' => $parsed_json));
        
        }
    
    public function telechargerAction($id_produit, $id_utilisateur){
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
    	
    	$produit = $utilisateurProduit->getProduit();
    	
    	return $this->render('MoteurProduitBundle:Produit:afficher.html.twig', array('produit' => $produit, 'visites' => $utilisateurProduit->getNombreVisite(), 'achat' => $utilisateurProduit->getAchat(), 'note' => $utilisateurProduit->getNote(), 'utilisateur' => $utilisateurProduit->getUtilisateurId()));
    }

    public function noteAction($id_produit, $id_utilisateur){
    	$request = $this->get('request');
    	
    	if ($request->isMethod('POST')) {
	    	$utilisateurProduit = UtilisateurProduitQuery::create()
	    	->filterByUtilisateurId($id_utilisateur)
	    	->filterByProduitId($id_produit)
	    	->filterByAchat(true) //Il faut avoir acheté le produit pour pouvoir le noter
	    	->findOne();
	    	if($utilisateurProduit){
	    		$utilisateurProduit->setNote($_POST['note'])->save();
	    	}
    	}
    	
    	$produit = $utilisateurProduit->getProduit();
    	 
    	return $this->render('MoteurProduitBundle:Produit:afficher.html.twig', array('produit' => $produit, 'visites' => $utilisateurProduit->getNombreVisite(), 'achat' => $utilisateurProduit->getAchat(), 'note' => $utilisateurProduit->getNote(), 'utilisateur' => $utilisateurProduit->getUtilisateurId()));
    }
}
