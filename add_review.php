<?php
session_start(); // Session starten
require_once 'database.php'; // Datenbankverbindung
require_once 'reviewRepository.php'; // Logik für Bewertungen

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Nur bei POST-Anfrage
    $artworkId = $_POST['artwork_id']; // ID des Kunstwerks
    $rating = $_POST['rating']; // Bewertung (z.B. 1-5)
    $comment = $_POST['comment']; // Kommentar zur Bewertung

    if (isset($_SESSION['user'])) { // Prüft, ob Benutzer eingeloggt ist
        $customerId = $_SESSION['user']['CustomerID']; // Benutzer-ID aus Session

        $reviewRepo = new ReviewRepository(new Database()); // Bewertungs-Repository
        
        // Check if user has already reviewed this artwork
        if ($reviewRepo->hasUserReviewedArtwork($artworkId, $customerId)) {
            $_SESSION['error_message'] = "You have already reviewed this artwork.";
            header("Location: site_artwork.php?id=$artworkId");
            exit();
        }
        
        // If not, add the review
        $reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

        header("Location: site_artwork.php?id=$artworkId"); // Zurück zur Kunstwerkseite
        exit();
    } else {
        header("Location: site_login.php"); // Zur Login-Seite, wenn nicht eingeloggt
        exit();
    }
}
?>