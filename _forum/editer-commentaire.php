<?php
require_once('../include.php');
 
 if (!isset($_SESSION['id'])) {
   header('Location:index.php');
   exit;
 }

  if(!isset($_GET['id']))
  {
    header('Location:forum.php');
   exit;

  }

  $get_id_topic_commentaire=(int) $_GET['id'];

    if($get_id_topic_commentaire <= 0){
      header('Location:forum.php');
      exit;
    }

    $req =$DB->prepare("SELECT * FROM topic_commentaire   WHERE id = ?");

    $req->execute([$get_id_topic_commentaire]);

    $req_topic_commentaire = $req->fetch();
  
  if(!isset($req_topic_commentaire['id'])){
       header('Location:forum.php');
      exit;
  }
   if($req_topic_commentaire['id_utilisateur'] <> $_SESSION['id'])
    {
      header('Location: topic.php?id='.$req_topic_commentaire['id_topic']);
        exit;
   }

 var_dump($_POST);
   $valid= true;

 if (!empty($_POST)) {
   extract($_POST);/*permet de déclarer les variables en quelque sorte sans avoir d'ajouter les post chaque fois*/

   if(isset($_POST['modification']))
   {
      
    $commentaire = (String) trim($commentaire);

  
      if (empty($commentaire)) {
      $err_commentaire="Ce champ ne peut etre vide "; 
      $valid=false;
    }
    elseif (grapheme_strlen($commentaire)< 5) {
       $err_commentaire="Le commentaire doit faire plus de cinq caracteres "; 
      $valid=false;
    }


      if($valid){
            $date_modification=date('Y-m-d H-i-s');

        $req= $DB->prepare("UPDATE  topic_commentaire SET  contenu=?, date_modification=? WHERE id=?");
        $req->execute([ $commentaire,$date_modification,  $req_topic_commentaire['id']]);
        $req_forum_verif = $req->fetch();
                   
         header('Location: topic.php?id='.$req_topic_commentaire['id_topic']);
        exit; 
      }
    
   } 
 }

?>

<!doctype html>
<html lang="en">
  <head>
        <title>Editer mon commentaire</title>
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
                <h1>Editer mon commentaire</h1>
                <form method="post">
                  <div class="mb-3">
                <div class="mb-3">
                 <?php if(isset($err_commentaire)){echo '<div>'.$err_commentaire.'</div>';} ?>
                  <label  class="form-label">Commentaire</label>
                  <textarea name="commentaire" class="form-control" cols="30" rows="5" placeholder="Votre commentaire ...." value=""><?php if(isset($commentaire)){echo $commentaire;}else{ echo $req_topic_commentaire['contenu'] ;} ?></textarea>
                
               </div>
              
                   <div class="mb-3">     
                       <button type="submit" class="btn btn-primary"  name="modification">Modifier mon commentaire</button>
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