<?php
session_start();

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . 'artworkRepository.php'; 
require_once __DIR__ . 'artistRepository.php'; 

$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());

if (isset($_SESSION['favorite_artworks'])) {
    $favoriteArtworks = $_SESSION['favorite_artworks']; 
} else {
    $favoriteArtworks = []; 
}

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
    <?php include __DIR__ . 'navigation.php'; ?>

    <div class="container">
        <h1 class="text-center">My Favorites</h1>

        <h2>Favorite Artists</h2>
        <?php if (!empty($favoriteArtists)){ ?>
            <div class="row">
                <?php foreach ($favoriteArtists as $artistId){ 
                    $artist = $artistRepo->getArtistById($artistId);
                    $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
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
            <p>You have no favorite artists yet.</p>
        <?php } ?>

        <h2 class="mt-4">Favorite Artworks</h2>
        <?php if (!empty($favoriteArtworks)){ ?>
            <div class="row">
                <?php foreach ($favoriteArtworks as $artworkId){ 
                    $artwork = $artworkRepo->getArtworkById($artworkId);
                    $artist = $artistRepo->getArtistById($artwork->getArtistID());
                    $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
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
            <p>You have no favorite artworks yet.</p>
        <?php } ?>
    </div>

    <?php include __DIR__ . 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>