<?php
session_start();
require_once 'database.php';
require_once 'ReviewRepository.php';

// Admin-Prüfung
if (!isset($_SESSION['user']['Type']) || $_SESSION['user']['Type'] != 1) {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: error.php");
    exit();
}

if (!isset($_POST['review_id']) || !isset($_POST['artwork_id'])) {
    $_SESSION['error'] = "Invalid request";
    header("Location: error.php");
    exit();
}

$reviewId = (int)$_POST['review_id'];
$artworkId = (int)$_POST['artwork_id'];

$reviewRepo = new ReviewRepository(new Database());
if ($reviewRepo->deleteReview($reviewId)) {
    $_SESSION['success'] = "Review deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete review";
}

header("Location: site_artwork.php?id=$artworkId");
exit();
?>