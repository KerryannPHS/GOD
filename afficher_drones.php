<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Afficher les drones</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/1L_dstPt3vvmD3hptI8LUk4W77lW7J/Xlx3m0z" crossorigin="anonymous">
    <!-- Inclure votre fichier CSS personnalisé (facultatif) -->
    <link rel="stylesheet" href="styles_view.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Liste des drones</h1>
        <?php
        $host = "localhost";
        $user = "root";
        $password = "root";
        $dbname = "API";

        $connexion = mysqli_connect($host, $user, $password, $dbname);

        $sql = "SELECT Drones.*, Positions.latitude, Positions.longitude, Positions.altitude FROM Drones LEFT JOIN Positions ON Drones.id = Positions.drone_id";

        $result = mysqli_query($connexion, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Afficher le tableau avec les données des drones
            echo "<table class='table table-striped table-bordered'>";
            echo "<tr><th>Nom du drone</th><th>Description</th><th>Date de création</th><th>Latitude</th><th>Longitude</th><th>Altitude</th></tr>";

            while ($drone = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($drone["nom"]) . "</td>";
                echo "<td>" . htmlspecialchars($drone["description"]) . "</td>";
                echo "<td>" . htmlspecialchars($drone["date_creation"]) . "</td>";

                if (!is_null($drone["latitude"])) {
                    echo "<td>" . htmlspecialchars($drone["latitude"]) . "</td>";
                } else {
                    echo "<td>-</td>";
                }

                if (!is_null($drone["longitude"])) {
                    echo "<td>" . htmlspecialchars($drone["longitude"]) . "</td>";
                } else {
                    echo "<td>-</td>";
                }

                if (!is_null($drone["altitude"])) {
                    echo "<td>" . htmlspecialchars($drone["altitude"]) . "</td>";
                } else {
                    echo "<td>-</td>";
                }
  // Ajouter un bouton "Supprimer" avec un lien vers un script PHP de suppression
  echo "<td><a href='supprimer_drone.php?id=" . $drone["id"] . "' class='btn btn-danger'>Supprimer</a></td>";


                echo "</tr>";
            }

            echo "</table>";
        } else {
            // Aucun drone trouvé
            echo "Aucun drone trouvé dans la base de données.";
        }

        mysqli_close($connexion);
        ?>
        <br>
        <br>
        <button type="button" class="btn btn-secondary mt-3 d-block mx-auto" onclick="window.location.href='drone.php';">Retour</button>
    </div>
    <!-- Inclure Bootstrap JS (Popper.js est nécessaire pour les tooltips, popovers et modals) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB5IXNxFwWQfE7u8Lj+XJHAxKlXiG/6KQ5Jk7qD1wg6OjU6C" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</body>
</html>
