<?php
require_once('include.php');

if (isset($_SESSION['id'])) {
   header('Location:index1.php');
   exit;
 }
 
 var_dump($_POST);

 if (!empty($_POST)) {
   extract($_POST);/*permet de déclarer les variables en quelque sorte*/
   if(isset($_POST['connexion']))
   {
    
  [$err_pseudo , $err_password] = $_Connexion->verification_connexion($pseudo,$password);
    
    
   }
 }

?>
<!doctype html>
<html lang="en">
  <head>
       <title>Accueil</title>
     <?php
     require_once('_head/meta.php');
        require_once('_head/link.php');
           require_once('_head/script.php');
    ?>   
  </head>


  <body>
    <?php
     require_once('_menu/menu.php');
    ?>
    <h1>Connexion</h1>
    <div class="container">
      <div class="row">
      <div class="col-3"></div>
        <div class="col-6">
                <h1>Bonjour</h1>
                <form method="post">
                  <div class="mb-3">
                    <?php if(isset($err_pseudo)){echo '<div>'.$err_pseudo.'</div>';} ?>
                  <label  class="form-label">Pseudo</label>
                   <input type="text" class="form-control" name="pseudo"  placeholder="Pseudo" value="<?php if(isset($pseudo)){echo $pseudo;} ?>">
                 </div>
                <div class="mb-3">
                   <?php if(isset($err_password)){echo '<div>'.$err_password.'</div>';} ?>
                  <label  class="form-label">Mot de passe</label>
                     <input type="password" class="form-control" name="password"  placeholder="Mot de passe"value="<?php if(isset($password)){echo $password;} ?>">
               </div>
                   <div class="mb-3">     
                       <button type="submit" class="btn btn-primary" name="connexion">Se connecter</button>
                   </div>
                </form>
            </div>

        </div>
      </div> 
 <?php
     require_once('_footer/footer.php');
    ?>
  </body>
</html>