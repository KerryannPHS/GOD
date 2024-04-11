<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="lgn.css">
</head>

<body>
    <div class="container">
        <form action="login.php" method="post">
            Nom d'utilisateur :
            <input type="text" name="login">
            Mot de passe :
            <input type="password" name="password">

            <button type="submit">Envoyer</button>
        </form>


    </div>
</body>

</html>





<?php

    session_start();

    $login = $_POST['login'];
    $pwd = $_POST['password'];

    if($login == "Peter" && $pwd == "peter") {

        $_SESSION['login'] = $login;
        echo "Bienvenue ".$login;
        
  header('Location: drone.php');
  exit;
    } if($login == "admin" && $pwd == "admin") {

        $_SESSION['login'] = $login;
        echo "Bienvenue ".$login;
        
  header('Location: drone.php');
  
    } else {

       

        echo "<script>alert('Mot de passe incorrect');</script>";
    }