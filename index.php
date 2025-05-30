<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Gallery - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container">
        <h1 class="text-center">Welcome to the Art Gallery</h1>
        <p class="text-center">Explore our collection of famous art classics.</p>

        <?php include 'carousel.php'; ?>
        
        <?php include 'top_artworks.php'; ?>
        
        <?php include 'top_artist.php'; ?>

        <h2>Recent Reviews</h2>
        <?php include 'recent_reviews.php'; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>