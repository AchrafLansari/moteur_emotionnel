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

/**
 * 
 * @todo ajouter les use!
 */

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
    	//Rï¿½cupï¿½re l'utilisateur correspondant
    	$utilisateur = UtilisateurQuery::create()->filterByNom($nom)->filterByPrenom($prenom)->findOne();
    	
    	//Rï¿½cupï¿½re les ï¿½lï¿½ments de la gï¿½olocalisation basï¿½e sur l'IP de l'utilisateur
    	$ip = $utilisateur->getIp();
    	
    	//Rï¿½cupï¿½re tous les id des centres d'intï¿½ret de l'utilisateur
    	$utilisateurInterets = $utilisateur->getUtilisateurInteretsJoinInteret();
    	
        //Contient les centres d'intï¿½rï¿½t de l'utilisateur
    	$interets = array();
    	
    	//Crï¿½e un tableau contenant les centres d'intï¿½rï¿½ts
    	while($i = $utilisateurInterets->getNext()){	//Rï¿½cupï¿½re l'id du ceontre d'intï¿½rï¿½t suivant
    		$interet[0] = InteretQuery::create()->findOneById($i->getInteretId()); //Rï¿½cupï¿½re le centre d'intï¿½rï¿½t dans la base
    		$interet[1] = $i->getValeur();	//Indique ï¿½ quel point le sujet intï¿½rï¿½se l'utilisateur
    		$interets[] = $interet;
    	}
    	
    	//Renvoie la vue adaptï¿½e
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
    	 
    	$request = $this->getRequest();		//RecupÃ¨re l'Ã©tat de la requÃªte
    	 //*/
    	//Si on accÃ¨de Ã  ce controleur via une requÃªte POST alors c'est que l'on a soumis le formulaire
    	if('POST' == $request->getMethod()){
    	
    		//On rÃ©cupÃ¨re le formulaire envoyÃ©
    		$form->handleRequest($request);
    	
    		//S'il est valide alors on l'enregistre
    		if ($form->isValid()){
                    if($_POST['form']['login']=="admin" && $_POST['form']['mdp']=="admin"){
                    	echo "ok";
                        $session->set('connexion','true');
                    }
                    
                }
        }
        return $this->render('MoteurUtilisateurBundle:Utilisateur:connexion.html.twig', array('form' => $form->createView()));

    }
    
    
    /**
     * Déconnecte un administrateur
     */
    public function deconnecterAction(){
    	/**
    	 * @todo Déconnecter l'administrateur
    	 * Renvoyer un message/une vue
    	 */
    }
    
    
    public function indexAction()
    {
    	return $this->render('MoteurUtilisateurBundle:Default:index.html.twig', array('name' => "nom"));
    }
    
    /**
     * Génère un nombre d'utilisateurs avec des centres d'intérêts et des interactions avec des produits existants
     *
     * @todo afficher une vue en retour
     * @param unknown $nombre Le nombre de nouveaux utilisateurs à créer
     * @return NULL
     */
    public function addAction($nombre){
    	//Rï¿½cupï¿½re l'ensemble des centres d'interet enregistrï¿½s
    	$interets = InteretQuery::create()->find();
    	 
    	//Rï¿½cupï¿½re l'ensembles des produits enregistrï¿½s
    	$produits = ProduitQuery::create()->find();
    	 
    	//Rï¿½cupï¿½re 500 des mots indexï¿½s
    	$mots = MotQuery::create()->limit(500)->find();
    	 
    	//On gï¿½nï¿½re les utilisateurs
    	for($u=0; $u<$nombre; $u++){		//Permet de gï¿½nï¿½rer 10 utilisateurs
    
    
    
    		//On crï¿½e une adresse IP et on rï¿½cupï¿½re les informations sur celle-ci
    		//do{
    		$geo = new GeoIp();
    		//gï¿½nï¿½re une IP alï¿½atoire afin d'ï¿½viter d'enregistrer seulement des IP correspondant au localhost
    		$geo->setIpadress(rand(0,255).".".rand(0,255).".".rand(0,255).".".rand(0,255));
    		//	$geo->geoCheckIP();
    		//}
    		//while ($geo->pays == null);
    
    		//Liste des gï¿½olocalisations possibles
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
    						array('region' => 'Baviï¿½re',
    								array('Munich', 'Augsbourg', 'Nuremberg')
    						),
    						array('region' => 'Saxe',
    								array('Dresde', 'Leipzig', 'Chemnitz')
    						)
    				)
    		);
    
    		//On dï¿½termine une gï¿½olocalisation alï¿½atoirement parmi la liste des gï¿½olocalisations possible
    		$pays_geo = $geolocalisation[rand(0,2)];
    		$region_geo = $pays_geo[rand(0,1)];
    		$ville_geo = $region_geo[0][rand(0,2)];
    		$geo->pays = $pays_geo['pays'];
    		$geo->departement = $region_geo['region'];
    		$geo->ville = $ville_geo;
    
    		//On crï¿½e un objet contenant les informations sur l'adresse ip
    		$ip = new Ip();
    		$ip->setPays($geo->pays);
    		$ip->setDepartement($geo->departement);
    		$ip->setVille($geo->ville);
    
    		//On crï¿½e un nouvel utilisateur
    		$utilisateur = new Utilisateur();
    		$utilisateur->setNom(substr(sha1(rand(0, 10)), 0, rand(5,15)));		//Le nom fera entre 5 et et 15 caractï¿½res
    		$utilisateur->setPrenom(substr(sha1(rand(0, 10)), 0, rand(5,15)));	//Le prï¿½nom fera entre 5 et 15 caractï¿½res
    		$utilisateur->setMail(
    				substr(sha1(rand(0, 10)), 0, rand(5,15))	//Premiï¿½re partie de l'adresse mail
    				."@".										//@
    				substr(sha1(rand(0, 10)), 0, rand(5,10))	//Nom de domaine de l'adresse mail
    				.".".
    				substr(sha1(rand(0, 10)), 0, rand(2,4)));	//Extension du nom de domaine de l'adresse mail
    		$utilisateur->setAge(rand(10, 75));	//Dï¿½finit un age compris entre 10 et 75
    
    		//ville possible
    		$liste_ville = array('Paris', 'New York', 'Marseille', 'Lyon', 'Moscou', 'Londres');
    
    		$utilisateur->setVille($liste_ville[rand(0,5)]);	//ville choisie alï¿½atoirement parmi les villes possibles
    
    		$description = "";
    		for($i=0; $i<rand(0,100); $i++){	//La description aura entre 0 et 100 mots
    			//Chaque mot comporte entre 2 et 10 caractï¿½res
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
    
    
    		//On parcours la liste des centres d'intï¿½rï¿½ts
    		foreach ($interets as $interet){
    			if(rand(0, 10) < 3){	//On dï¿½cide alï¿½atoirement si on ajoute ce centre d'intï¿½rï¿½ts ï¿½ l'utilisateur
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
    			if(rand(0, 10) < 5){	//On dï¿½cide alï¿½atoirement si l'utilisateur a interagit avec ce produit
    
    				$visites = rand(0,5);	//s'il a interagit alors on dï¿½finit alï¿½atoirement un nombre de visite de ce produit
    
    				$achat;
    
    				//s'il a visitï¿½ au moins une fois le produit alors il peut l'acheter/tï¿½lï¿½charger
    				if($visites>0)
    					$achat = (rand(0, 5)<2) ? true : false;	//On dï¿½cide alï¿½toirement s'il a achetï¿½/tï¿½lï¿½chargï¿½ le produit
    				else
    					$achat = false;	//S'il n'a pas visitï¿½ le produit alors il ne l'a pas achetï¿½/tï¿½lï¿½chargï¿½
    
    				$note;
    
    				//S'il a achetï¿½/tï¿½lï¿½chargï¿½ le produit alors il a pu le noter et on dï¿½cide alï¿½atoirement s'il a souhaitï¿½ noter le produit
    				if(!$achat || rand(0,5)<2)
    					$note = null;	//si ce n'est pas le cas alors la note est null (NE PAS METTRE 0, ï¿½a fausserait le scoring)
    				else
    					$note = rand(0, 10);	//sinon on dï¿½cide alï¿½atoirement d'une note comprise entre 0 et 10
    
    				//On crï¿½e un modï¿½le recensant les interactions d'un utilisateur avec un produit
    				$utilisateur_produit = new UtilisateurProduit();
    
    				//On enregistre le modï¿½le ainsi crï¿½e dans la base de donnï¿½es
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
    		 * 				->Simule des recherches de produits ayant ï¿½tï¿½ effectuï¿½es par l'utilisateur
    		 *
    		 */
    
    		//Rï¿½alisation de chaque requï¿½te
    		for($i=0; $i < rand(0,20); $i++){	//On dï¿½cide alï¿½atoirement du nombre de requï¿½tes effectuï¿½es par l'utilisateur
    			$requete = array();
    		  
    			foreach ($mots as $mot){
    				//On dï¿½cide alï¿½atoirement si le mot fait partie de la requï¿½te
    				if(rand(0,100)<2)
    					$requete[] = $mot->getId();
    			}
    		  
    			//Si la requete n'est pas vide alors on peut l'enregistrer
    			if(count($requete)>0){
    				//l'id de la derniï¿½re requete effectuï¿½e
    				$requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne()->getRequeteId();
    
    				//On enregiste individuellement chaque mot dans la requï¿½te
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
    	return null;
    }
    
    
    /**
     * Créer un nouveau centre d'intérêt
     * @todo il faut renvoyer une vue ou un message
     * @param unknown $nom
     */
    public function createInteretAction($nom){
    	InteretQuery::create()->filterByNom($nom)->findOneOrCreate()->setNom($nom)->save();
    }
    
    /**
     * Permet à un utilisateur d'ajouter un centre d'intérêt
     * @param unknown $id_interet
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
     * Renvoie la liste des centres d'intï¿½rï¿½ts existants
     * @todo ajouter le nombre d'utilisateurs
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listerAction(){
    	//Recherche la liste des centres d'intï¿½rï¿½ts classï¿½s par ordre alphabï¿½tique
    	$interets = InteretQuery::create()->orderByNom(\Criteria::ASC)->find();
    	 
    	//Retourne la liste dans la vue adaptï¿½e
    	return $this->render('MoteurUtilisateurBundle:Utilisateur:liste_interet.html.twig', array('interets' => $interets));
    }
}
