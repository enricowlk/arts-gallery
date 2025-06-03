<?php
/**
 * Handles the submission of artwork reviews
 * 
 * This controller processes POST requests for adding new reviews to artworks.
 * It validates user authentication, processes form data, and stores the review.
 * Implements Use Case 16 - Add A Review.
 */

// Start session for user data and error messages
session_start();

// Include required files
require_once __DIR__ . '/../../config/database.php';       // Database configuration
require_once __DIR__ . '/../repositories/reviewRepository.php'; // Review repository

/**
 * Security check: Only allow POST requests
 * Terminates execution if not a POST request
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(); 
}

// Get form data from POST request
$artworkId = $_POST['artwork_id'];    
$rating = $_POST['rating'];         
$comment = $_POST['comment'];        

/**
 * Authentication check
 * Redirects to login page if user is not logged in
 */
if (!isset($_SESSION['user'])) {
    header("Location: ../views/site_login.php");
    exit();
}

// Get CustomerID from session
$customerId = $_SESSION['user']['CustomerID'];

// Initialize ReviewRepository with database connection
$reviewRepo = new ReviewRepository(new Database());

/**
 * Save new review to database
 */
$reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

// Redirect back to artwork page (with success notification)
header("Location: ../views/site_artwork.php?id=$artworkId");
exit();
?>