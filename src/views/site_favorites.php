<?php
// Start session to access favorites data
session_start();

// Include required files
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/artworkRepository.php'; 
require_once __DIR__ . '/../repositories/artistRepository.php'; 

// Create repository instances
$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());

// Load favorites from session or initialize empty arrays
if (isset($_SESSION['favorite_artworks'])) {
    $favoriteArtworks = $_SESSION['favorite_artworks']; // Listed artworks
} else {
    $favoriteArtworks = []; // Empty array if no favorites
}

if (isset($_SESSION['favorite_artists'])) {
    $favoriteArtists = $_SESSION['favorite_artists']; // Listed artists
} else {
    $favoriteArtists = []; // Empty array if no favorites
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container">
        <h1 class="text-center">My Favorites</h1>

        <h2>Favorite Artists</h2>
        <?php if (!empty($favoriteArtists)){ ?>
            <div class="row">
                <?php foreach ($favoriteArtists as $artistId){ 
                    // Load artist data
                    $artist = $artistRepo->getArtistById($artistId);
                    // Create and check image path
                    $imagePath = "../../images/artists/medium/" . $artist->getArtistID() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    // Artist image or placeholder
                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "../../images/placeholder.png";
                    }
                ?>
                    <!-- Artist card -->
                    <div class="col-md-4 mb-4">
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                        <div class="card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                <p class="small">(<?php echo $artist->getYearOfBirth(); ?> - <?php echo $artist->getYearOfDeath(); ?>)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <!-- Message if no artist favorites available -->
            <p>You have no favorite artists yet.</p>
        <?php } ?>


        <h2 class="mt-4">Favorite Artworks</h2>
        <?php if (!empty($favoriteArtworks)){ ?>
            <div class="row">
                <?php foreach ($favoriteArtworks as $artworkId){ 
                    // Load artwork data
                    $artwork = $artworkRepo->getArtworkById($artworkId);
                    // Load artist data
                    $artist = $artistRepo->getArtistById($artwork->getArtistID());
                    // Create and check image path
                    $imagePath = "../../images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    // Artwork image or placeholder
                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "../../images/placeholder.png";
                    }
                ?>
                    <!-- Artwork card -->
                    <div class="col-md-4 mb-4">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="small">By <?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <!-- Message if no artwork favorites available -->
            <p>You have no favorite artworks yet.</p>
        <?php } ?>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
