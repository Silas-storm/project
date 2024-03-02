<?php
require_once('../include.php');
      
   if(!isset($_GET['id']))
   {
     header('Location : forum.php');
     exit;
   }

      $get_id_topic = (int) $_GET['id'];

    if($get_id_topic <= 0)
      {
         header('Location: forum.php');
         exit;
      }
      
      $req=$DB->prepare('SELECT t.*, u.pseudo , f.titre AS titre_forum FROM topic t INNER JOIN utilisateur u ON u.id=t.id_utilisateur INNER JOIN forum f ON f.id=t.id_forum WHERE t.id = ?  ORDER BY t.date_creation DESC ');
   
         $req->execute([$get_id_topic]);

       $req_topic=$req->fetch();

       if(!isset($req_topic['id']))
       {
         header('Location: forum.php');
         exit; 
       }


       $req =$DB->prepare("SELECT tc.*, u.pseudo FROM topic_commentaire tc INNER JOIN utilisateur u ON u.id= tc.id_utilisateur WHERE tc.id_topic = ? ORDER BY tc.date_creation DESC");

       $req->execute([$req_topic['id']]);
       
      $req_topic_commentaires= $req->fetchAll();
       


 if (!empty($_POST)) {
               extract($_POST);/*permet de déclarer les variables en quelque sorte sans avoir d'ajouter les post chaque fois*/
               $valid= true;

               if(isset($_POST['poster']))
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

                if($valid && isset($_SESSION['id']) )
                {
                    $date_creation=date('Y-m-d H-i-s');

                    $req= $DB->prepare("INSERT INTO topic_commentaire (id_topic , id_utilisateur, contenu, date_creation, date_modification) VALUES (?,?,?,?,?)");
                    $req->execute([$req_topic['id'], $_SESSION['id'], $commentaire, $date_creation, $date_creation]);

                    header('Location: topic.php?id='.$req_topic['id']);
                    exit;
                }
              }
              elseif(isset($_POST['sup_com']))
              {
                            $id_comm= (int) $id_com;


                        if($id_com<=0){
                           
                           $valid=false;
                           $err_commentaire="Impossible de supprimer ce commentaire";

                        }else{
                            $req= $DB->prepare("SELECT id FROM topic_commentaire WHERE id=? AND id_utilisateur=?");
                            $req->execute([$id_com, $_SESSION['id']]);
                            $req_verif_com = $req->fetch();

                            if(!isset($req_verif_com['id'])){
                              $valid=false;
                             $err_commentaire="Impossible de supprimer ce commentaire";
                            }

                        }
                               if($valid && isset($_SESSION['id']))
                               {
                                $req= $DB->prepare("DELETE FROM topic_commentaire WHERE id = ?");
                                $req->execute([$req_verif_com['id']]);

                                header('Location: topic.php?id='.$req_topic['id']);
                                 exit;

                               }

              }elseif(isset($_POST['sup_topic']))
                {
                        if($_SESSION['id'] <> $req_topic['id_utilisateur']){
                          
                          $valid = false;
                          $err_pseudo='Impossible de supprimer ce topic';

                        }
 
                           if($valid && isset($_SESSION['id']))
                           {

                            $req= $DB->prepare("DELETE FROM topic_commentaire WHERE id_topic = ?");
                            $req->execute([$req_topic['id']]);

                            $req= $DB->prepare("DELETE FROM topic WHERE id = ?");
                            $req->execute([$req_topic['id']]);

                            header('Location: forum.php');
                             exit;

                           }
                }
            } 
