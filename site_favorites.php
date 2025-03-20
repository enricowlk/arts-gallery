<?php
session_start();

require_once 'ArtworkRepository.php';
require_once 'ArtistRepository.php';

$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());

// Favoriten für Kunstwerke aus der Session holen
if (isset($_SESSION['favorite_artworks'])) {
    $favoriteArtworks = $_SESSION['favorite_artworks'];
} else {
    $favoriteArtworks = [];
}

// Favoriten für Künstler aus der Session holen
if (isset($_SESSION['favorite_artists'])) {
    $favoriteArtists = $_SESSION['favorite_artists'];
} else {
    $favoriteArtists = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1>My Favorites</h1>

        <!-- Favorisierte Künstler -->
        <h2 class="mt-4">Favorite Artists</h2>
        <?php if (!empty($favoriteArtists)){ ?>
            <div class="row">
                <?php foreach ($favoriteArtists as $artistId){ 
                    $artist = $artistRepo->getArtistById($artistId);
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                                <img src="images/artists/medium/<?php echo $artist->getArtistID(); ?>.jpg" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                <p class="card-text"><?php echo $artist->getNationality(); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <p>You have no favorite artists yet.</p>
        <?php } ?>

        <!-- Favorisierte Kunstwerke -->
        <h2 class="mt-4">Favorite Artworks</h2>
        <?php if (!empty($favoriteArtworks)){ ?>
            <div class="row">
                <?php foreach ($favoriteArtworks as $artworkId){ 
                    $artwork = $artworkRepo->getArtworkById($artworkId);
                    $artist = $artistRepo->getArtistById($artwork->getArtistID());
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="card-text">By <?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <p>You have no favorite artworks yet.</p>
        <?php } ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>