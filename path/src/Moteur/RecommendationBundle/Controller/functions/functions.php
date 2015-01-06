<?php

function tokenization ($text,$delimiteurs,$nb_carac,$state)
        {
            $arrayElements = array();
            global $tab_mots_vides;
            $words = strtok($text,$delimiteurs);
            
            
             while ($words !== false){
                    if($state == 0){
                        $words = trim(str_replace('&nbsp','',$words));
                        $cpt = strlen($words);
                     if($cpt >= $nb_carac){
                         if (!array_key_exists($words,array_flip($tab_mots_vides)))
                             {
                                $arrayElements[]=$words;
                             }
                         
                         
                         }
                     
                    }else {
                        $arrayElements[]=$words;
                    }
                     $words = strtok($delimiteurs);
                 
             }
             return $arrayElements;
        }
 
 function recommandation_description($tab_livres,$description){
     
     
     $tab_description = tokenization($description," ,\n",0,1);
     $tab_recommandations = array();
     
     foreach ($tab_description as $item=>$valeur ){
         
         $val = utf8_decode($valeur);
         $val = ucfirst($val);
         
         for($i=0;$i<count($tab_livres);$i++){
            
          
          if($val==rtrim($tab_livres[$i])){
              
              array_push($tab_recommandations, $valeur);
          }
         }
     }
     return $tab_recommandations;
 }
 
 function recommandations_articles($tab_recommandations){
     
     
     $url = "http://it-ebooks-api.info/v1/search/";
     $books = array();
     
     foreach ($tab_recommandations as $item=>$valeur ){
        
        $json = file_get_contents($url.$valeur);
        $parsed_json = json_decode($json,true);
        
        for($i=0;$i<5;$i++){
            array_push($books,$parsed_json['Books'][$i]); 
        }
          
     }
     return $books;
         
 }