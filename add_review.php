<?php
session_start();
require_once 'database.php';
require_once 'reviewRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

$artworkId = $_POST['artwork_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

$customerId = $_SESSION['user']['CustomerID'];
$reviewRepo = new ReviewRepository(new Database());

if ($reviewRepo->hasUserReviewedArtwork($artworkId, $customerId)) {
    $_SESSION['error_message'] = "You have already reviewed this artwork.";
    header("Location: site_artwork.php?id=$artworkId");
    exit();
}

$reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

header("Location: site_artwork.php?id=$artworkId");
exit();
?>
