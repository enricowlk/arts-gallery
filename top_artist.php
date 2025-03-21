<?php

require_once 'database.php';
require_once 'ArtistRepository.php';

// Repository-Instanzen erstellen
$artistRepo = new ArtistRepository(new Database());


$topArtists = $artistRepo->getTop3Artists();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="top-artist-container">
        <?php if (!empty($topArtists)){ ?>
            <div class="row">
                <?php foreach ($topArtists as $artistData){ 
                    $artist = $artistData['artist'];
                    $reviewCount = $artistData['reviewCount'];
                ?>
                    <div class="col-md-4">
                        <div class="card">
                            <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                                <img src="images/artists/medium/<?php echo $artist->getArtistID(); ?>.jpg" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                <p class="card-text">(<?php echo $reviewCount; ?> reviews)</p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <p class="text-center">No top artists found.</p>
        <?php } ?>
    </div>
</body>
</html>