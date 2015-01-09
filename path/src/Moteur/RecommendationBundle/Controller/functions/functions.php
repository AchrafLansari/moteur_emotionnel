<?php

use Doctrine\DBAL\Driver\PDOConnection;
function tokenization ($text,$delimiteurs,$nb_carac,$state)
        {
            $arrayElements = array();
           $text = strtolower($text);
            $words = strtok($text,$delimiteurs);
            
            
             while ($words !== false){
                    if($state == 0){
                        $words = trim(str_replace('&nbsp','',$words));
                        $cpt = strlen($words);
                     if($cpt >= $nb_carac){
                         
                             
                                $arrayElements[]=$words;
                             
                         
                         
                         }
                     
                    }else {
                        $arrayElements[]=$words;
                    }
                     $words = strtok($delimiteurs);
                 
             }
             return $arrayElements;
        }
 
 function recommandation_description($description){
     
     
     $tab_description = tokenization($description," ,\n",0,1);
     //array_map('strtolower',$tab_description);
     var_dump($tab_description);
     return $tab_description;
 }
 
 function recommandations_articles($tab_recommandations){
     
     return $books;
         
 }