<?php
session_start();

// Kunstwerk-ID aus dem Formular holen
$artworkId = $_POST['artwork_id'];

// Initialisiere die Favoritenliste in der Session, falls sie noch nicht existiert
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Überprüfen, ob das Kunstwerk bereits in den Favoriten ist
if (in_array($artworkId, $_SESSION['favorites'])) {
    // Kunstwerk aus der Favoritenliste entfernen
    $_SESSION['favorites'] = array_diff($_SESSION['favorites'], [$artworkId]);
} else {
    // Kunstwerk zur Favoritenliste hinzufügen
    $_SESSION['favorites'][] = $artworkId;
}

// Zurück zur vorherigen Seite
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>