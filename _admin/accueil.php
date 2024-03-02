 <?php

  require_once('../include.php');

   if(!isset($_SESSION['admin']['ok']) || !$_SESSION['admin']['admin_accueil']){
      header('Location: //localhost/creation/index1.php');
      exit;
    }
   /*if(!in_array($_SESSION['role'] , [1, 2])){
    
    header('Location: //localhost/creation/index.php');
   exit;*/
   

?>


<!DOCTYPE html>
<html lang="en">
<head>

     <title>Dashboard</title>
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
                    <div>Mon espace admin</div>
                    <div><a href="_admin/niveau.php">Modifier le role d'un utilisateur</a></div>
                    <div><a href="_admin/gestion-role.php">Modifier les roles</a></div>
                </div>
              </div>
            </div>  

          <?php
             require_once('../_footer/footer.php');
            ?>
    
</body>
</html>