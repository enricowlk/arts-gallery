<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <!-- Font Awesome für Social-Media-Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
</head>
<body>
    <!-- Platzhalter für den Hauptinhalt -->
    <div class="content">
        <!-- Der Hauptinhalt der Seite wird hier eingefügt -->
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Über uns -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>About Us</h5>
                    <p class="text-white">Discover the world of art through our curated collection of masterpieces. We bring you the best from classic and contemporary artists.</p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="browse_artists.php" class="text-white text-decoration-none">Artists</a></li>
                        <li><a href="browse_artworks.php" class="text-white text-decoration-none">Artworks</a></li>
                        <li><a href="site_favorites.php" class="text-white text-decoration-none">Favorites</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="icons">
                        <a href="https://facebook.com" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="https://instagram.com" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="https://pinterest.com" class="text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center pt-3 border-top border-secondary mt-3">
                <p class="mb-0 text-white">&copy; <?php echo date("Y"); ?> Art Gallery. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>