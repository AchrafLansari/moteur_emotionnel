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

class BasicController extends Controller
{
	/**
	 * Permet de créer un nouvel utilisateur
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function creerAction()
    {
    	$utilisateur = new Utilisateur();	//Le nouvel utilisateur
    	$form = $this->createForm(new UtilisateurType(), $utilisateur);	//Le formulaire associé
    	
    	$request = $this->getRequest();		//Recupère l'état de la requête
    	
    	//Si on accède à ce controleur via une requête POST alors c'est que l'on a soumis le formulaire
    	if('POST' == $request->getMethod()){
    		
    		//On récupère le formulaire envoyé
    		$form->handleRequest($request);
    		
    		//S'il est valide alors on l'enregistres
    		if ($form->isValid()){
    			
    			//un nouvel objet IP
    			$ip = new Ip();
    			
    			//Permet de géolocaliser un utilisateur en fonction de son ip pour faire un scoring basé sur la géolocalisation
    			$geo = new GeoIp();
    			
    			/**
    			 * @todo A Supprimer pour le passage en production
    			 */
    			$r_1 = rand(0,255);
    			$r_2 = rand(0,255);
    			$r_3 = rand(0,255);
    			$r_4 = rand(0,255);
    			
    			//génère une IP aléatoire afin d'éviter d'enregistrer seulement des IP correspondant au localhost
    			$geo->setIpadress($r_1.".".$r_2.".".$r_3.".".$r_4);
    			/**
    			 * FIN de la partie à supprimer
    			 */
    			
    			//Si on a réussit à le géolocaliser alors on ajoute les éléments de la géolocalisation au modèle IP
    			if($geo->geoCheckIP()){	//On fait appel à un site externe afin d'identifier la géolocalisation de l'ip
    				$ip->setPays($geo->pays);
    				$ip->setDepartement($geo->departement);
    				$ip->setVille($geo->ville);	 
    			}
    			
    			$utilisateur->setIp($ip);
    			$utilisateur->setIpUtilisateur($geo->getIpadress());

    			//on sauvegarde le nouvel utilisateur
    			$utilisateur->save();
    			
    			//on affiche la vue adaptée
    			return $this->render('MoteurUtilisateurBundle:Default:index.html.twig', array('message' => "Ajout réussi!"));
    		}
    	}
    	//on renvoie vers la vue du formulaire de création de l'utilisateur
        return $this->render('MoteurUtilisateurBundle:Utilisateur:creer.html.twig', array('form' => $form->createView()));
    }
    
    //FONCTIONNE - MODIF A FAIRE
    public function listerAction($page, $nombre)
    {
    	$utilisateurs = UtilisateurQuery::create()
    						->orderByNom(\Criteria::ASC)
    						->paginate($page, $nombre);
    	
    	$id = rand(0,80);
    	
    	$retours;
    	
    	foreach ($utilisateurs as $utilisateur){
    		
    		if($utilisateur->getId() < $id){
    			$inf = $utilisateur->getId();
    			$sup = $id;
    		}
    		else{
    			$inf = $id;
    			$sup = $utilisateur->getId();
    		}
    		
	    	$score = ProfilScoreUtilisateurQuery::create()
		    	->condition('cond1', 'profil_score_utilisateur.utilisateur_a_id = ?', $inf)
		    	->condition('cond2', 'profil_score_utilisateur.utilisateur_b_id = ?', $sup)
		    	->where(array('cond1', 'cond2'), 'and')
		    	->findOne();
	    	
	    	$retour[0] = $utilisateur;
	    	$retour[1] = $score->getScore()? $score->getScore() : 0;
	    	
	    	$retours[] = $retour;
    	}
    	//TO DO Ajouter le score entre les utilisateurs
    	
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:liste.html.twig', array('utilisateurs' => $retours, 'page' => $page, 'nombre' => $nombre));
    }
    
    /**
     * Affiche la page d'un utilisateur
     * @param unknown $nom
     * @param unknown $prenom
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficherAction($nom, $prenom)
    {
    	//Récupère l'utilisateur correspondant
    	$utilisateur = UtilisateurQuery::create()->filterByNom($nom)->filterByPrenom($prenom)->findOne();
    	
    	//Récupère les éléments de la géolocalisation basée sur l'IP de l'utilisateur
    	$ip = $utilisateur->getIp();
    	
    	//Récupère tous les id des centres d'intéret de l'utilisateur
    	$utilisateurInterets = $utilisateur->getUtilisateurInteretsJoinInteret();
    	
        //Contient les centres d'intérêt de l'utilisateur
    	$interets = array();
    	
    	//Crée un tableau contenant les centres d'intérêts
    	while($i = $utilisateurInterets->getNext()){	//Récupère l'id du ceontre d'intérêt suivant
    		$interet[0] = InteretQuery::create()->findOneById($i->getInteretId()); //Récupère le centre d'intérêt dans la base
    		$interet[1] = $i->getValeur();	//Indique à quel point le sujet intérèse l'utilisateur
    		$interets[] = $interet;
    	}
    	
    	//Renvoie la vue adaptée
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:afficher.html.twig', array('utilisateur' => $utilisateur, "ip" => $ip, "interets" => $interets));
    }
}
