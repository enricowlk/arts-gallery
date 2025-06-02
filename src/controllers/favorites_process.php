<?php
session_start(); // Session starten für Daten aus der Session

// Mapping von POST-Keys zu Session-Keys
$favoriteType = [
    'artist_id' => 'favorite_artists',   
    'artwork_id' => 'favorite_artworks'  
];

// Durch Mapping loopen
foreach ($favoriteType as $postKey => $favorite) {
    if (isset($_POST[$postKey])) {
        $id = $_POST[$postKey];  // ID aus POST holen
        
        // Favoriten-Array initialisieren falls nicht existiert
        $_SESSION[$favorite] = $_SESSION[$favorite] ?? [];
        
        // ID hinzufügen/entfernen
        if (in_array($id, $_SESSION[$favorite])) {
            $_SESSION[$favorite] = array_diff($_SESSION[$favorite], [$id]);  // Entfernen
        } else {
            $_SESSION[$favorite][] = $id;  // Hinzufügen
        }
        break;  // Nur einen Typ pro Request bearbeiten
    }
}

// Zur vorherigen Seite zurück
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>