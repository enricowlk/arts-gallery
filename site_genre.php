<?php
session_start(); // Startet die Session

require_once 'database.php'; // Bindet die Database-Klasse ein
require_once 'GenreRepository.php'; // Bindet die GenreRepository-Klasse ein
require_once 'ArtworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein

// Überprüft, ob eine Genre-ID übergeben wurde
if (isset($_GET['id'])) {
    $genreId = $_GET['id']; // Holt die Genre-ID aus dem GET-Parameter
} else {
    $genreId = null; // Falls keine ID übergeben wurde
}

// Erstellt Instanzen der Repository-Klassen
$genreRepo = new GenreRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

// Holt die Genre-Daten und die zugehörigen Kunstwerke
$genre = $genreRepo->getGenreById($genreId); // Holt das Genre anhand der ID
$artworks = $artworkRepo->getAllArtworksForOneGenreByGenreId($genreId); // Holt die Kunstwerke des Genres
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Titel der Seite mit dem Namen des Genres -->
    <title><?php echo $genre->getGenreName(); ?> - Art Gallery</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Stil für das Genre-Bild */
        .genre-image {
            width: 322px;
            height: 400px;
            object-fit: cover; /* Bild wird zugeschnitten, um den Container zu füllen */
            border-radius: 10px; /* Abgerundete Ecken */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten hinzufügen */
        }
    </style>
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Genre-Informationen -->
        <div class="row">
            <div class="col-md-4">
                <!-- Genre-Bild -->
                <img src="images/genres/square-medium/<?php echo $genre->getGenreID(); ?>.jpg" class="genre-image" alt="<?php echo $genre->getGenreName(); ?>">
            </div>
            <div class="col-md-8">
                <div class="info">
                    <!-- Genre-Name -->
                    <h1><?php echo $genre->getGenreName(); ?></h1>
                    <!-- Epoche des Genres -->
                    <p class="lead">Era: <?php echo $genre->getEra(); ?></p>
                    <!-- Beschreibung des Genres -->
                    <p><?php echo $genre->getDescription(); ?></p>
                    <br>
                    <!-- Link zu weiteren Informationen -->
                    <p>You want to know more about <strong><?php echo $genre->getGenreName(); ?></strong>?</p>
                    <a href="<?php echo $genre->getLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Kunstwerke in diesem Genre -->
        <h2 class="mt-5 mb-4">Artworks in <?php echo $genre->getGenreName(); ?></h2>
        <?php if (empty($artworks)){ ?>
            <!-- Meldung, falls keine Kunstwerke gefunden wurden -->
            <p class="text-center">No artworks found in this genre.</p>
        <?php }else{ ?>
            <div class="row">
                <?php foreach ($artworks as $artwork){ 
                    // Holt die durchschnittliche Bewertung des Kunstwerks
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card artwork-card">
                            <!-- Kunstwerkbild -->
                            <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            </a>
                            <div class="card-body">
                                <!-- Titel des Kunstwerks -->
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <!-- Jahr des Kunstwerks -->
                                <p class="card-text">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <!-- Durchschnittliche Bewertung -->
                                <p class="card-text">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>