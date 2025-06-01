<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../repositories/reviewRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

$artworkId = $_POST['artwork_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

if (!isset($_SESSION['user'])) {
    header("Location: ../views/site_login.php");
    exit();
}

$customerId = $_SESSION['user']['CustomerID'];
$reviewRepo = new ReviewRepository(new Database());

if ($reviewRepo->hasUserReviewedArtwork($artworkId, $customerId)) {
    $_SESSION['error_message'] = "You have already reviewed this artwork.";
    header("Location: ../views/site_artwork.php?id=$artworkId");
    exit();
}

$reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

header("Location: ../views/site_artwork.php?id=$artworkId");
exit();
?>