?>
<!doctype html>
<html lang="en">
  <head>
       <title><?=$req_topic ['titre']?></title>
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
        <div class="col-2"></div>
        <div class="col-8">
            <div class="topic-body">
                 <h1 class="topic_body_h1"><?=$req_topic ['titre']?></h1>
                
                        <?php if(isset($err_topic)){echo '<div>'.$err_topic.'</div>';} ?>
                          <?php
                           if(isset($_SESSION['id']) && $_SESSION['id'] == $req_topic['id_utilisateur']){
                         ?>
                     <div class="topic_body_action_btn">
                          <div>
                          <form action="" method="post">
                             <button type="submit" name="sup_topic" class="topic_action_btn"><i class="bi bi-trash"></i>Supprimer mon topic</button>  
                          </form>
                        </div>
                        <div><a href="_forum/editer-topic.php?id=<?=$req_topic['id']?>" class="topic_action_btn"><i class="bi bi-pencil-fill"></i>Editer mon topic</a></div>

                    </div>
                        <?php
                         }
                        ?>
                        <div class="topic_body_contenu"> <?=nl2br($req_topic['contenu'])?></div>
                        <div class="topic_footer">
                            <div>De :<?=$req_topic['pseudo']?></div>
                            <div class="topic_footer_cat">Catégorie: <?= $req_topic['titre_forum']?></div>
                            <br>
                             <div>Le : <?= date_format(date_create($req_topic['date_creation']),'d/m/Y à H-i-s');?></div>
                             <?php
                             if($req_topic['date_creation'] < $req_topic['date_modification']){
                             ?>
                                   <div>Modifié le : <?= date_format(date_create($req_topic['date_modification']),'d/m/Y à H-i-s');?></div>
                              <?php
                                }
                             ?>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="container">
             <div class="row">
               <div class="col-2"></div>
                <div class="col-8">
                 <div class="topic-body">
                       
                   <h2 class="topic_body_h1">Commentaires</h2>
                       <div class="topic_body_zone_commentaire">
                         <form method="post">
                            <div class="mb-3">
                            <?php 
                            if(isset($err_commentaire)){
                             ?>   
                            <div class="topic_zone_commentaire_erreur"><?=$err_commentaire?></div>
                           <?php      
                            }
                          ?>
                              <label  class="form-label">Votre commentaire</label>
                              <textarea name="commentaire" class="topic_com_body_textarea" cols="30" rows="5" placeholder="Votre commentaire ...." value="<?php if(isset($commentaire)){echo $commentaire;} ?>"></textarea>         
                           </div>
                            <div class="topic_com_footer_btn">     
                             <button type="submit" class="topic_action_btn"  name="poster"><i class="bi bi-send-check-fill"></i>Poster </button>
                           </div>
                         </form>
                        </div> 
                    </div>
                </div>
               </div>
             </div>

              <div class="container">
                 <div class="row">  
                               <?php 
                                foreach($req_topic_commentaires as $rtc){

                                ?>  
                          <div class="col-2"></div>
                          <div class="col-8">
                              <div class="topic_commentaire_body">  
                                 <div class="topic_commentaire_titre">
                                 <div>De :<?=$rtc['pseudo']?> </div>
                                 </div>
                                 <?php
                                  if(isset($_SESSION['id']) && $_SESSION['id'] == $rtc['id_utilisateur']){

                                 ?>
                         <div class="topic_body_action_btn">   
                                <div>
                                  <form action="" method="post">
                                     
                                       <button type="submit" name="sup_com" class="topic_action_btn"><i class="bi bi-trash"></i>Supprimer mon commentaire</button>
                                       <input type="hidden" name="id_com" value="<?= $rtc['id']?>">
                                      
                                  </form>

                                </div>
                                <div> <a href="_forum/editer-commentaire.php?id=<?=$rtc['id']?>" class="topic_action_btn"><i class="bi bi-pencil-fill"></i>Editer mon commentaire</a></div>
                         </div>
                            
                                 <?php 
                                  }
                                  
                                 ?>
                                <div class="topic_body_contenu"> <?=nl2br($rtc['contenu'])?></div>
                                 <div class="topic_footer">

                                 <div>Le : <?= date_format(date_create($rtc['date_creation']),'d/m/Y à H-i-s');?></div>
                                 <?php
                                 if($rtc['date_creation'] < $rtc['date_modification']){
                                 ?>
                                       <div>Modifié le : <?= date_format(date_create($rtc['date_modification']),'d/m/Y à H-i-s');?></div>
                                  <?php
                                    }
                                 ?>
                              </div>
                            </div>
                         </div>
                <div class="col-2"></div>
                <?php
                      }
                ?>
         </div>
        </div>
      </div> 
 <?php
     require_once('../_footer/footer.php');
    ?>
  </body>
</html>