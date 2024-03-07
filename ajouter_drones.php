<?php
// Définition des informations de connexion
$host = "localhost";
$dbname = "API";
$username = "root";
$password = "root";

// Création d'une instance PDO
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Erreur de connexion à la base de données : " . $e->getMessage();
  die();
}

// Récupération des données du formulaire
$nomDrone = $_POST["nom_drone"];
$description = $_POST["description"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$altitude = $_POST["altitude"];

// Définition de la date de création
$dateCreation = date("Y-m-d H:i:s");

// **Vérification de l'existence du drone**
$sql_check_drone = "SELECT COUNT(*) FROM Drones WHERE nom = :nom";

// Préparation de la requête
$stmt_check_drone = $pdo->prepare($sql_check_drone);

// Liaison du paramètre
$stmt_check_drone->bindParam(":nom", $nomDrone);

// Exécution de la requête
$stmt_check_drone->execute();

// Récupération du nombre de drones trouvés
$nb_drones = $stmt_check_drone->fetchColumn(0);

// Fermeture du curseur
$stmt_check_drone->closeCursor();

if ($nb_drones == 0) {
  // **Ajout du drone**
  $sql_insert_drone = "INSERT INTO Drones (nom, description, date_creation) VALUES (:nom, :description, :dateCreation)";

  // Préparation de la requête
  $stmt_insert_drone = $pdo->prepare($sql_insert_drone);

  // Liaison des paramètres
  $stmt_insert_drone->bindParam(":nom", $nomDrone);
  $stmt_insert_drone->bindParam(":description", $description);
  $stmt_insert_drone->bindParam(":dateCreation", $dateCreation);

  // Exécution de la requête
  $stmt_insert_drone->execute();

  // Récupération de l'identifiant du drone inséré
  $drone_id = $pdo->lastInsertId();

  // Fermeture du curseur
  $stmt_insert_drone->closeCursor();
} else {
  // Le drone existe déjà, on récupère son identifiant
  $sql_get_drone_id = "SELECT id FROM Drones WHERE nom = :nom";

  // Préparation de la requête
  $stmt_get_drone_id = $pdo->prepare($sql_get_drone_id);

  // Liaison du paramètre
  $stmt_get_drone_id->bindParam(":nom", $nomDrone);

  // Exécution de la requête
  $stmt_get_drone_id->execute();

  // Récupération de l'identifiant du drone
  $drone_id = $stmt_get_drone_id->fetchColumn(0);

  // Fermeture du curseur
  $stmt_get_drone_id->closeCursor();
}

// **Ajout de la position du drone**
$sql_insert_position = "INSERT INTO Positions (drone_id, latitude, longitude, altitude, date_creation) VALUES (:drone_id, :latitude, :longitude, :altitude, :dateCreation)";

// Préparation de la requête
$stmt_insert_position = $pdo->prepare($sql_insert_position);

// Liaison des paramètres
$stmt_insert_position->bindParam(":drone_id", $drone_id);
$stmt_insert_position->bindParam(":latitude", $latitude);
$stmt_insert_position->bindParam(":longitude", $longitude);
$stmt_insert_position->bindParam(":altitude", $altitude);
$stmt_insert_position->bindParam(":dateCreation", $dateCreation);

// Exécution de la requête
$stmt_insert_position->execute();

// Fermeture du curseur
$stmt_insert_position->closeCursor();

// Fermeture de la connexion à la base de données
$pdo = null;

// Redirection vers la page d'affichage des drones
header("Location: afficher_drones.php");

?>
