<?php 
   
  class Inscription{
    private $valid;
    private $err_pseudo;
    private $err_mail;
    private $err_password;


    public  function verification_inscription($pseudo, $mail, $confmail, $password, $confpassword) {
    
    global $DB;
    /*variables d'entées */
        $pseudo= (String) ucfirst(trim($pseudo));/*permet de supprimer les espaces au debut et a la fin d'une valeur entrée*/
    $mail= (String) trim($mail);
    $confmail= (String) trim($confmail);
    $password= (String) trim($password);
    $confpassword=(String) trim($confpassword);
     
     /*variables de sorties   */
     $this->err_pseudo= (String) '';
     $this->err_mail=(String)'';
     $this->err_password=(String)'';
     $this->valid=(boolean) true;
       
     $this->verification_pseudo($pseudo);
      $this->verification_mail($mail,$confmail);
      $this->verification_password($password,$confpassword);
     

      
      if ($this->valid) {

//----------------------------------------------J'ai laissé ce code là pour me rappeler des notions qu'il a abordé en diagonale comme celle de password_verify------------------------------ 
        /*
        if (password_verify($password, $crytp_password)) {
           echo "le mot de passe est validé";
        }else
        {
          echo "le mot de passe est invalide";
        }
      //  $crypt_password=crypt($password, '$6$rounds=5000$hOKTYxxQ6Dx98dv*sb)Fs$f(abAWm*FNEYR(grV5XcRjYmmKH^g$8zQeca7T%@Fa');*/
//------------------------------------------♠♠♠♠♠-------------------------------------------------------
        $crytp_password=password_hash($password, PASSWORD_ARGON2ID);
        $date_creation=date('Y-m-d H-i-s');
        $req = $DB->prepare("INSERT INTO utilisateur( pseudo, mail, mdp, date_creation, date_modification) VALUES (?,?,?,?,?)");
        $req->execute(array($pseudo,$mail,$crytp_password,$date_creation,$date_creation));
        header('Location:connexion.php');
        exit;
      }
         return [$this->err_pseudo, $this->err_mail, $this->err_password];
      }
   
   private  function verification_pseudo($pseudo){
      global $DB;

   	 if (empty($pseudo)) {
      $this->err_pseudo="Ce champ ne peut etre vide "; 
      $this->valid=false;
    }
    elseif (grapheme_strlen($pseudo)< 5) {
       $this->err_pseudo="Le pseudo doit faire plus de cinq caracteres "; 
      $this->valid=false;
    }
    elseif (grapheme_strlen($pseudo)> 26) {
       $this->err_pseudo="Le pseudo doit faire moins de 26 caracteres(".grapheme_strlen($pseudo)."/26)"; 
      $this->valid=false;
    }
    else{
        $req = $DB-> prepare('SELECT id FROM utilisateur WHERE pseudo=?');
        $req->execute(array($pseudo));
        $req=$req->fetch();

        if (isset($req['id'])) {
          $this->valid=false;
            $this->err_pseudo="Ce pseudo est déja pris ";/*ceci implique que dan sla base de données il existe déja un id possédant les memes informations que celles entrées */
        }
    }
     return true;
   }

    private  function verification_mail($mail, $confmail){
       global $DB;
    	if (empty($mail)) {
      $this->err_mail="Ce champ ne peut etre vide ";
      $this->valid=false; 
    }elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      $this->valid=false;
      $this->err_mail='Format invalide pour ce mail';
    }
    elseif($mail<>$confmail){
      $this->err_mail="Le mail est différent de la confirmation ";
      $this->valid=false;
    }
    else{
        $req = $DB-> prepare('SELECT id FROM utilisateur WHERE mail=?');
        $req->execute(array($mail));
        $req=$req->fetch();

        if (isset($req['id'])) {
          $this->valid=false;
            $this->err_mail="Ce mail a déja été utilisé ";/*ceci implique que dan sla base de données il existe déja un id possédant les memes informations que celles entrées */
        }
    }
     return true ;
   }

    private  function verification_password( $password, $confpassword){
       global $DB;

      if (empty($password)) {
      $this->err_password="Ce champ ne peut etre vide ";
      $this->valid=false; 
    }
    elseif($password<>$confpassword){
      $this->err_password="Le mot de passe  est différent de la confirmation ";
      $this->valid=false;
    }
    else{
        $req = $DB-> prepare('SELECT id FROM utilisateur WHERE mdp=?');
        $req->execute(array($password));
        $req=$req->fetch();

        if (isset($req['id'])) {
          $this->valid=false;
            $this->err_password="Ce mot de passe a déja été utilisé ";/*ceci implique que dan sla base de données il existe déja un id possédant les memes informations que celles entrées */
        }
    }

   }
  }

 ?>