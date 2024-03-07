<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couleur Hexadécimale</title>
    <style>
        body {
//            background-color: <?php echo isset($_POST['couleur']) ? $_POST['couleur'] : 'white'; ?>;
        }
    </style>
</head>
<body>
    <form method="post">
        Entrez un code hexadécimal de couleur : <input type="text" name="couleur">
        <input type="submit" value="Appliquer">
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $couleur = $_POST["couleur"];

        if (preg_match('/^#[a-f0-9]{6}$/i', $couleur)) {
            echo "<div style='background-color: $couleur; width: 100px; height: 100px;'></div>";
            echo "La couleur est : $couleur";
        } else {
            echo "Code hexadécimal de couleur invalide. Veuillez entrer une valeur au format #RRGGBB.";
        }
    }
    ?>
</body>
</html>
