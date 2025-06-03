<?php
/**
 * Artist Detail Page
 *
 * This script handles the display of a single artist's detailed information, including:
 * - Artist metadata (name, nationality, years, biography)
 * - Artist portrait image
 * - A button to add/remove the artist from the user's favorites
 * - A list of artworks created by the artist, each with an image, title, year, and average rating
 */

session_start(); // Enable session for access to user-specific data

// Include necessary services and repositories
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../repositories/artistRepository.php';
require_once __DIR__ . '/../repositories/artworkRepository.php';

// Validate the incoming artist ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /components/error.php?message=Invalid or missing artist ID");
    exit();
}

$artistId = (int)$_GET['id']; // Cast GET parameter to integer

// Create repository instances
$artistRepo = new ArtistRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

// Fetch artist data
$artist = $artistRepo->getArtistById($artistId);

// Redirect if artist not found
if ($artist === null) {
    header("Location: /components/error.php?message=Artist not found");
    exit();
}

// Fetch all artworks by this artist
$artworks = $artworkRepo->getAllArtworksForOneArtistByArtistId($artistId);

// Determine if artist is in the user's favorites list
$isFavoriteArtist = isset($_SESSION['favorite_artists']) && in_array($artistId, $_SESSION['favorite_artists']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-3">
        <div class="row">
            <!-- Left column: artist portrait and favorite button -->
            <div class="col-md-6">
                <?php
                $imagePath = "../../images/artists/medium/" . $artist->getArtistID() . ".jpg";
                $imageUrl = file_exists($imagePath) ? $imagePath : "../../images/placeholder.png";
                ?>
                <img src="<?php echo $imageUrl; ?>" 
                     class="artist-image-small rounded border shadow" 
                     alt="Portrait of <?php echo $artist->getFirstName() . ' ' . $artist->getLastName(); ?>">

                <form action="../controllers/favorites_process.php" method="post" class="mt-3">
                    <input type="hidden" name="artist_id" value="<?php echo $artistId; ?>">
                    <button type="submit" class="btn <?php echo $isFavoriteArtist ? 'btn-danger' : 'btn-secondary'; ?>">
                        <?php echo $isFavoriteArtist ? 'Remove Artist from Favorites' : 'Add Artist to Favorites'; ?>
                    </button>
                </form>
            </div>

            <!-- Right column: artist bio -->
            <div class="col-md-6">
                <div class="info">
                    <h1><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h1>
                    <p><?php echo $artist->getNationality(); ?> (<?php echo $artist->getYearOfBirth(); ?> - <?php echo $artist->getYearOfDeath(); ?>)</p>
                    <p><?php echo $artist->getDetails(); ?></p>
                    <br>
                    <p>You want to know more about <strong><?php echo $artist->getFirstName() . ' ' . $artist->getLastName(); ?></strong>?</p>
                    <a href="<?php echo $artist->getArtistLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Artwork section -->
        <h2 class="mt-5">Artworks by <?php echo $artist->getLastName(); ?></h2>
        <?php if (empty($artworks)) { ?>
            <p class="text-center">No artworks found for this artist.</p>
        <?php } else { ?>
            <div class="row">
                <?php foreach ($artworks as $artwork) { 
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                    $imagePath = "../../images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageUrl = file_exists($imagePath) ? $imagePath : "../../images/placeholder.png";
                ?>
                    <div class="col-md-4 mb-4">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card artwork-card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="small">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <p class="small">
                                    Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="../../images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
