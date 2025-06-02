<?php
// Startet oder setzt eine PHP-Session fort (wichtig für Benutzer-Logins, favorites etc.)
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
    <!-- Haupt-Navigation der Website -->
    <?php include __DIR__ . '/src/views/components/navigation.php'; ?>

    <div class="container mb-3">
        <h1 class="text-center">Welcome to the Art Gallery</h1>
        <p class="text-center">Explore our collection of famous art classics.</p>

        <!-- Karussell-Komponente (für 3 Random-Kunstwerke) -->
        <?php include __DIR__ . '/src/views/components/carousel.php'; ?>
        
        <!-- Komponente für die 3 Top-Kunstwerke -->
        <?php include __DIR__ . '/src/views/components/top_artworks.php'; ?>
        
        <!-- Komponente für die 3 beliebtesten Künstler -->
        <?php include __DIR__ . '/src/views/components/top_artist.php'; ?>

        <!-- Bereich für die neuesten Bewertungen -->
        <h2>Recent Reviews</h2>
        <?php include __DIR__ . '/src/views/components/recent_reviews.php'; ?>
    </div>

    <!-- Fußzeile der Website -->
    <?php include __DIR__ . '/src/views/components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>