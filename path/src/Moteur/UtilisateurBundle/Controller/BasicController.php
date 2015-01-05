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
	
	
	//FONCTIONNE
    public function creerAction()
    {
    	$utilisateur = new Utilisateur();
    	$form = $this->createForm(new UtilisateurType(), $utilisateur);
    	
    	$request = $this->getRequest();
    	if('POST' == $request->getMethod()){
    		$form->handleRequest($request);
    		
    		if ($form->isValid()){
    			$ip = new Ip();
    			
    			$geo = new GeoIp();
    			if($geo->pays != null){
    				$ip->setPays($geo->pays);
    				$ip->setDepartement($geo->departement);
    				$ip->setVille($geo->ville);	 
    			}
    			
    			$utilisateur->setIp($ip);
    			$utilisateur->save();
    			
    			return $this->render('MoteurUtilisateurBundle:Default:index.html.twig', array('message' => "Ajout r�ussi!"));
    		}
    	}
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
    
    //FONCTIONNE
    public function afficherAction($nom, $prenom)
    {
    	$utilisateur = UtilisateurQuery::create()->filterByNom($nom)->filterByPrenom($prenom)->findOne();
    	$ip = $utilisateur->getIp();
    	
    	$utilisateurInterets = $utilisateur->getUtilisateurInteretsJoinInteret();
        
        $utilisateurDescription = $utilisateur->getDescription();
    	
    	$interets = array();
    	
    	while($i = $utilisateurInterets->getNext()){
    		$interet[0] = InteretQuery::create()->findOneById($i->getInteretId()); 
    		$interet[1] = $i->getValeur();
    		$interets[] = $interet;
    	}
    	
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:afficher.html.twig', array('utilisateur' => $utilisateur, "ip" => $ip, "interets" => $interets,"description" => $utilisateurDescription));
    }
}
