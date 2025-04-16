<?php
session_start();
require_once 'database.php';
require_once 'reviewRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(); // Nur POST-Anfragen verarbeiten
}

// Pflichtdaten aus dem Formular
$artworkId = $_POST['artwork_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

// Nur eingeloggte Benutzer dürfen bewerten
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

$customerId = $_SESSION['user']['CustomerID'];
$reviewRepo = new ReviewRepository(new Database());

// Prüfen, ob bereits bewertet
if ($reviewRepo->hasUserReviewedArtwork($artworkId, $customerId)) {
    $_SESSION['error_message'] = "You have already reviewed this artwork.";
    header("Location: site_artwork.php?id=$artworkId");
    exit();
}

// Bewertung speichern
$reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

// Weiterleitung zur Kunstwerkseite
header("Location: site_artwork.php?id=$artworkId");
exit();
?>
