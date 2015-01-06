<?php

namespace Acme\UserBundle\Controller;

use Acme\UserBundle\Model\UtilisateurQuery;
use Acme\UserBundle\Model\Utilisateur;
use Acme\UserBundle\Form\Type\UtilisateurType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;


 
 
class DefaultController extends Controller
{   
   
     
   
    
    /**
     * @Route("/search/", name="_search")
     * @Template()
     */
    public function searchAction()
    {   
        $request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
        if($request->getMethod() == 'POST')
        {        
        $url = "http://it-ebooks-api.info/v1/search/";
        $json = file_get_contents($url.$_POST['tags']);
        $parsed_json = json_decode($json,true);
        
        
       if ($parsed_json['Total'] == "0"){
        
       return new Response('Nothing Found!');
        
        
       }else {
           
        $books = count($parsed_json['Books']);
        return $this->render('UserBundle:User:index.html.twig',array('nb_books' => $books,
                     'books' => $parsed_json['Books'],'flag'=>false));
           
           
       }
        
       }
    }
    
     
    public function indexAction()
    {   
        include_once 'functions/functions.php';
        
        $request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
        
        $path =  $this->get('kernel')->locateResource("@UserBundle/Data/data.txt");
        $data = tokenization(utf8_decode(file_get_contents($path)),"\n",0,1);        
        $url = "http://it-ebooks-api.info/v1/search/";
        $parsed_json['Total'] = "0";
        
        while ($parsed_json['Total'] == "0"){
        $query= rtrim($data[rand(0,count($data)-1)]);
       
        $json = file_get_contents($url.$query);
        $parsed_json = json_decode($json,true);
        
        }
        //echo $parsed_json['Total']/10;
        
        $books = count($parsed_json['Books']);
        
       
        $response = new Response();
        //$response->headers->clearCookie('cookie');
        //$response->send();

        $dejaVu = $request->cookies->has('cookie');
        
        //$request->cookies->get("mycookie"); 
        $session = new Session();
        
        if($dejaVu){
            
          if($session->get('nom')){
              $user = UtilisateurQuery::create()
                    ->filterByNom($session->get('nom'))
                    ->findOne();
              
            recommandation_description($data,$user->getDescription());
                      
          } 
         
          $flag = false;
           
        }else {
         $recommandation_books = null;
         if($request->getMethod() == 'POST')
        {        
        
        
        $session->start();

        
        // définit des messages dits « flash »
        $session->getFlashBag()->add('notice', 'Utilisateur Modifier');     
        $session->getFlashBag()->add('error', 'Pas d\'utilisateur');     
             
             
        $nom = $_POST['nom']; 
        $prenom = $_POST['prenom']; 
        $age = $_POST['age'];   
        $ville = $_POST['ville'];   
        $description = $_POST['description'];
        
        // définit et récupère des attributs de session
        $session->set('nom', $nom);
        

        
        
        $utilisateur = new Utilisateur();
        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setVille($ville);
        $utilisateur->setAge($age);
        $utilisateur->setDescription($description);
        $utilisateur->setIp($this->container->get('request')->getClientIp());
        $utilisateur->save();
        
        $geo = new GeoIp();
    		
    		if($geo->pays != null){
                    
	    		$ip = new Ip();
	    		$ip->setPays($geo->pays);
	    		$ip->setDepartement($geo->departement);
	    		$ip->setVille($geo->ville);
	    		$ip->save();
                }
        
        $session->set('id',$utilisateur->getId());// je pense qu'il faut le récuperer de la base ou de l'action précedante
        
        
        unset($_POST);
        
        $cookie = new Cookie('cookie', 'utilisateur',time() + 3600 * 24 * 7);
        $response->headers->setCookie($cookie);
        $response->send();
        
        
        }
        $flag=true;
        }
        
        //return $this->render('UserBundle:User:index.html.twig');
        return $this->render('UserBundle:User:index.html.twig',array('nb_books' => $books,
                     'books' => $parsed_json['Books'],'flag'=>$flag,'recommandation_book'=>$recommandation_books));
    }
    
    /**
     * @Route("/book/{id}", name="_book_show")
     * @Template()
     */
    public function bookAction($id)
    {
     $path = "http://it-ebooks-api.info/v1/book/".$id;
        
        
     $json = file_get_contents($path);
     $parsed_json = json_decode($json,true);
     
     
        
    //$book = 
        

    if ($parsed_json["Error"]!="0") {
        throw $this->createNotFoundException('No book found for id '.$id);
    }
  
    // faites quelque chose, comme passer l'objet $product à un template
    return $this->render('UserBundle:User:book.html.twig',array(
                     'book' => $parsed_json));
    }
    
    
    
    
    public function updateAction($id)
    {
    $user = UtilisateurQuery::create()
        ->findPk($id);

    if (!$user) {
        throw $this->createNotFoundException('No user found for id '.$id);
    }

    $user->setName('New user name!');
    $user->save();

    return $this->redirect($this->generateUrl('homepage'));
    }
    public function deleteAction($id)
    {
    $user = UtilisateurQuery::create()
        ->findPk($id);

    if (!$user) {
        throw $this->createNotFoundException('No user found for id '.$id);
    }

    $user->delete();
    $user->save();

    return $this->redirect($this->generateUrl('homepage'));
    }
    
    /**
     * @Route("/redirect", name="_redirect")
     * @Template()
     */
     public function redirectAction()
    {
         return $this->redirect($this->generateUrl('_user'));
    }
    
     /**
     * @Route("/user/identification", name="_user_identification")
     * @Template()
     */
    public function contactAction()
    {
        $form = $this->get('form.factory')->create(new UtilisateurType());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->getFlashBag()->set('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_user'));
            }
        }

        return array('form' => $form->createView());
    }
    
}
