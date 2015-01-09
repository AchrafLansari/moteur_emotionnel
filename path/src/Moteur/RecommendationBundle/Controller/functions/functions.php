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
 
 function recommandation_description($description){
     
     
     $tab_description = tokenization($description," ,\n",0,1);
     array_map('strtolower',$tab_description);
     var_dump($tab_description);
     return $tab_description;
 }
 
 function recommandations_articles($tab_recommandations){
     
     
     
     $books = array();
     
     foreach ($tab_recommandations as $item=>$valeur ){
        
        $requete = $valeur;
        $indexation = new IndexationMot($requete, $path);
        $requete_id = RequeteQuery::create()->limit(1)->orderBy('requete_id', 'DESC')->findOne();
        $requete_id = $requete_id->getRequeteId();
        
        for($i=0;$i<5;$i++){
            array_push($books,$parsed_json['Books'][$i]); 
        }
          
     }
     return $books;
         
 }