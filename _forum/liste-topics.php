<?php
require_once('../include.php');
      
   if(!isset($_GET['id']))
   {
     header('Location : forum.php');
     exit;
   }

      $get_id_forum = (int) $_GET['id'];

    if($get_id_forum <= 0)
      {
         header('Location: forum.php');
         exit;
      }
      $req=$DB->prepare('SELECT * FROM forum WHERE id = ? ');
   
         $req->execute([$get_id_forum]);

       $req_forum=$req->fetch();/*quand l'on veut récupérer une et une seule valeur*/



      $req=$DB->prepare('SELECT T.* ,U.pseudo FROM topic t INNER JOIN utilisateur U ON U.id=T.id_utilisateur 
        WHERE T.id_forum = ?  
        ORDER BY T.date_creation DESC ');
   
         $req->execute([$get_id_forum]);

       $req_liste_topics=$req->fetchAll();



?>
<!doctype html>
<html lang="en">
  <head>
       <title>Forum -<?=$req_forum ['titre']?></title>
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
              <div class="list_topic_body">
               <h1 class="list_topic_h1"><?=$req_forum ['titre']?></h1>

                    <?php
                        foreach($req_liste_topics as $rlt){
                            $req =$DB-> prepare("SELECT COUNT(id) AS NbCommentaire FROM  topic_commentaire  WHERE id_topic =? ");
                            $req->execute([$rlt['id']]);
                            $req_nb_commentaire= $req->fetch();
                            $nb_commentaire= $req_nb_commentaire['NbCommentaire'];
                     ?>    
                     <a href="_forum/topic.php?id=<?=$rlt['id']?>" class="list_topic_link">  
                        <div class="list_topic_sujet">
                            <div> <?=$rlt['titre']?></div>
                            <div class="list_topic_footer">
                                
                              <div>De: <?=$rlt['pseudo']?></div>
                              <div><i class="bi bi-chat-dots"></i> <?= $nb_commentaire?></div>
                            
                             <?php
                             if($rlt['date_creation'] < $rlt['date_modification']){
                             ?>
                                   <div>Modifié le : <?= date_format(date_create($rlt['date_modification']),'d/m/Y à H-i-s');?></div>
                              <?php
                                }else{
                              ?>
                                 <div>Le : <?= date_format(date_create($rlt['date_creation']),'d/m/Y à H-i-s');?></div>
                               <?php
                                }
                             ?>
                         </div>
                         </div>
                     </a>  
                      <?php    
                        }
                           
                    ?>
                </div>   
            </div>
         </div>
      </div> 
 <?php
     require_once('../_footer/footer.php');
    ?>
  </body>
</html>