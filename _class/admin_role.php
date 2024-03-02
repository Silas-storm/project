<?php
   class Admin_Role{
  
       public  function chargement_role($p_role) {

      global $DB;
          
        $req = $DB-> prepare('SELECT * FROM admin_matrice WHERE role =?');

        $req->execute([$p_role]);

        $req=$req->fetch();
       
        if(!$req){
         return true;
        }
        $_SESSION['admin']['ok'] = true;
        $_SESSION['admin']['admin_accueil']=$req['admin_accueil'];
        $_SESSION['admin']['admin_changement_role']=$req['admin_changement_role'];
 
     }

   }


?>