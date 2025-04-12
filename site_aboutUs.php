<?php 
session_start(); 

require_once 'logging.php';
require_once 'global_exception_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <!-- Hauptinhalt -->
    <div class="container mt-5">
        <!-- Überschrift und Beschreibung -->
        <h1 class="text-center">About Us</h1>
        <p class="text-center">
            This site is a hypothetical web application created as a term project for the lecture "Web Development/Web-Technologies" at the technical university of applied sciences Wildau. 
            It is designed to showcase reproductions of famous art classics and provide a platform for users to explore and 
            interact with artworks, artists, and reviews. The site is not intended for commercial use but serves as an 
            educational project to demonstrate the implementation of a non-trivial web application.
        </p>

        <!-- Teamabschnitt -->
        <h2 class="mt-4">Our Team</h2>
        <p>Below are the team members and their contributions to this project:</p>
        <ul>
            <li><strong>Paul Lorenz</strong>: </li>
            <li><strong>Niels Grosche</strong>: </li>
            <li><strong>Natalie Danner</strong>: </li>
            <li><strong>Enrico Wölck</strong>: </li>
        </ul>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>