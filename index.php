<?php
// Starts or resumes a PHP session (important for user login data etc.)
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
    <!-- Main navigation of the website -->
    <?php include __DIR__ . '/src/views/components/navigation.php'; ?>

    <div class="container mb-3">
        <h1 class="text-center">Welcome to the Art Gallery</h1>
        <p class="text-center">Explore our collection of famous art classics.</p>

        <!-- Carousel component (for 3 random artworks) -->
        <?php include __DIR__ . '/src/views/components/carousel.php'; ?>
        
        <!-- Component for the top 3 artworks -->
        <?php include __DIR__ . '/src/views/components/top_artworks.php'; ?>
        
        <!-- Component for the top 3 most popular artists -->
        <?php include __DIR__ . '/src/views/components/top_artist.php'; ?>

        <!-- Section for the most recent reviews -->
        <h2 class="text-center">Recent Reviews</h2>
        <?php include __DIR__ . '/src/views/components/recent_reviews.php'; ?>
    </div>

    <!-- Website footer -->
    <?php include __DIR__ . '/src/views/components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
