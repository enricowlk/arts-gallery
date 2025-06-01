<?php
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/artworkRepository.php'; 

$artworkRepo = new ArtworkRepository(new Database());

$topArtworks = $artworkRepo->get3TopArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <div class="top-artworks-container">
        <h2>Top Artworks</h2>
        <?php if (!empty($topArtworks)) { ?>
            <div class="row">
                <?php foreach ($topArtworks as $artworkData) { 
                    $artwork = $artworkData['artwork'];
                    $averageRating = $artworkData['averageRating'];
                    $imagePath = "images/works/small/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="mt-2">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="small">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-center">No top artworks found.</p>
        <?php } ?>
    </div>
</body>
</html>