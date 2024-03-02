 <?php

  require_once('../include.php');

  if(!isset($_SESSION['admin']['ok']) || !$_SESSION['admin']['admin_changement_role']){
    
    header('Location: //localhost/creation/index1.php');
   exit;
   }
   /*if(!in_array($_SESSION['role'] , [1, 2])){
    
    header('Location: //localhost/creation/index.php');
   exit;
   }*/
     
                    $req= $DB->prepare("SELECT u.* , ar.libelle FROM utilisateur u LEFT JOIN  admin_role ar ON ar.role=u.role WHERE u.id<>? AND u.role <> 1 AND ar.ordre > ? ORDER BY ar.ordre,u.pseudo");
                    /*ceci m'a permis de faire une jointure genre relier deux tables différentes en joingnant deux de leurs composantes respectives*/

                    $req->execute([$_SESSION['id'],$_SESSION['role_ordre']]);

                    $req_list_user = $req->fetchAll();


                    $req =$DB-> prepare("SELECT * FROM admin_role WHERE role <> 1 AND ordre > ?");
                      $req->execute([$_SESSION['role_ordre']]);
                    $req_list_role = $req->fetchAll();
                    $tab_list_role = [];

                    foreach( $req_list_role AS $r){

                     array_push($tab_list_role ,[$r['role'] ,$r['libelle']]);
                   
                   }



 if (!empty($_POST)) {

            extract($_POST);/*permet de déclarer les variables en quelque sorte sans avoir d'ajouter les post chaque fois*/
            $valid= true;

         if(isset($_POST['changementrole']))
         {
                    $id_user = (int) $id_user;

                    $role = (int) $role;

                     $req =$DB-> prepare("SELECT * FROM utilisateur WHERE id =? ");
                      $req->execute([$id_user]);
                    $verif_user = $req->fetch(); 

                     if(!$verif_user){
                     $valid=false;
                     $err_role="Cet utilisateur n'existe plus";
                     }
                    else{

                           $req =$DB-> prepare("SELECT * FROM admin_role WHERE role =? AND role<> 1 AND ordre > ? ");
                            $req->execute([$role , $_SESSION['role_ordre']]);
                          $verif_role = $req->fetch();     
                           
                          if(!$verif_role){
                           $valid=false;
                           $err_role="Ce role n'existe pas";
                          }
                      }


                    if($valid)
                    {
                     $req = $DB->prepare("UPDATE utilisateur SET role = ? WHERE id= ?");
                     $req->execute([$verif_role['role'] , $id_user]);

                     header("Location: //localhost/creation/_admin/niveau.php");
                     exit;
                    }

         }

      }   
?>


<!DOCTYPE html>
<html lang="en">
<head>

     <title>Changement role</title>
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
                  <div>Changement role</div>
                    <?php
                     
                     if(isset($err_role)){
                       echo $err_role ;
                     }
                     
                    ?>
                    <div>
                                <?php
                                foreach( $req_list_user AS $r){

                               ?>

                               <form  method="post">
                                      <div>
                                       <div><?= $r['pseudo']?></div>
                                       <br>

                                      <select name="role" >

                                       <option value="<?= $r['role']?>" hidden><?= $r['libelle']?> </option>
                                       <?php
                                            foreach($tab_list_role as $tr){
                                          ?>

                                        <option value="<?= $tr['0']?>"><?= $tr['1']?> </option>

                                        <?php
                                            }
                                        ?>
                                      </select>         
                                        <input type="hidden" name="id_user" value="<?=$r['id']?>" />
                                        <button type="submit" name="changementrole">Modifier</button> 
                                      </div>
                             </form>
                               
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