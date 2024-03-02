<?php
require_once('include.php');

if (isset($_SESSION['id'])) {
  $var="Bonjour ♠♠♠ ".$_SESSION['pseudo'];
}else
{
  $var='Bonjour à tous';
}
 
?>

<!doctype html>
<html lang="en">
  <head>
       <title>Accueil</title>
     <?php
     require_once('_head/meta.php');
        require_once('_head/link.php');
           require_once('_head/script.php');
    ?>   
  </head>


  <body>
    
    <?php
     require_once('_menu/menu.php');
    ?>
    <div class="AccueilBody">
    <h1><?=$var?></h1>
    </div>
 <?php
     require_once('_footer/footer.php');
    ?>

  </body>
</html>