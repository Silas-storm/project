<?php
require_once('include.php');
 session_start();
 $_SESSION=array();

session_destroy();
  header('Location:index1.php');
  exit;

  ?>