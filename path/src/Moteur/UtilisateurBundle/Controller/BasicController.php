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
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;

class BasicController extends Controller
{
	/**
	 * Permet de cr�er un nouvel utilisateur
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function creerAction()
    {
    	$utilisateur = new Utilisateur();	//Le nouvel utilisateur
    	$form = $this->createForm(new UtilisateurType(), $utilisateur);	//Le formulaire associ�
    	
    	$request = $this->getRequest();		//Recup�re l'�tat de la requ�te
    	
    	//Si on acc�de � ce controleur via une requ�te POST alors c'est que l'on a soumis le formulaire
    	if('POST' == $request->getMethod()){
    		
    		//On r�cup�re le formulaire envoy�
    		$form->handleRequest($request);
    		
    		//S'il est valide alors on l'enregistres
    		if ($form->isValid()){
    			
    			//un nouvel objet IP
    			$ip = new Ip();
    			
    			//Permet de g�olocaliser un utilisateur en fonction de son ip pour faire un scoring bas� sur la g�olocalisation
    			$geo = new GeoIp();
    			
    			/**
    			 * @todo A Supprimer pour le passage en production
    			 */
    			$r_1 = rand(0,255);
    			$r_2 = rand(0,255);
    			$r_3 = rand(0,255);
    			$r_4 = rand(0,255);
    			
    			//g�n�re une IP al�atoire afin d'�viter d'enregistrer seulement des IP correspondant au localhost
    			$geo->setIpadress($r_1.".".$r_2.".".$r_3.".".$r_4);
    			/**
    			 * FIN de la partie � supprimer
    			 */
    			
    			//Si on a r�ussit � le g�olocaliser alors on ajoute les �l�ments de la g�olocalisation au mod�le IP
    			if($geo->geoCheckIP()){	//On fait appel � un site externe afin d'identifier la g�olocalisation de l'ip
    				$ip->setPays($geo->pays);
    				$ip->setDepartement($geo->departement);
    				$ip->setVille($geo->ville);	 
    			}
    			
    			$utilisateur->setIp($ip);
    			$utilisateur->setIpUtilisateur($geo->getIpadress());

    			//on sauvegarde le nouvel utilisateur
    			$utilisateur->save();
    			
    			//on affiche la vue adapt�e
    			return $this->render('MoteurUtilisateurBundle:Default:index.html.twig', array('message' => "Ajout r�ussi!"));
    		}
    	}
    	//on renvoie vers la vue du formulaire de cr�ation de l'utilisateur
        return $this->render('MoteurUtilisateurBundle:Utilisateur:creer.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * Affiche la page d'un utilisateur
     * @param unknown $nom
     * @param unknown $prenom
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficherAction($nom, $prenom)
    {
    	//R�cup�re l'utilisateur correspondant
    	$utilisateur = UtilisateurQuery::create()->filterByNom($nom)->filterByPrenom($prenom)->findOne();
    	
    	//R�cup�re les �l�ments de la g�olocalisation bas�e sur l'IP de l'utilisateur
    	$ip = $utilisateur->getIp();
    	
    	//R�cup�re tous les id des centres d'int�ret de l'utilisateur
    	$utilisateurInterets = $utilisateur->getUtilisateurInteretsJoinInteret();
    	
        //Contient les centres d'int�r�t de l'utilisateur
    	$interets = array();
    	
    	//Cr�e un tableau contenant les centres d'int�r�ts
    	while($i = $utilisateurInterets->getNext()){	//R�cup�re l'id du ceontre d'int�r�t suivant
    		$interet[0] = InteretQuery::create()->findOneById($i->getInteretId()); //R�cup�re le centre d'int�r�t dans la base
    		$interet[1] = $i->getValeur();	//Indique � quel point le sujet int�r�se l'utilisateur
    		$interets[] = $interet;
    	}
    	
    	//Renvoie la vue adapt�e
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:afficher.html.twig', array('utilisateur' => $utilisateur, "ip" => $ip, "interets" => $interets));
    }
    
    public function connecterAction(){
        
        $request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
        
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        //$session->start();
        
        $form = $this->createFormBuilder()
            ->add('login', 'text')
            ->add('mdp', 'password')
            ->add('Valider', 'submit')
            ->getForm();
    	 
    	$request = $this->getRequest();		//Recupère l'état de la requête
    	 //*/
    	//Si on accède à ce controleur via une requête POST alors c'est que l'on a soumis le formulaire
    	if('POST' == $request->getMethod()){
    	
    		//On récupère le formulaire envoyé
    		$form->handleRequest($request);
    	
    		//S'il est valide alors on l'enregistre
    		if ($form->isValid()){
                    
                    
                    
                   // if($_POST['login']=="admin" && $_POST['mdp']=="admin"){
                        
                        
                        $session->set('connexion','true');
                      
                       
                   // }
                    
                }
        }
        
        return $this->render('MoteurUtilisateurBundle:Utilisateur:connexion.html.twig', array('form' => $form->createView()));

    }
}
