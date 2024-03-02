<?php
require_once('../include.php');

if(!isset($_SESSION['id']))
{
   header('Location://localhost/creation/index.php');
   exit;
}

     
if (!empty($_POST)) {
   extract($_POST);/*permet de déclarer les variables en quelque sorte*/
   $valid=true;

  if(isset($_POST['modifier']))
  {
    if(isset($_FILES['file']) && !empty($_FILES['file']['name'])){
      $filename= $_FILES['file']['tmp_name'];

      $tailleMax="5242880";
      
      if($_FILES['file']['size'] <= $tailleMax)
      {
        $extensionsValides=array('jpg','png','jpeg');

        $extensionUpload = strtolower(substr(strrchr($_FILES['file']['name'], '.'),1));

             if(in_array($extensionUpload, $extensionsValides)){
         
                  $dossier='../public/avatar/'.$_SESSION['id'].'/';

                  if(!is_dir($dossier)){
                    mkdir($dossier);
                  }

                  $nom= md5(uniqid(rand(), true));

                  $chemin= $dossier . $nom . "." .$extensionUpload;

                  $resultat=move_uploaded_file($_FILES['file']['tmp_name'], $chemin); 

                    if($resultat){  

                                    if(is_readable($chemin)){
                                       $req = $DB->prepare("UPDATE utilisateur SET avatar=? WHERE id=?");
                                       $req->execute([($nom . "." .$extensionUpload), $_SESSION['id']]);

                                       header('Location:profil.php');
                                       exit;

                                       }
                                       else{
                                       $err_avatar = " Impossible d'optimiser votre fichier";
                                       }

                           }else{

                           $err_avatar = " Impossible d'inspecter votre fichier";
                           }

              }else{
                $err_avatar="l'extension de votre  n'est pas validé";
               }

        }else{
            $err_avatar="L'image doit faire 5mo au moins" ;
           }


    }else
    {
      $err_avatar= "Ceci n'est pas un fichier valide";    
    }
     
 }   
}


?>



<!doctype html>
<html lang="en">
  <head>
       <title>Modifier mon avatar</title>
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
    
                  <h1>Modifier mon avatar</h1>

                  <form  method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                    <?php if(isset($err_avatar)){echo '<div>'.$err_avatar.'</div>';} ?>
                     <input class="form-control"  type="file" name="file"/>
                     </div>
                     <div class="mb-3">
                     <input class="btn btn-primary" type="submit" name="modifier" value="Modifier">
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