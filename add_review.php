<?php
session_start();
require_once 'database.php';
require_once 'ReviewRepository.php';
require_once 'CustomerRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $artworkId = $_POST['artwork_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Annahme: Der eingeloggte Benutzer ist in der Session gespeichert
    if (isset($_SESSION['user'])) {
        $customerId = $_SESSION['user']['CustomerID'];

        $reviewRepo = new ReviewRepository(new Database());
        $reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

        // Zurück zur Kunstwerkseite
        header("Location: site_artwork.php?id=$artworkId");
        exit();
    } else {
        // Benutzer ist nicht eingeloggt
        header("Location: site_login.php");
        exit();
    }
}
?>