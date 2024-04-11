<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="style.css"> </head>

<body>
  <div class="container">
    <?php
      session_start();

      if (isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        echo "<p><b>Bienvenue " . $login . " !</b></p>";
      }
    ?>
    <a href="afficher_drones.php" class="button">Afficher les drones</a>
    <a href="ajouter_drones.php" class="button">Ajouter un drone</a>
    <a href="login.php" class="button_login">Déconnexion</a>
  </div>
</body>

</html>
