<?php
require_once('include.php');
 
 if (isset($_SESSION['id'])) {
   header('Location:index.php');
   exit;
 }
 var_dump($_POST);

 if (!empty($_POST)) {
   extract($_POST);/*permet de déclarer les variables en quelque sorte sans avoir d'ajouter les post chaque fois*/

   if(isset($_POST['inscription']))
   {
    [$err_pseudo,  $err_mail , $err_password]= $_Inscription->verification_inscription($pseudo, $mail, $confmail, $password, $confpassword);

    
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
                  <?php if(isset($err_mail)){echo '<div>'.$err_mail.'</div>';} ?>
                  <label  class="form-label">Mail</label>
                     <input type="email" class="form-control" name="mail"  placeholder="Mail" value="<?php if(isset($mail)){echo $mail;} ?>">
               </div>
               <div class="mb-3">
                  <label  class="form-label">Confirmation du mail </label>
                     <input type="email" class="form-control" name="confmail"  placeholder="Confirmation  Mail" value="<?php if(isset($confmail)){echo $confmail;} ?>">
               </div>
                <div class="mb-3">
                   <?php if(isset($err_password)){echo '<div>'.$err_password.'</div>';} ?>
                  <label  class="form-label">Mot de passe</label>
                     <input type="password" class="form-control" name="password"  placeholder="Mot de passe"value="<?php if(isset($password)){echo $password;} ?>">
               </div>
                <div class="mb-3">
                  <label  class="form-label">Confirmation du mot de passe</label>
                     <input type="password" class="form-control" name="confpassword"  placeholder="Confirmation mot de passe" value="<?php if(isset($confpassword)){echo $confpassword;} ?>">
               </div>
                   <div class="mb-3">     
                       <button type="submit" class="btn btn-primary" name="inscription">Inscription</button>
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