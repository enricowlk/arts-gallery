<?php
/**
 * Handles the deletion of artwork reviews (Admin function)
 * 
 * This controller processes POST requests for deleting existing reviews.
 * Implements Use Case 17 - Delete A Review.
 */

// Start session for user data and status messages
session_start();

// Include database configuration and review repository
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../repositories/reviewRepository.php';

/**
 * Admin access control
 * Only users with Type=1 (Admin) can delete reviews
 * Terminates execution with error message for unauthorized access
 */
if (!isset($_SESSION['user']['Type']) || $_SESSION['user']['Type'] != 1) {
    $_SESSION['error'] = "Unauthorized access";
    exit();
}

/**
 * Request validation
 * Verifies all required POST parameters are present
 * Terminates execution for invalid requests
 */
if (!isset($_POST['review_id']) || !isset($_POST['artwork_id'])) {
    $_SESSION['error'] = "Invalid request";
    exit();
}

// Get IDs from POST data and cast to integers (type safety)
$reviewId = (int)$_POST['review_id'];
$artworkId = (int)$_POST['artwork_id'];

/**
 * Review deletion process
 * Initializes repository and attempts to delete review
 * Sets appropriate status message in session
 */
$reviewRepo = new ReviewRepository(new Database());
if ($reviewRepo->deleteReview($reviewId)) {
    $_SESSION['success'] = "Review deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete review";
}

// Redirect back to artwork page with status message
header("Location: ../views/site_artwork.php?id=$artworkId");
exit();
?>