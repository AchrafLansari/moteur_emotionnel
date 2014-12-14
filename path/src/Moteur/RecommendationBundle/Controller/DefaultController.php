<?php

namespace Moteur\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\ProduitBundle\Model\MotQuery;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MoteurRecommendationBundle:Default:index.html.twig', array('name' => "nom"));
    }
    
    /**
     * @Route("/recherche/{requete}")
     */
    public function rechercheAction($requete)
    {
    	$mots;
    	$tok = strtok($requete, "+");
    	while($tok !== false){
    		$mots[] = $tok;
    		$tok = strtok("+");
    	}
    	
    	
    	/*
    	 * A CHANGER POUR METTRE L'UTILISATEUR CONNECTE
    	 */
    	$utilisateur = UtilisateurQuery::create()->findOne();
    	
    	
    	//Sauvegarde de la requete dans la base de donnée
    	$idMotsRequete = array();
    	$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne()->getRequeteId();    	
    	
    	foreach ($mots as $mot){
    		$m_Req = MotQuery::create()
    							->filterByMot($mot)
    							->findOne();
    							//->findOneOrCreate() -> Ne fonctionne pas, je ne sais pas pourquoi
    							//->setMot($mot)
    							//->save();
    							
    		if(!$m_Req){
    			$m_Req = new Mot();
    			$m_Req->setMot($mot)->save();
    		}
    		$ajoutRequete = new Requete();
    		$ajoutRequete->setUtilisateur($utilisateur);
    		$ajoutRequete->setRequeteId($requete_id + 1);
    		$ajoutRequete->setMotId($m_Req->getId());
    		$ajoutRequete->save();
    	}
    	
    	$score = new ProfilScoreRequeteUtilisateurProduit();
    	//TO DO mais pas prioritaire
    }
}
