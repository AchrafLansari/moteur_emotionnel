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

 
 
class DefaultController extends Controller
{   
   //$_SERVER['HTTP_USER_AGENT'] Navigateur de l'utilisateur
     
    public function helloAction()
    {
    return new Response('Hello world!');
    }
    
    /**
     * @Route("/user/goodbye", name="_user")
     * @Template()
     */
    public function goodbyeAction()
    {   
        $request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
        
        if($request->getMethod() == 'POST')
        {        

        $nom = $_POST['nom']; 
        $prenom = $_POST['prenom']; 
        $age = $_POST['age'];   
        $ville = $_POST['ville'];   
        $description = $_POST['description'];
        
        $utilisateur = new Utilisateur();
        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setVille($ville);
        $utilisateur->setAge($age);
        $utilisateur->setDescription($description);
        $utilisateur->setIp($this->container->get('request')->getClientIp());
        
        $utilisateur->save();
        
        echo 'Utilisateur Ajouté';
        }
        
    return new Response('Goodbye!');
    }
    
     
    public function indexAction()
    {   
        
        $request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
        $path = "http://it-ebooks-api.info/v1/search/";
        $query= "php%20mysql";
        
        $json = file_get_contents($path.$query);
        $parsed_json = json_decode($json,true);
        
        //echo $parsed_json['Total']/10;
        
        $books = count($parsed_json['Books']);
        
       
        $response = new Response();
        

        $dejaVu = $request->cookies->has("cookie");
        
        //$request->cookies->get("mycookie");    
        
        
        $response->headers->clearCookie('cookie');
        $response->send();
        
        if($dejaVu){
            
          $flag = false;
           
        }else {
        
        /*$cookie = new Cookie('cookie', 'contentOfMyCookie',time() + 3600 * 24 * 7);
        $response->headers->setCookie($cookie);
        $response->headers->clearCookie('cookie');
        $response->send();*/
        
            
            
            $flag=true;
            
            
        }
        
        //return $this->render('UserBundle:User:index.html.twig');
        return $this->render('UserBundle:User:index.html.twig',array('nb_books' => $books,
                     'books' => $parsed_json['Books'],'flag'=>$flag));
    }
    /**
     * @Route("/show/{id}", name="_user_show")
     * @Template()
     */
    public function showAction($id)
    {
    $user = UtilisateurQuery::create()
        ->findPk($id);

    if (!$user) {
        throw $this->createNotFoundException('No user found for id '.$id);
    }

    // faites quelque chose, comme passer l'objet $product à un template
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
