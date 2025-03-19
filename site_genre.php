<?php
session_start();

require_once 'database.php';
require_once 'GenreRepository.php';
require_once 'ArtworkRepository.php';

if (isset($_GET['id'])) {
    $genreId = $_GET['id'];
} else {
    $genreId = null;
}

$genreRepo = new GenreRepository($pdo);
$artworkRepo = new ArtworkRepository($pdo);

$genre = $genreRepo->getGenreById($genreId);
$artworks = $artworkRepo->getAllArtworksForOneGenreByGenreId($genreId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $genre->getGenreName(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Genre-Bild */
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
                    <h1><?php echo $genre->getGenreName(); ?></h1>
                    <p class="lead">Era: <?php echo $genre->getEra(); ?></p>
                    <p><?php echo $genre->getDescription(); ?></p>
                    <br>
                    <p>You want to know more about <strong><?php echo $genre->getGenreName(); ?></strong>?</p>
                    <a href="<?php echo $genre->getLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Kunstwerke in diesem Genre -->
        <h2 class="mt-5 mb-4">Artworks in <?php echo $genre->getGenreName(); ?></h2>
        <?php if (empty($artworks)){ ?>
            <p class="text-center">No artworks found in this genre.</p>
        <?php }else{ ?>
            <div class="row">
                <?php foreach ($artworks as $artwork){ 
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card artwork-card">
                            <!-- Kunstwerkbild -->
                            <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="card-text">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
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

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>