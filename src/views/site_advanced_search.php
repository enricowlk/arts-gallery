<?php
/**
 * Advanced Search Page
 *
 * This script initializes the session and sets up the global exception handler.
 * It renders a placeholder page for the "Advanced Search" functionality, which is still under development.
 */

session_start(); // Start the session to enable session-based features

// Include the global exception handler for centralized error processing
require_once __DIR__ . '/../services/global_exception_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php 
    // Include the site's navigation bar
    include __DIR__ . '/components/navigation.php'; 
    ?>

    <div class="container">
        <h1>Advanced Search</h1>
        <h2>In Progress !!!</h2>
        <!-- Future implementation of advanced search form and results will be placed here -->
    </div>

    <?php 
    // Include the site's footer
    include __DIR__ . '/components/footer.php'; 
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
