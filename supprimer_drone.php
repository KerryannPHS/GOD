<?php
// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "API";

$connexion = mysqli_connect($host, $user, $password, $dbname);

// Récupérer l'ID du drone à supprimer
$id_drone = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Vérifier si l'ID du drone est valide
if ($id_drone > 0) {
  // Supprimer les positions liées au drone
  $sqlDeletePositions = "DELETE FROM Positions WHERE drone_id = $id_drone";
  if (mysqli_query($connexion, $sqlDeletePositions)) {
    // Supprimer le drone après avoir supprimé les positions
    $sqlDeleteDrone = "DELETE FROM Drones WHERE id = $id_drone";
    if (mysqli_query($connexion, $sqlDeleteDrone)) {
      // Suppression réussie
      echo "<script>alert('Drone supprimé avec succès !'); window.location.href='afficher_drones.php';</script>";
    } else {
      // Échec de la suppression du drone
      echo "<script>alert('Échec de la suppression du drone.'); window.location.href='index.php';</script>";
    }
  } else {
    // Échec de la suppression des positions
    echo "<script>alert('Échec de la suppression des positions associées au drone.'); window.location.href='index.php';</script>";
  }
} else {
  // ID du drone invalide
  echo "<script>alert('ID du drone invalide.'); window.location.href='index.php';</script>";
}

// Fermer la connexion à la base de données
mysqli_close($connexion);
?>
