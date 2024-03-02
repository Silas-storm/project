<?php
require_once('../include.php');


      $req=$DB->prepare('SELECT * FROM forum  ORDER BY  ordre ');
   
         $req->execute();
       $req_forum=$req->fetchAll();



?>
<!doctype html>
<html lang="en">
  <head>
       <title>Forum</title>
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
           <div class="forum_body">
                <h1 class="forum_h1">Forum</h1>
                <div class="forum_body_btn">
                    <a href="_forum/creer-topic.php" class="forum_btn_create"><i class="bi bi-clipboard2-plus btn_create"></i>Créér un topic</a>
                 </div>   

                <?php
                    foreach($req_forum as $rf){

                        $req =$DB-> prepare("SELECT COUNT(id) AS NbCommentaire FROM  topic  WHERE id_forum =? ");
                            $req->execute([$rf['id']]);
                            $req_nb_topic= $req->fetch();
                            $nb_topic= $req_nb_topic['NbCommentaire'];

                            if($nb_topic > 1)
                            {
                               $lib_nb_topic=" Il y a ".$nb_topic." topics ♠♠♠♠";
                            }else{

                              $lib_nb_topic=" Il y a ".$nb_topic." topic ♠♠♠♠";
                            }
                 ?>    
                 <a href="_forum/liste-topics.php?id=<?=$rf['id']?>" class="list_forum_link">  
                     <div class="list_cat_forum">
                          <div> <?=$rf['titre']?></div>
                          <div class="list_footer_forum">
                            <div><?= $lib_nb_topic?></div>
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