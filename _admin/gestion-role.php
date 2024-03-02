 <?php

  require_once('../include.php');

   if(!isset($_SESSION['admin']['ok']) || !$_SESSION['admin']['admin_accueil']){
      header('Location: //localhost/creation/index.php');
      exit;
    }
                  $req= $DB->prepare("SELECT * FROM admin_role  WHERE role <> 1  ORDER BY ordre");
                    

                    $req->execute();

                    $req_list_role = $req->fetchAll();
                    $selrole = false;


                    if(isset($_GET['role'])){
                     $get_role = (int) $_GET['role'];

                      $req= $DB->prepare("SELECT * FROM admin_role  WHERE role <> 1 AND role = ? ");
                 

                    $req->execute([$get_role]);

                    $verif_gestion_role = $req->fetch();
                     
                      if($verif_gestion_role){
                          $selrole = true ;
                         }
                     
                    }

                    if($selrole){
                     $req= $DB->prepare(" SELECT * FROM admin_matrice WHERE role=? ");

                    $req->execute([$verif_gestion_role['role']]);

                    $req_list_gestion_role = $req->fetchAll();
                    }

     
 if (!empty($_POST)) {
            extract($_POST);/*permet de déclarer les variables en quelque sorte sans avoir d'ajouter les post chaque fois*/
            $valid= true;
            
         if(isset($_POST['valrole']))
          {
              
              $selrole= (int) $selrole;
              var_dump ($selrole);

              $req= $DB->prepare("SELECT * FROM admin_role  WHERE role <> 1 AND role = ?");
                   
                      
                    $req->execute([$selrole]);
                    $verif_role = $req->fetch();
                     var_dump($verif_role);
                         var_dump ($selrole);
                      exit;

               if(!$verif_role){
                $valid=false;
                $err_role= "Le role n'existe pas";
               }

               if($valid){
                 header("Location:/creation/_admin/gestion-role.php?role=".$selrole);
                 exit;  
               }

           }

     }  

?>


<!DOCTYPE html>
<html lang="en">
<head>

     <title>Gestion des roles</title>
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
                    <div>Gestion des roles</div>
                     <div>
                        <form method="post">
                              <select name="selrole" >
                                <option hidden>Selectionner votre role</option>
                                <?php
                                 foreach($req_list_role as $rlr){

                                ?>
                              
                                 <option value="<?$rltr['role']?>"><?=$rlr['libelle']?></option>
                              
                                 <?php
                                  }

                                 ?>
                        </select>
                        <button type="submit" name="valrole">Valider</button>
                     </form>
                  </div>
                  <?php
                     if($selrole){
                   ?> 
                     <div>
                       Affichage des droits du role :<?=$verif_gestion_role['libelle']?>
                     </div>

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