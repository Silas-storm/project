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
        <div class="col-12">
         <h1>Bonjour <?=$req_user['pseudo']?></h1>
             <div  class="prof__banniere__ava">
               <div class="prof__banniere__ava__body">
                <img src="<?=$chemin_avatar?>" class="prof__banniere__ava__picture"/>
              </div>
              <div class="prof__banniere__ava__info">   
                <div class="prof__banniere__info__4">
                 Date d'inscription : <?= $date_inscription ?>
                </div> 
                <div class="prof__banniere__info__4">
                 Date de derniere connexion: Le  <?= $date_modification?>
                </div>
                <div class="prof__banniere__info__4">
                 Role: <?= $role ?>
                </div>
            <div>
                <a href="_profil/modifier-profil.php">Modifier mon compte</a>
            </div>

            <div>
            <a href="_profil/avatar.php">Modifier mon avatar</a>
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