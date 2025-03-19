<?php
session_start();

require_once 'database.php';
require_once 'ArtistRepository.php';
require_once 'ArtworkRepository.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
} else {
    $query = '';
}

$artistRepo = new ArtistRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

$artists = $artistRepo->searchArtists($query);
$artworks = $artworkRepo->searchArtworks($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1>Search Results for "<?php echo $query; ?>"</h1>

        <h2>Artists</h2>
        <?php if (empty($artists)){ ?>
            <p>No artists found.</p>
        <?php }else{ ?>
            <ul class="list-unstyled">
                <?php foreach ($artists as $artist){ ?>
                    <li>
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                            <?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <h2>Artworks</h2>
        <?php if (empty($artworks)){ ?>
            <p>No artworks found.</p>
        <?php }else{ ?>
            <ul class="list-unstyled">
                <?php foreach ($artworks as $artwork){ ?>
                    <li>
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <?php echo $artwork->getTitle(); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>