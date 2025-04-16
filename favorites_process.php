<?php
session_start();

// Mögliche Favoriten-Typen und die zugehörigen Session-Keys
$favoriteType = [
    'artist_id' => 'favorite_artists',
    'artwork_id' => 'favorite_artworks'
];

foreach ($favoriteType as $postKey => $favorite) {
    if (isset($_POST[$postKey])) {
        $id = $_POST[$postKey];
        $_SESSION[$favorite] = $_SESSION[$favorite] ?? [];

        if (in_array($id, $_SESSION[$favorite])) {
            $_SESSION[$favorite] = array_diff($_SESSION[$favorite], [$id]);
        } else {
            $_SESSION[$favorite][] = $id;
        }
        break; // Nur eine ID gleichzeitig verarbeiten
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>