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

// Si la méthode de requête est POST, traiter les données JSON du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON envoyées via POST
    $data = file_get_contents('php://input');
    $drone = json_decode($data, true);

    // Vérifier si les données JSON ont été correctement décodées
    if (!$drone) {
        die("Données JSON invalides.");
    }

    // Extraire les valeurs des champs du formulaire
    $nomDrone = isset($drone["nom_drone"]) ? $drone["nom_drone"] : null;
    $description = isset($drone["description"]) ? $drone["description"] : null;
    $latitude = isset($drone["latitude"]) ? $drone["latitude"] : null;
    $longitude = isset($drone["longitude"]) ? $drone["longitude"] : null;
    $altitude = isset($drone["altitude"]) ? $drone["altitude"] : null;

    // Préparer la requête d'insertion SQL pour la table Drones
    $sqlDrones = "INSERT INTO Drones (nom, description) VALUES (?, ?)";
    $stmtDrones = mysqli_prepare($connexion, $sqlDrones); 

    // Vérifier la préparation de la requête
    if ($stmtDrones) {
        // Liaison des paramètres
        mysqli_stmt_bind_param($stmtDrones, "ss", $nomDrone, $description);
        
        // Exécuter la requête
        if (mysqli_stmt_execute($stmtDrones)) {
            // Récupérer l'ID du drone inséré
            $droneId = mysqli_insert_id($connexion);

            // Préparer la requête d'insertion SQL pour la table Positions
            $sqlPositions = "INSERT INTO Positions (drone_id, latitude, longitude, altitude) VALUES (?, ?, ?, ?)";
            $stmtPositions = mysqli_prepare($connexion, $sqlPositions);

            // Vérifier la préparation de la requête
            if ($stmtPositions) {
                // Liaison des paramètres
                mysqli_stmt_bind_param($stmtPositions, "iddd", $droneId, $latitude, $longitude, $altitude);
                
                // Exécuter la requête
                if (mysqli_stmt_execute($stmtPositions)) {
                    // Insertion réussie
                    echo json_encode(["message" => "Drone ajouté avec succès !"]);
                } else {
                    // Échec de l'insertion
                    echo json_encode(["message" => "Échec de l'ajout du drone : " . mysqli_error($connexion)]);
                }

                // Fermer la déclaration
                mysqli_stmt_close($stmtPositions);
            } else {
                // Échec de la préparation de la requête
                echo json_encode(["message" => "Échec de la préparation de la requête : " . mysqli_error($connexion)]);
            }
        } else {
            // Échec de l'insertion
            echo json_encode(["message" => "Échec de l'ajout du drone : " . mysqli_error($connexion)]);
        }

        // Fermer la déclaration
        mysqli_stmt_close($stmtDrones);
    } else {
        // Échec de la préparation de la requête
        echo json_encode(["message" => "Échec de la préparation de la requête : " . mysqli_error($connexion)]);
    }
} else {
    // Si la méthode de requête est GET, afficher le formulaire HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un drone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/1L_dstPt3vvmD3hptI8LUk4W77lW7J/Xlx3m0z" crossorigin="anonymous">
    <link rel="stylesheet" href="ajt.css">

  </head>
<body>
<div class="container">
    <h1>Ajouter un drone</h1>
    <form id="ajouterDroneForm">
        <div class="mb-3">
            <label for="nom_drone" class="form-label">Nom du drone</label>
            <input type="text" class="form-control" id="nom_drone" name="nom_drone" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="number" step="0.00001" class="form-control" id="latitude" name="latitude">
        </div>
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="number" step="0.00001" class="form-control" id="longitude" name="longitude">
        </div>
        <div class="mb-3">
            <label for="altitude" class="form-label">Altitude</label>
            <input type="number" step="0.01" class="form-control" id="altitude" name="altitude">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <button type="button" class="btn btn-second" onclick="window.location.href='drone.php';">Retour</button>

      </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB5IXNxFwWQfE7u8Lj+XJHAxKlXiG/6KQ5Jk7qD1wg6OjU6C" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
<script>
    document.getElementById('ajouterDroneForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher la soumission du formulaire par défaut

        // Créer un objet avec les données du formulaire
        var droneData = {
            "nom_drone": document.getElementById('nom_drone').value,
            "description": document.getElementById('description').value,
            "latitude": parseFloat(document.getElementById('latitude').value),
            "longitude": parseFloat(document.getElementById('longitude').value),
            "altitude": parseFloat(document.getElementById('altitude').value)
        };

        // Envoyer les données JSON via une requête POST
        fetch('ajouter_drones.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(droneData),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Réponse du serveur :', data);
            // Ajoutez ici le code pour gérer la réponse du serveur, par exemple afficher un message à l'utilisateur
        })
        .catch((error) => {
            console.error('Erreur lors de la requête :', error);
            // Ajoutez ici le code pour gérer les erreurs de requête, par exemple afficher un message d'erreur à l'utilisateur
        });
    });
</script>
</body>
</html>
<?php
}
?>
