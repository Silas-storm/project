<?php
   class Connexion{
       private $valid;
        private $err_pseudo;
    private $err_password;

       public  function verification_connexion($pseudo, $password) {

      global $DB;
      global $_Admin_Role;
          
          $pseudo= ucfirst(trim($pseudo));/*permet de supprimer les espaces au debut et a la fin d'une valeur entrée*/
    $password=trim($password);
    $this->valid=(boolean) true;


    if (empty($pseudo)) {
      $this->err_pseudo="Ce champ ne peut etre vide "; 
      $this->valid=false;
    }

    if (empty($password)) {
      $this->err_password="Ce champ ne peut etre vide ";
      $this->valid=false; 
    }

    if($this->valid){
        $req = $DB-> prepare('SELECT mdp FROM utilisateur WHERE pseudo=?');
        $req->execute(array($pseudo));
        $req=$req->fetch();

        if (isset($req['mdp'])) {
           if (password_verify($password, $req['mdp'])) {
          
           $this->valid=false;
            $this->err_pseudo="La conbinaison du pseudo/mot de passe est incorrecte ";
           }
        }else{
          
          $this->valid=false;
            $this->err_pseudo="La conbinaison du pseudo/mot de passe est incorrecte ";
        }
    }
     
      
 
      if ($this->valid) {
      
        $req = $DB-> prepare('SELECT u.* , ar.ordre role_ordre FROM utilisateur u INNER JOIN admin_role ar ON ar.role=u.role WHERE u.pseudo=?');
        $req->execute(array($pseudo));
        $req_user=$req->fetch();

        if (isset($req_user['id'])) {
                   $date_modification=date('Y-m-d H-i-s');
        $req = $DB->prepare("UPDATE utilisateur SET date_modification=? WHERE id=?");
        $req->execute(array($date_modification , $req_user['id']));

        $_SESSION['id']=$req_user['id'];
        $_SESSION['pseudo']=$req_user['pseudo'];
        $_SESSION['mail']=$req_user['mail'];
        $_SESSION['role']=$req_user['role'];
        $_SESSION['avatar']=$req_user['avatar'];
        $_SESSION['role_ordre']=$req_user['role_ordre'];
        
        if($req_user['role'] > 0){
        $_Admin_Role->chargement_role($req_user['role']);
        }
        
        header('Location:index1.php');
        exit;

        }else{
          
          $this->valid=false;
            $this->err_pseudo="La conbinaison du pseudo/mot de passe est incorrecte ";
        }
   
      }
      
      return [$this->err_pseudo, $this->err_password] ;

     }

   }


?>