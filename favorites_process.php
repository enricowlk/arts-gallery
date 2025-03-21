<?php
session_start(); // Startet die Session

// Prüfen, ob eine Künstler-ID oder Kunstwerk-ID gesendet wurde
if (isset($_POST['artist_id'])) {
    // Verarbeitung für Künstler
    $artistId = $_POST['artist_id']; // Holt die Künstler-ID aus dem POST-Request

    // Initialisiert die Favoritenliste für Künstler, falls sie noch nicht existiert
    if (!isset($_SESSION['favorite_artists'])) {
        $_SESSION['favorite_artists'] = [];
    }

    // Überprüft, ob der Künstler bereits in den Favoriten ist
    if (in_array($artistId, $_SESSION['favorite_artists'])) {
        // Künstler aus der Favoritenliste entfernen
        $_SESSION['favorite_artists'] = array_diff($_SESSION['favorite_artists'], [$artistId]);
    } else {
        // Künstler zur Favoritenliste hinzufügen
        $_SESSION['favorite_artists'][] = $artistId;
    }
} elseif (isset($_POST['artwork_id'])) {
    // Verarbeitung für Kunstwerke
    $artworkId = $_POST['artwork_id']; // Holt die Kunstwerk-ID aus dem POST-Request

    // Initialisiert die Favoritenliste für Kunstwerke, falls sie noch nicht existiert
    if (!isset($_SESSION['favorite_artworks'])) {
        $_SESSION['favorite_artworks'] = [];
    }

    // Überprüft, ob das Kunstwerk bereits in den Favoriten ist
    if (in_array($artworkId, $_SESSION['favorite_artworks'])) {
        // Kunstwerk aus der Favoritenliste entfernen
        $_SESSION['favorite_artworks'] = array_diff($_SESSION['favorite_artworks'], [$artworkId]);
    } else {
        // Kunstwerk zur Favoritenliste hinzufügen
        $_SESSION['favorite_artworks'][] = $artworkId;
    }
}

// Zurück zur vorherigen Seite
header('Location: ' . $_SERVER['HTTP_REFERER']); // Leitet den Benutzer zurück zur vorherigen Seite
exit(); // Beendet das Skript
?>