<?php
 session_start();
 include_once('_db/connexion_db.php');
 include_once('_class/admin_role.php');
 include_once('_class/inscription.php');
 include_once('_class/connexion.php');

 $_Admin_Role=new Admin_Role; 
 $_Inscription= new Inscription;
 $_Connexion= new Connexion;
?>