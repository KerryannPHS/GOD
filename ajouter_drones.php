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

// Récupérer tous les drones existants pour les afficher dans le formulaire
$sql = "SELECT id, nom FROM Drones";
$result = mysqli_query($connexion, $sql);
$drones = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    // Vérifie si l'utilisateur souhaite ajouter un nouveau drone ou mettre à jour un existant
    if ($data['drone_existant'] === "nouveau") {
        // Ajouter un nouveau drone
        $nomDrone = $data['nom_drone'];
        $description = $data['description'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $altitude = $data['altitude'];

        $sql = "INSERT INTO Drones (nom, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($connexion, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $nomDrone, $description);
        mysqli_stmt_execute($stmt);
        $droneId = mysqli_insert_id($connexion);

    } else {
        // Utiliser l'ID du drone existant
        $droneId = $data['drone_existant'];
    }

    // Ajouter les coordonnées pour le drone
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $altitude = $data['altitude'];
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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un drone ou des coordonnées</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Ajouter un drone ou des coordonnées</h2>
    <form id="droneForm">
        <div class="form-group">
            <label for="drone_existant">Sélectionner un drone existant ou nouveau</label>
            <select class="form-control" id="drone_existant" name="drone_existant">
                <option value="nouveau">Nouveau drone</option>
                <?php foreach ($drones as $drone): ?>
                    <option value="<?= $drone['id']; ?>"><?= $drone['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="nom_drone">Nom du drone</label>
            <input type="text" class="form-control" id="nom_drone" name="nom_drone">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description">
        </div>
        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
        </div>
        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
        </div>
        <div class="form-group">
            <label for="altitude">Altitude</label>
            <input type="number" step="any" class="form-control" id="altitude" name="altitude" required>
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='drone.php';">Retour</button>

    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#droneForm').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                drone_existant: $('#drone_existant').val(),
                nom_drone: $('#nom_drone').val(),
                description: $('#description').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                altitude: $('#altitude').val()
            };
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                // Recharger la page ou rediriger l'utilisateur comme nécessaire
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    });
</script>
</body>
</html>
