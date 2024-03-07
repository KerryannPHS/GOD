<?php
if (isset($_GET['r']) && isset($_GET['v']) && isset($_GET['b']) && isset($_GET['title'])) {
    $r = $_GET['r'];
    $v = $_GET['v'];
    $b = $_GET['b'];
    $title = $_GET['title'];

    // Assurez-vous que les paramètres r, v et b sont des valeurs hexadécimales valides.
    if (preg_match('/^[0-9A-Fa-f]{2}$/', $r) && preg_match('/^[0-9A-Fa-f]{2}$/', $v) && preg_match('/^[0-9A-Fa-f]{2}$/', $b)) {
        // Construisez la couleur CSS en format hexadécimal (#RRGGBB).
        $color = '#' . $r . $v . $b;

        // Affichez le titre avec la couleur spécifiée.
        echo "<h1 style='color: $color;'>$title</h1>";
    } else {
        echo "Paramètres de couleur invalides.";
    }
} else {
    echo "Paramètres manquants.";
}
?>
