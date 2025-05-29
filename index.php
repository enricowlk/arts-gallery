<?php
session_start(); // Startet die Session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Gallery - Home</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <!-- Hauptinhalt -->
    <div class="container text-center" >
        <!-- Willkommensnachricht -->
        <h1>Welcome to the Art Gallery</h1>
        <p>Explore our collection of famous art classics.</p>

        <!-- Carousel einbinden -->
        <?php include 'carousel.php'; ?>
    </div>
    <div class="container">
        <!-- Top Kunstwerke -->
        <h2>Top Artworks</h2>
        <?php include 'top_artworks.php'; ?>
    </div>
    <div class="container">
        <!-- Top Künstler -->
        <h2>Top Artists</h2>
        <?php include 'top_artist.php'; ?>
    </div>
    <div class="container">
        <!-- Neueste Bewertungen -->
        <h2>Recent Reviews</h2>
        <?php include 'recent_reviews.php'; ?>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>