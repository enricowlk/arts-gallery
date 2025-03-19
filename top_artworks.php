<?php
require_once 'database.php';
require_once 'ArtworkRepository.php';

// Repository-Instanzen erstellen
$artworkRepo = new ArtworkRepository(new Database());

$topArtworks = $artworkRepo->get3TopArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="top-artworks-container">
        <?php if (!empty($topArtworks)){ ?>
            <div class="row">
                <?php foreach ($topArtworks as $artworkData){ 
                    $artwork = $artworkData['artwork'];
                    $averageRating = $artworkData['averageRating'];
                ?>
                    <div class="col-md-4">
                        <div class="card">
                            <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <img src="images/works/small/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="card-text">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <p class="text-center">No top artworks found.</p>
        <?php } ?>
    </div>
</body>
</html>