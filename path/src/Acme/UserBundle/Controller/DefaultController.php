<?php

namespace Acme\UserBundle\Controller;

use Acme\UserBundle\Model\UtilisateurQuery;
use Acme\UserBundle\Form\Type\UtilisateurType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{   
    
     
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
    return new Response('Goodbye!');
    }
    
     
    public function indexAction()
    {   
        $path = "http://it-ebooks-api.info/v1/search/";
        $query= "php%20mysql";
        
        $json = file_get_contents($path.$query);
        
        $parsed_json = json_decode($json,true);
        
        //var_dump($parsed_json) ;
        
        //echo $parsed_json['Total']/10;
        
        
        $books = count($parsed_json['Books']);
        
        
        
        //return $this->render('UserBundle:User:index.html.twig');
        return $this->render('UserBundle:User:index.html.twig',array('nb_books' => $books,
                     'books' => $parsed_json['Books'],'flag'=>false));
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
