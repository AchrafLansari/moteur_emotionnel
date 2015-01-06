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
use Moteur\UtilisateurBundle\Form\Type\UtilisateurType;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateur;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Moteur\UtilisateurBundle\Form\Type\InteretType;

class InteretController extends Controller
{   
    /**
     * Renvoie la liste des centres d'intérêts existants
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listerAction(){
    	//Recherche la liste des centres d'intérêts classés par ordre alphabétique
    	$interets = InteretQuery::create()->orderByNom(\Criteria::ASC)->find();
    	
    	//Retourne la liste dans la vue adaptée
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:liste_interet.html.twig', array('interets' => $interets));
    }
}
