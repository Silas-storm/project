<?php
require_once('../include.php');

  $get_id=(int) $_GET['id'];

    if($get_id <= 0)
    {
     header('Location: membres.php');
     exit;
    }
     
   if(isset($_SESSION['id']) && $get_id==$_SESSION['id'])
   {
      header('Location: profil.php');

   }

      $req=$DB->prepare("SELECT *  FROM utilisateur WHERE id = ?");
       $req->execute([$get_id]);
      
       $req_user=$req->fetch();

   $date =date_create($req_user['date_creation']);
    $date_inscription= date_format($date , 'd/m/Y ');

       $date =date_create($req_user['date_modification']);
    $date_modification= date_format($date , 'd/m/Y à H-i-s');

  switch ($req_user['role']) {
      
        case '0':
          $role="utilisateur";
          break;

         case '1':
          $role=" Super Admin ";
          break;

          case '2':
           $role="Admin";
          break;

            case '3':
           $role="Modérateur"; 
            break;

  }

  $chemin_avatar= null ;

  if(isset($req_user['avatar']) && $req_user['avatar'] <> null)
  {
    $chemin_avatar = 'public/avatar/'.$req_user['id'].'/'.$req_user['avatar'];
  }else
  {
     $chemin_avatar = 'public/avatar/default/default.svg ';
  }

?>



<!doctype html>
<html lang="en">
  <head>
       <title>Profil de <?=$req_user['pseudo']?></title>
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
        <div class="col-12 col-md-12 col-xl-12">
         <h1>Bonjour <?=$req_user['pseudo']?></h1>
            <div  class="prof__banniere__ava">       
            <div class="prof__banniere__ava__body">
               <img src="<?=$chemin_avatar?>" class="prof__banniere__ava__picture"/>
            </div>
             <div class="prof__banniere__ava__info">    
                    <div class="prof__banniere__info__1">
                     Date d'inscription : <?= $date_inscription ?>
                    </div> 
                    <div class="prof__banniere__info__2">
                     Date de derniere connexion: Le  <?= $date_modification?>
                    </div>

                    <div class="prof__banniere__info__3">
                     Role d'utilisateur: <?= $role ?>
                    </div>
              </div> 
              </div>
           </div>
        </div>
      </div> 
 <?php
     require_once('../_footer/footer.php');
    ?>
  </body>
</html>