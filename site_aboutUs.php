<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">About Us</h1>
        <p class="text-center">
            This site is a hypothetical web application created as a term project for the lecture "Web Development/Web-Technologies" at the technical university of applied sciences Wildau. 
            It is designed to showcase reproductions of famous art classics and provide a platform for users to explore and 
            interact with artworks, artists, and reviews. The site is not intended for commercial use but serves as an 
            educational project to demonstrate the implementation of a non-trivial web application.
        </p>
        <h2 class="mt-4">Our Team</h2>
        <p>Below are the team members and their contributions to this project:</p>
        <ul>
            <li><strong>Enrico Wölck</strong>: </li>
            <li><strong>Paul Lorenz</strong>: </li>
            <li><strong>Niels Grosche</strong>: </li>
            <li><strong>Natalie Danner</strong>: </li>
        </ul>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>