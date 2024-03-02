<?php
require_once('../include.php');

$req_sql="SELECT id, pseudo, avatar FROM utilisateur ";

if(isset($_SESSION['id']))
{
  $req_sql .="WHERE id <> ?";

}

      $req=$DB->prepare($req_sql);
      if(isset($_SESSION['id']))
      {

       $req->execute([$_SESSION['id']]);

      }
      else
      {
         $req->execute();
      }

       $req_membres=$req->fetchAll();



?>
<!doctype html>
<html lang="en">
  <head>
       <title>Membres</title>
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
      
         <h1>Membres</h1>
         
           
            <?php
            foreach($req_membres as $rm){
              
               $chemin_avatar= null ;

                  if(isset($rm['avatar']) && $rm['avatar']<> null)
                  {
                    $chemin_avatar = 'public/avatar/'.$rm['id'].'/'.$rm['avatar'];
                  }else
                  {
                     $chemin_avatar = 'public/avatar/default/default.svg';
                  }

         ?> 
         <div class="memberBody">                 
            <div class="member__card__avatar">
             <img src="<?=$chemin_avatar?>" class="member__card__picture" style="object-fit: cover; box-shadow: 2px 2px 15px rgba(5, 5, 5, .2); backdrop-filter: blur(15px)"/>
            </div>
            <div class="member__card__pseudo"> <?=$rm['pseudo']?></div>
            <div class="member__card__link"><a href="_profil/voir-profil.php?id=<?=$rm['id']?>" class="link">Voir profil</a></div>
              </div>
          <?php    
            }
               
        ?>
     

         
     
      </div> 
 <?php
     require_once('../_footer/footer.php');
    ?>
  </body>
</html>