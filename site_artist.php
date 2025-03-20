<?php
session_start();

require_once 'database.php';
require_once 'ArtistRepository.php';
require_once 'ArtworkRepository.php';

if (isset($_GET['id'])) {
    $artistId = $_GET['id'];
} else {
    $artistId = null;
}

$artistRepo = new ArtistRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

$artist = $artistRepo->getArtistById($artistId);
$artworks = $artworkRepo->getAllArtworksForOneArtistByArtistId($artistId);

// Prüfen, ob der Künstler in den Favoriten ist
$isFavoriteArtist = isset($_SESSION['favorite_artists']) && in_array($artistId, $_SESSION['favorite_artists']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Künstlerinformationen -->
        <div class="row">
            <div class="col-md-4">
                <!-- Künstlerbild -->
                <img src="images/artists/medium/<?php echo $artist->getArtistID(); ?>.jpg" class="artist-image" alt="<?php echo $artist->getLastName(); ?>">
                <!-- Add to Favorites/Remove Button für den Künstler -->
                <form action="favorites_process.php" method="post" class="mt-3">
                        <input type="hidden" name="artist_id" value="<?php echo $artistId; ?>">
                        <button type="submit" class="btn 
                            <?php
                                if ($isFavoriteArtist) {
                                    echo 'btn-danger';
                                } else {
                                    echo 'btn-secondary';
                                }
                            ?>">
                            <?php
                                if ($isFavoriteArtist) {
                                    echo 'Remove Artist from Favorites';
                                } else {
                                    echo 'Add Artist to Favorites';
                                }
                            ?>
                        </button>
                    </form>
            </div>
            <div class="col-md-8">
                <div class="info">
                    <h1><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h1>
                    <p class="lead"><?php echo $artist->getNationality(); ?> (<?php echo $artist->getYearOfBirth(); ?> - <?php echo $artist->getYearOfDeath(); ?>)</p>
                    <p><?php echo $artist->getDetails(); ?></p>
                    <br>
                    <p> You want to know more about <strong><?php echo $artist->getFirstName(); ?> <?php echo $artist->getLastName(); ?></strong>?</p>
                    <a href="<?php echo $artist->getArtistLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Kunstwerke des Künstlers -->
        <h2 class="mt-5 mb-4">Artworks by <?php echo $artist->getLastName(); ?></h2>
        <?php if (empty($artworks)){ ?>
            <p class="text-center">No artworks found for this artist.</p>
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