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
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;


class BasicController extends Controller
{
	
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
    
    
    /**
     * Connecte un administrateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
                    if($_POST['form']['login']=="admin" && $_POST['form']['mdp']=="admin"){
                        $session->set('connexion','true');
                    }
                    
                }
        }
        return $this->render('MoteurUtilisateurBundle:Utilisateur:connexion.html.twig', array('form' => $form->createView()));

    }
    
    
    /**
     * D�connecte un administrateur
     */
    public function deconnecterAction(){
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->remove('connexion');
        return $this->redirect($this->generateUrl('moteur_utilisateur_connecte'));
        
    }
    
    /**
     * G�n�re un nombre d'utilisateurs avec des centres d'int�r�ts et des interactions avec des produits existants
     *
     */
    public function addAction(){
    	$nombre = 1;
    	
    	if(!isset($_POST['nombre']))
    		return $this->redirect($this->generateUrl("moteur_utilisateur_liste", array('page'=>1, 'nombre'=>10))); //redirige l'administrateur vers la liste des utilisateurs
    	else
    		$nombre = $_POST['nombre'];
    	
    	//R�cup�re l'ensemble des centres d'interet enregistr�s
    	$interets = InteretQuery::create()->find();
    	 
    	//R�cup�re l'ensembles des produits enregistr�s
    	$produits = ProduitQuery::create()->find();
    	 
    	//R�cup�re 500 des mots index�s
    	$mots = MotQuery::create()->limit(500)->find();
    	 
    	//On g�n�re les utilisateurs
    	for($u=0; $u<$nombre; $u++){		//Permet de g�n�rer 10 utilisateurs
    
    
    
    		//On cr�e une adresse IP et on r�cup�re les informations sur celle-ci
    		//do{
    		$geo = new GeoIp();
    		//g�n�re une IP al�atoire afin d'�viter d'enregistrer seulement des IP correspondant au localhost
    		$geo->setIpadress(rand(0,255).".".rand(0,255).".".rand(0,255).".".rand(0,255));
    		//	$geo->geoCheckIP();
    		//}
    		//while ($geo->pays == null);
    
    		//Liste des g�olocalisations possibles
    		$geolocalisation = array(
    				array('pays' => 'France',
    						array('region' => 'Ile-de-france',
    								array('Paris', 'Saint-Denis', 'Montreuil')
    						),
    						array('region' => 'Rhone-Alpes',
    								array('Lyon', 'Saint-Etienne', 'Grenoble')
    						)
    				),
    				array('pays' => 'Etats-Unis',
    						array('region' => 'New-York',
    								array('Manhattan', 'Bronx', 'Brooklyn')
    						),
    						array('region' => 'Californie',
    								array('Los Angeles', 'San Diego', 'San Jose')
    						)
    				),
    				array('pays' => 'Allemagne',
    						array('region' => 'Bavière',
    								array('Munich', 'Augsbourg', 'Nuremberg')
    						),
    						array('region' => 'Saxe',
    								array('Dresde', 'Leipzig', 'Chemnitz')
    						)
    				)
    		);
    
    		//On d�termine une g�olocalisation al�atoirement parmi la liste des g�olocalisations possible
    		$pays_geo = $geolocalisation[rand(0,2)];
    		$region_geo = $pays_geo[rand(0,1)];
    		$ville_geo = $region_geo[0][rand(0,2)];
    		$geo->pays = $pays_geo['pays'];
    		$geo->departement = $region_geo['region'];
    		$geo->ville = $ville_geo;
    
    		//On cr�e un objet contenant les informations sur l'adresse ip
    		$ip = new Ip();
    		$ip->setPays($geo->pays);
    		$ip->setDepartement($geo->departement);
    		$ip->setVille($geo->ville);
    
    		//On cr�e un nouvel utilisateur
    		$utilisateur = new Utilisateur();
    		$utilisateur->setNom(substr(sha1(rand(0, 10)), 0, rand(5,15)));		//Le nom fera entre 5 et et 15 caract�res
    		$utilisateur->setPrenom(substr(sha1(rand(0, 10)), 0, rand(5,15)));	//Le pr�nom fera entre 5 et 15 caract�res
    		$utilisateur->setMail(
    				substr(sha1(rand(0, 10)), 0, rand(5,15))	//Premi�re partie de l'adresse mail
    				."@".										//@
    				substr(sha1(rand(0, 10)), 0, rand(5,10))	//Nom de domaine de l'adresse mail
    				.".".
    				substr(sha1(rand(0, 10)), 0, rand(2,4)));	//Extension du nom de domaine de l'adresse mail
    		$utilisateur->setAge(rand(10, 75));	//D�finit un age compris entre 10 et 75
    
    		//ville possible
    		$liste_ville = array('Paris', 'New York', 'Marseille', 'Lyon', 'Moscou', 'Londres');
    
    		$utilisateur->setVille($liste_ville[rand(0,5)]);	//ville choisie al�atoirement parmi les villes possibles
    
    		$description = "";
    		for($i=0; $i<rand(0,100); $i++){	//La description aura entre 0 et 100 mots
    			//Chaque mot comporte entre 2 et 10 caract�res
    			$description .= substr(sha1(rand(0,10000)), 0, rand(2, 10))." ";
    		}
    
    		$utilisateur->setDescription($description);
    		$utilisateur->setIpUtilisateur($geo->getIpadress());
    		$utilisateur->setIp($ip);
    
    		//On sauvegarde le nouvel utilisateur
    		$utilisateur->save();
    
    		/**
    		 * 				AJOUTE DES CENTRES D'INTERET A L'UTILISATEUR
    		*/
    
    
    		//On parcours la liste des centres d'int�r�ts
    		foreach ($interets as $interet){
    			if(rand(0, 10) < 3){	//On d�cide al�atoirement si on ajoute ce centre d'int�r�ts � l'utilisateur
    				$utilisateur_interet = new UtilisateurInteret();
    				$utilisateur_interet->setUtilisateur($utilisateur)
    				->setInteret($interet)
    				->setValeur(rand(0, 10))	//on choisit une valeur entre 0 et 10
    				->save();
    			}
    		}
    
    		/**
    		 * 			CREE DES INTERACTIONS ENTRE L'UTILISATEUR ET LES PRODUITS SAUVEGARDES
    		 *
    		 */
    
    		//On parcours la liste des produits
    		foreach ($produits as $produit){
    			if(rand(0, 10) < 5){	//On d�cide al�atoirement si l'utilisateur a interagit avec ce produit
    
    				$visites = rand(0,5);	//s'il a interagit alors on d�finit al�atoirement un nombre de visite de ce produit
    
    				$achat;
    
    				//s'il a visit� au moins une fois le produit alors il peut l'acheter/t�l�charger
    				if($visites>0)
    					$achat = (rand(0, 5)<2) ? true : false;	//On d�cide al�toirement s'il a achet�/t�l�charg� le produit
    				else
    					$achat = false;	//S'il n'a pas visit� le produit alors il ne l'a pas achet�/t�l�charg�
    
    				$note;
    
    				//S'il a achet�/t�l�charg� le produit alors il a pu le noter et on d�cide al�atoirement s'il a souhait� noter le produit
    				if(!$achat || rand(0,5)<2)
    					$note = null;	//si ce n'est pas le cas alors la note est null (NE PAS METTRE 0, �a fausserait le scoring)
    				else
    					$note = rand(0, 10);	//sinon on d�cide al�atoirement d'une note comprise entre 0 et 10
    
    				//On cr�e un mod�le recensant les interactions d'un utilisateur avec un produit
    				$utilisateur_produit = new UtilisateurProduit();
    
    				//On enregistre le mod�le ainsi cr�e dans la base de donn�es
    				$utilisateur_produit->setNombreVisite($visites)
    				->setAchat($achat)
    				->setNote($note)
    				->setUtilisateur($utilisateur)
    				->setProduitId($produit->getId())
    				->save();
    			}
    		}
    
    		/**
    		 * 				CREE DES REQUETES AYANT ETE EFFECTUEES PAR L'UTILISATEUR
    		 * 				->Simule des recherches de produits ayant �t� effectu�es par l'utilisateur
    		 *
    		 */
    
    		//R�alisation de chaque requ�te
    		for($i=0; $i < rand(0,20); $i++){	//On d�cide al�atoirement du nombre de requ�tes effectu�es par l'utilisateur
    			$requete = array();
    		  
    			foreach ($mots as $mot){
    				//On d�cide al�atoirement si le mot fait partie de la requ�te
    				if(rand(0,100)<2)
    					$requete[] = $mot->getId();
    			}
    		  
    			//Si la requete n'est pas vide alors on peut l'enregistrer
    			if(count($requete)>0){
    				//l'id de la derni�re requete effectu�e
    				$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne()->getRequeteId();
    
    				//On enregiste individuellement chaque mot dans la requ�te
    				foreach ($requete as $id_mot){
    					$ajoutRequete = new Requete();
    					$ajoutRequete->setUtilisateur($utilisateur);
    					$ajoutRequete->setRequeteId($requete_id + 1);
    					$ajoutRequete->setMotId($id_mot);
    					$ajoutRequete->save();
    				}
    			}
    		}
    	}
    	return $this->redirect($this->generateUrl("moteur_utilisateur_liste", array('page'=>1, 'nombre'=>10)));
    }
    
    
    /**
     * Créé un nouveau centre d'intérêt
     * @param unknown $nom
     */
    public function createInteretAction(){
    	if(isset($_POST['nom'])){
    		InteretQuery::create()
    			->filterByNom($_POST['nom'])
    			->findOneOrCreate();
    	}
    	return $this->redirect($this->generateUrl("moteur_interet_liste"));
    }
    
