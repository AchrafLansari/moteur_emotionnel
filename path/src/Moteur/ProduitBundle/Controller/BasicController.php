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

class BasicController extends Controller
{
	/**
	 * @todo
	 * @param unknown $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function afficherAction($id)
    {
    	$produit = ProduitQuery::create()->findOneById($id);
    	
    	/*$id_utilisateur = 10;
    	
    	$connexion = \Propel::getConnection();
    	$sql;
    	$statement = $connexion->prepare($sql);
    	$statement->execute();
    	$resultats = $statement->fetchAll();
    	*/
    	$produits = array();
    	
        return $this->render('MoteurProduitBundle:Produit:afficher.html.twig', array('produit' => $produit, 'produits' => $produits));
    }
}
