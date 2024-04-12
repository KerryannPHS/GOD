<?php
// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "API";
$connexion = mysqli_connect($host, $user, $password, $dbname);

// Vérification de la connexion
if (!$connexion) {
    die("Connection failed: " . mysqli_connect_error());
}

// Requête SQL pour récupérer les données des drones
$sql = "SELECT Drones.*, Positions.latitude, Positions.longitude, Positions.altitude FROM Drones LEFT JOIN Positions ON Drones.id = Positions.drone_id";
$result = mysqli_query($connexion, $sql);

// Création d'un tableau pour stocker les données des drones
$drones = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $drone_id = $row['id'];
        $drone_nom = $row['nom'];
        $cordonnees = array(
            'altitude' => $row['altitude'],
            'longitude' => $row['longitude'],
            'latitude' => $row['latitude']
        );

        // Ajout des coordonnées au tableau correspondant au drone
        if (!isset($drones[$drone_id])) {
            $drones[$drone_id] = array(
                'nom_du_drone' => $drone_nom,
                'coordonnees' => array($cordonnees)
            );
        } else {
            $drones[$drone_id]['coordonnees'][] = $cordonnees;
        }
    }
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);

// Envoi des en-têtes HTTP appropriés pour le téléchargement du fichier JSON
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="drones.json"');

// Sortie du fichier JSON
echo json_encode($drones, JSON_PRETTY_PRINT);
?>

