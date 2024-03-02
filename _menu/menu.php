
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index1.php">Accueil</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav"> 
      .<li class="nav-item">
          <a class="nav-link" href="_forum/forum.php">Forum</a>
        </li> 
            <li class="nav-item">
          <a class="nav-link" href="_profil/membres.php">Membres</a>
        </li>
      <?php
           if(!isset($_SESSION['id'])){
        ?>   

            <li class="nav-item">
          <a class="nav-link" href="inscription.php">Inscription</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="connexion.php">Connexion</a>
        </li>

         <?php   
           }else{
           
           ?> <li class="nav-item">
              <a class="nav-link" href="_profil/profil.php">Mon profil</a>
             </li>

          <?php 
            /*if(in_array($_SESSION['role'] , [1, 2])){*/
              if(isset($_SESSION['admin']['ok'])){
            ?>

          <li class="nav-item">
          <a class="nav-link" href="_admin/accueil.php">Admin</a>
         </li>

          <?php 
            }
          ?>
        
         
             <li class="nav-item">
          <a class="nav-link" href="deconnexion.php">Déconnexion</a>
        </li>
           <?php 
           }  
      ?>  
      
      </ul>
    </div>
  </div>
</nav>