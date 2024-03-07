<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définition des informations de connexion
$host = "localhost";
$user = "root"; // Remplacer par le nom d'utilisateur de votre base de données
$password = "root"; // Remplacer par le mot de passe de votre base de données
$dbname = "API"; // Remplacer par le nom de votre base de données

// Tentative de connexion à la base de données
$connexion = mysqli_connect($host, $user, $password, $dbname);

// Vérification de la connexion
if ($connexion) {
    // Affichage d'un message de succès
    echo "<p>Connexion à la base de données réussie !</p>";

    // Requête pour extraire les noms des drones
    $sql = "SELECT nom FROM Drones";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $sql);

    // Vérification du résultat
    if ($resultat) {
        // Boucle pour afficher les noms des drones
        while ($drone = mysqli_fetch_assoc($resultat)) {
            echo "<p>" . $drone["nom"] . "</p>";
        }

        // Libération du résultat
        mysqli_free_result($resultat);
    } else {
        // Affichage d'un message d'erreur
        echo "<p>Echec de l'extraction des noms des drones.</p>";
    }
} else {
    // Affichage d'un message d'erreur de connexion
    echo "<p>Echec de la connexion à la base de données.</p>";
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);

?>
