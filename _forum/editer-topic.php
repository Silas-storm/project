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

  $get_id_topic=(int) $_GET['id'];

    if($get_id_topic <= 0){
      header('Location:forum.php');
      exit;
    }

    $req =$DB->prepare("SELECT t.* , f.titre AS titre_forum FROM topic t INNER JOIN forum f ON f.id = t.id_forum  WHERE t.id = ?");

    $req->execute([$get_id_topic]);

    $req_topic = $req->fetch();
  
  if(!isset($req_topic['id'])){
       header('Location:forum.php');
      exit;
  }
  
   if($req_topic['id_utilisateur'] <> $_SESSION['id'])
    {
      header('Location: topic.php?id='.$req_topic['id']);
        exit;
   }

 $req= $DB->prepare('SELECT id,titre FROM forum');
 $req->execute();
 $req_forum = $req->fetchAll();

 var_dump($_POST);
   $valid= true;

 if (!empty($_POST)) {
   extract($_POST);/*permet de déclarer les variables en quelque sorte sans avoir d'ajouter les post chaque fois*/

   if(isset($_POST['modification']))
   {
      
    $titre = (String) ucfirst(trim($titre));
    $categorie= (int) $categorie;
    $contenu = (String) trim($contenu);

      if (empty($titre)) {
      $err_titre="Ce champ ne peut etre vide "; 
      $valid=false;
    }
    elseif (grapheme_strlen($titre)< 5) {
       $err_titre="Le titre doit faire plus de cinq caracteres "; 
      $valid=false;
    }
    elseif (grapheme_strlen($titre)> 50) {
       $err_titre="Le titre doit faire moins de 51 caracteres(".grapheme_strlen($titre)."/51)"; 
      $valid=false;
    }
    
     $req= $DB->prepare('SELECT id,titre FROM forum WHERE id=?');
 $req->execute([$categorie]);
 $req_forum_verif = $req->fetch();

    if(!isset($req_forum_verif['id']))
    {
      $valid=false;
      $categorie=null;
      $err_cat="Cette catégorie n'existe pas";
    }

      if (empty($contenu)) {
      $err_contenu="Ce champ ne peut etre vide "; 
      $valid=false;
    }
    elseif (grapheme_strlen($contenu)< 5) {
       $err_contenu="Le contenu doit faire plus de cinq caracteres "; 
      $valid=false;
    }


      if($valid){
            $date_modification=date('Y-m-d H-i-s');

        $req= $DB->prepare("UPDATE  topic SET id_forum=?, titre=?, contenu=?, date_modification=? WHERE id=?");
        $req->execute([$req_forum_verif['id'], $titre, $contenu, $date_modification,  $req_topic['id']]);
        $req_forum_verif = $req->fetch();
        echo "ok ca a marché";
                   
         header('Location: topic.php?id='.$req_topic['id']);

        exit; 
      }
    
   } 
 }

?>

<!doctype html>
<html lang="en">
  <head>
        <title>Editer mon topic</title>
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
                <h1>Editer mon topic</h1>
                <form method="post">
                  <div class="mb-3">
                    <?php if(isset($err_titre)){echo '<div>'.$err_titre.'</div>';} ?>
                  <label  class="form-label">Titre</label>
                   <input type="text" class="form-control" name="titre"  placeholder="Titre" value="<?php if(isset($titre)){echo $titre;}else{ echo $req_topic['titre'] ;} ?>">
                 </div>
                 <div class="mb-3">
                  <?php if(isset($err_cat)){echo '<div>'.$err_cat.'</div>';} ?>
                  <label  class="form-label">Catégorie</label>
                  <select  class="form-control" name="categorie" >
                    <?php
                    if(isset($categorie)){
                    ?>
                        <option value="<?= $req_forum_verif['id']?>"><?= $req_forum_verif['titre']?></option>
                    <?php 
                    }elseif(isset($req_topic['id_forum'])){
                    ?>
                         <option value="<?= $req_topic['id_forum']?>"><?= $req_topic['titre_forum']?></option>
                    <?php 
                    }else{

                    ?>

                   <option hidden>Choisissez votre catégorie</option>

                   <?php                    
                     }
                    ?>
                     
                    <?php
                      foreach($req_forum as $rf){
                      ?>  
                        <option value="<?=$rf['id']?>"><?=$rf['titre']?></option>                    
 
                     <?php
                      } 

                    ?>
                      }
                  </select>
               </div>
               <div class="mb-3">
                <?php if(isset($err_contenu)){echo '<div>'.$err_contenu.'</div>';} ?>
                  <label  class="form-label">Contenu</label>
                  <textarea name="contenu" class="form-control" cols="30" rows="5" placeholder="Votre topic ...." value=""><?php if(isset($contenu)){echo $contenu;}else{ echo $req_topic['contenu'] ;} ?></textarea>
                
               </div>
              
                   <div class="mb-3">     
                       <button type="submit" class="btn btn-primary"  name="modification">Modifier mon topic</button>
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