    /**
     * Permet à un utilisateur d'ajouter un centre d'intérêt
     * @param unknown $id_interet
     * 
     * @todo Inutilisé pour l'instant
     * 
     */
    public function addInteretAction($id_interet){
    
    	$session = new Session();
    	$session->start();
    
    	$interet = InteretQuery::create()->findById($id_interet);
    	$utilisateur = UtilisateurQuery::create()->findById($session->get('id'));
    	 
    	//rajoute un interet a un utilisateur
    	$utilisateur_interet = new UtilisateurInteret();
    	UtilisateurInteretQuery::create()
	    	->filterByUtilisateurId($utilisateur->getId())
	    	->filterByInteretId($interet->getId())
	    	->findOneOrCreate()
	    	->setUtilisateurId($utilisateur->getId())
	    	->setInteretId($interet->getId())
	    	->setValeur(1)
	    	->save();
    }
    
    /**
     * Renvoie la liste des centres d'intérèts existants
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listerAction(){
    	//Recherche la liste des centres d'int�r�ts class�s par ordre alphab�tique
    	
    	/**
    	 * @todo Modifier la requete pour afficher les centres d'intérêts n'étant pas associés à des utilisateurs
    	 */
    	$query = "SELECT nom, COUNT(utilisateur_id) as nombre FROM interet i JOIN utilisateur_interet u ON i.id = u.interet_id GROUP BY interet_id ORDER BY nom ASC";
    	$statement = \Propel::getConnection()->prepare($query);
    	$statement->execute();
    	$interets = $statement->fetchAll();
    	
    	//Retourne la liste dans la vue adapt�e
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:liste_interet.html.twig', array('interets' => $interets));
    }
    
    /**
     * Renvoie une liste d'utilisateurs enregistrés classés par ordre alphabétique de noms
     * @param unknown $nombre
     * @param unknown $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeutilisateursAction($nombre, $page){
    	$utilisateurs = UtilisateurQuery::create()->orderByNom(\Criteria::ASC)->paginate($page, $nombre);
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:liste.html.twig', array('utilisateurs' => $utilisateurs, 'page'=> $page, 'nombre'=>$nombre));
    }
}
