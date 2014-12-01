<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Formulaire Récuperation Profil </title>
    <?php 
     //setcookie ("loisirs", "", time() - 3600);
        function get_ip() {
        // IP si internet partagé
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
        }
        // IP derrière un proxy
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Sinon : IP normale
        else {
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        }
        }

        echo get_ip();
        echo $_SERVER['HTTP_REFERER'];
        echo "<br>".$_SERVER['REQUEST_URI']."<br>";
        echo time();
       if(isset($_COOKIE['loisirs'])){
           include_once 'connexion.php';
           $db = connect();
           //echo "afficher".$_POST['loisirs'];
           
           $db->exec("DROP VIEW IF EXISTS afficherview ");
           
           $selection= $db->prepare("CREATE VIEW  afficherview AS 
                         SELECT page 
                         FROM profil 
                         WHERE description = ? ");
         
           try {
                // On envois la requète
                $selection->execute(array($_POST['loisirs']));
                
                 $select = $db->query("SELECT * from afficherview ") ;
                 $select->setFetchMode(PDO::FETCH_OBJ);
                while( $enregistrement = $select->fetch() )
                {
                // Affichage d'un des champs
	echo   "<script type='text/javascript'>document.location.replace('".$enregistrement->page."');</script>";
                }
   
  // Traitement
  
            } catch( Exception $e ){
                echo 'Erreur de suppression : ', $e->getMessage();
       }
           
           
       }  
     
        if(isset($_POST['valider'])){ 
          
           setcookie("loisirs", $_POST['loisirs'], time()+3600); 
       }
    ?>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

   
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
         
        <form class="form-signin" role="form" action="" method="POST" >
        <h2 class="form-signin-heading">Veuillez remplir les informations suivantes :</h2>
        <input type="text" class="form-control" placeholder="Nom"  name="nom"required >
        <input type="text" class="form-control" placeholder="Prenom" required >
        <div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button"  id="dropdownMenu1" data-toggle="dropdown">
    Loisirs
    <span class="caret"></span>
  </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Musique</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Sport</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Danse</a></li>

     </ul>
        </div>
        <select name="loisirs" > Loisirs: 
        <option>  Musique </option>    
        <option>  Danse </option>    
        <option>  Sport </option>   
                
        </select>  
        
        <input type="email" class="form-control" placeholder="Email address" required autofocus>
        
        <button class="btn btn-lg btn-primary btn-block" name="valider" type="submit">Valider</button>
      </form>
        
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
  </body>
</html>