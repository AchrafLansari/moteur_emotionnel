<?php

function connect(){
 try{
        $db = new PDO('mysql:host=localhost;dbname=adaptation','root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;
 }catch (Exception $ex){
   echo  $ex->getMessage();
 }
}

?>
