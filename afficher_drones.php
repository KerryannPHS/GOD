<?php


$host = "localhost";
$user = "root";
$password = "root";
$dbname = "API";

$connexion = mysqli_connect($host, $user, $password, $dbname);

$sql = "SELECT * FROM Drones JOIN Positions ON Drones.id = Positions.drone_id";

$result = mysqli_query($connexion, $sql);

while ($drone = mysqli_fetch_assoc($result)) {
    echo "**Drone :** " . $drone["nom"] . "<br>";
    echo "**Latitude :** " . $drone["latitude"] . "<br>";
    echo "**Longitude :** " . $drone["longitude"] . "<br>";
    echo "**Altitude :** " . $drone["altitude"] . "<br>";
    echo "<br>";
}

mysqli_close($connexion);

?>
