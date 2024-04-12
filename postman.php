<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "API";

$connexion = mysqli_connect($host, $user, $password, $dbname);

// Vérifier la connexion
if (!$connexion) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées depuis POSTMAN
    $data = json_decode(file_get_contents('php://input'), true);

    // Assurez-vous que les données obligatoires sont présentes
    if (!isset($data['drone_id']) || !isset($data['latitude']) || !isset($data['longitude']) || !isset($data['altitude'])) {
        echo json_encode(["message" => "Veuillez fournir toutes les données nécessaires"]);
        exit();
    }

    // Récupérer les données depuis POSTMAN
    $droneId = $data['drone_id'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $altitude = $data['altitude'];

    // Ajouter les coordonnées pour le drone
    $sql = "INSERT INTO Positions (drone_id, latitude, longitude, altitude) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "iddd", $droneId, $latitude, $longitude, $altitude);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(["message" => "Succès"]);
    } else {
        echo json_encode(["message" => "Erreur lors de l'ajout des données"]);
    }
    exit();
}
?>
