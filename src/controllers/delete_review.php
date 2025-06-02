<?php
session_start(); // Session für Benutzerdaten

// Datenbank und Repository einbinden
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../repositories/reviewRepository.php';

// Nur Admin-Zugriff erlauben (Type = 1) 
if (!isset($_SESSION['user']['Type']) || $_SESSION['user']['Type'] != 1) {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../views/components/error.php");
    exit();
}

// Prüfen ob benötigte POST-Daten vorhanden 
if (!isset($_POST['review_id']) || !isset($_POST['artwork_id'])) {
    $_SESSION['error'] = "Invalid request";
    header("Location: ../views/components/error.php");
    exit();
}

// IDs aus POST-Daten holen
$reviewId = (int)$_POST['review_id'];
$artworkId = (int)$_POST['artwork_id'];

// Review löschen
$reviewRepo = new ReviewRepository(new Database());
if ($reviewRepo->deleteReview($reviewId)) {
    $_SESSION['success'] = "Review deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete review";
}

// Zurück zur Artwork-Seite
header("Location: ../views/site_artwork.php?id=$artworkId");
exit();
?>