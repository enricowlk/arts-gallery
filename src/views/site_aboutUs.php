<?php
/**
 * About Us Page
 *
 * This script initializes the session, sets up the global exception handler, and renders the "About Us" page
 * of the web application. It provides context about the project's purpose and lists the team members involved.
 * This page is static and does not interact with a database.
 */

session_start(); // Start session to enable session variables

// Include global exception handler for centralized error handling
require_once __DIR__ . '/../services/global_exception_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php 
    include __DIR__ . '/components/navigation.php'; 
    ?>

    <div class="container">
        <h1 class="text-center">About Us</h1>

        <!-- Project description -->
        <p class="text-center">
            This site is a hypothetical web application created as a term project for the lecture 
            "Web Development/Web-Technologies" at the Technical University of Applied Sciences Wildau.
            It is designed to showcase reproductions of famous art classics and provide a platform for users 
            to explore and interact with artworks, artists, and reviews. The site is not intended for commercial use 
            but serves as an educational project to demonstrate the implementation of a non-trivial web application.
        </p>

        <!-- Team section -->
        <h2 class="mt-5">Our Team</h2>
        <p>Below are the team members and their contributions to this project:</p>
        <ul>
            <li><strong>Paul Lorenz</strong>: Significant contribution to core functionality and multiple user interface features. </li>
            <li><strong>Niels Grosche</strong>: Focused on specific user management and advanced UI functionality.</li>
            <li><strong>Natalie Danner</strong>: Focused on user interaction and frontend views.</li>
            <li><strong>Enrico Wölck</strong>: Worked on backend logic, database-driven features and contribution multiple user interface features. </li>
        </ul>
    </div>

    <?php 
    include __DIR__ . '/components/footer.php'; 
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
