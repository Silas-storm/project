<?php
require_once('../include.php');

if(!isset($_SESSION['id']))
{
   header('Location://localhost/creation/index.php');
   exit;
}

      $req=$DB->prepare("SELECT *  FROM utilisateur WHERE id = ?");
       $req->execute([$_SESSION['id']]);
      
       $req_user=$req->fetch();

if (!empty($_POST)) {
   extract($_POST);/*permet de déclarer les variables en quelque sorte*/
   $valid=true;

   if(isset($_POST['form1']))
   {
     $mail = (String) trim($mail);
      
      if($mail == $_SESSION['mail']){
        $valid=false;
        $err_mail="On n'espere un autre email.";
      }elseif(!isset($mail)){
       $valid=false;
       $err_mail='Ce champ ne peut pas etre vide';
     }
     elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      $valid=false;
      $err_mail='Format invalide pour ce mail';
     }
     else{
        $req= $DB->prepare("SELECT id FROM utilisateur WHERE mail=?");
        $req->execute([$mail]);

        $req=$req->fetch();
        if(isset($req['id']))
        {
          $valid=false;
          $err_mail="Ce mail est déja pris";          
        }
     }

     if($valid){
      
      $req=$DB->prepare('UPDATE utilisateur SET mail=? WHERE id=?');
      $req->execute([$mail , $_SESSION['id']]);

      $_SESSION['mail']=$mail;
     header('Location: modifier-profil.php');
     exit;

     }
   }
   elseif(isset($_POST['form2']))
   {
     $oldpsd = (String) trim($oldpsd);
     $psd  = (String) trim($psd);
     $confpsd = (String) trim($confpsd);

          if(!isset($oldpsd))
         {
           $valid=false;
           $err_password="Ce champ ne peut etre vide";
         }
         else{
             $req = $DB-> prepare('SELECT mdp FROM utilisateur WHERE id=?');
             $req->execute([$_SESSION['id']]);
             $req=$req->fetch();

          if (isset($req['mdp'])) 
          {
                if (!password_verify($oldpsd, $req['mdp'])) {
                  
                     $valid=false;
                    $err_password="L' ancien mot de passe est incorrecte ";
                   }
                }else
                {
                  $valid=false;
                    $err_password="L ' ancien mot de passe est incorrecte ";
                   
                }
         }

         if($valid){

                   if (empty($psd)) {
              $err_password="Ce champ ne peut etre vide ";
              $valid=false; 
            }
            elseif($psd<>$confpsd){
              $err_password="Le mot de passe  est différent de la confirmation ";
              $valid=false;
            }elseif($psd==$oldpsd){
              $err_password="Le mot de passe  doit etre différent de l'ancien ";
              $valid=false;

            }

         }
          if($valid){

            $crytp_password=password_hash($psd, PASSWORD_ARGON2ID);
            $req=$DB->prepare('UPDATE utilisateur SET mdp =? WHERE id=?');
            $req->execute([$crytp_password , $_SESSION['id']]);
             header('Location: modifier-profil.php');
            exit;
         }

       }
     
    }

     if(!isset($mail)){

       $mail=$req_user['mail'];
     }


?>



<!doctype html>
<html lang="en">
  <head>
       <title>Modifier mon compte</title>
     <?php
     require_once('../_head/meta.php');
        require_once('../_head/link.php');
           require_once('../_head/script.php');
    ?>   
  </head>


  <body>
    <?php
     require_once('../_menu/menu.php');
    ?>
   
    <div class="container">
      <div class="row">
             <div class="col-3"></div>
              <div class="col-6">
                <br>
                <br>
                <br>
                  <h1>Modifier mes informations</h1>

                  <form  method="post">
                    <div class="mb-3">
                    <?php if(isset($err_mail)){echo '<div>'.$err_mail.'</div>';} ?>
                     <input class="form-control" type="email" name="mail"  value="<?= $mail?>" placeholder="Mail" />
                     </div>
                     <div class="mb-3">
                     <input class="btn btn-primary" type="submit" name="form1" value="Modifier">
                     </div>

                  </form>
         <br>
          <form  method="post">
            <div class="mb-3">
                 <?php if(isset($err_password)){echo '<div>'.$err_password.'</div>';} ?>
             <input class="form-control" type="password" name="oldpsd"  value="" placeholder="Mot de passe actuel" />
            </div>
            <div class="mb-3">
             <input class="form-control" type="password" name="psd"  value="" placeholder="Nouveau mot de passe actuel" />
             </div>
             <div class="mb-3">
             <input  class="form-control" type="password" name="confpsd"  value="" placeholder="Confirmation du mot de passe actuel" />
              </div>
              <div class="mb-3">
             <input type="submit" class="btn btn-primary" name="form2" value="Modifier">
             </div>
          </form>
          
        </div>
     </div>
  </div> 
 <?php
     require_once('../_footer/footer.php');
    ?>
  </body>
</html>