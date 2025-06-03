<?php
/**
 * Handles adding/removing items to/from favorites lists
 * 
 * This controller processes POST requests for managing both artist and artwork favorites.
 * Implements parts of Use Case 18 - Add To Favorites List and Use Case 19 - View Favorites List.
 */

// Start session for storing favorites
session_start();

/**
 * Mapping of POST keys to session keys
 * Defines the relationship between form fields and session storage
 * @var array $favoriteType
 */
$favoriteType = [
    'artist_id' => 'favorite_artists',   // Key for artist favorites
    'artwork_id' => 'favorite_artworks'  // Key for artwork favorites
];

/**
 * Process favorite toggle request
 * Loops through possible favorite types and processes the first valid one found
 */
foreach ($favoriteType as $postKey => $favorite) {
    if (isset($_POST[$postKey])) {
        $id = $_POST[$postKey];  // Get ID from POST data
        
        // Initialize favorites array if it doesn't exist
        $_SESSION[$favorite] = $_SESSION[$favorite] ?? [];
        
        /**
         * Toggle favorite status
         * Removes ID if already present, adds if not present
         */
        if (in_array($id, $_SESSION[$favorite])) {
            // Remove from favorites
            $_SESSION[$favorite] = array_diff($_SESSION[$favorite], [$id]);
        } else {
            // Add to favorites
            $_SESSION[$favorite][] = $id;
        }
        break;  // Only process one type per request
    }
}

// Redirect back to previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>