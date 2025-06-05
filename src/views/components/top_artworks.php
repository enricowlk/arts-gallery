<?php
/**
 * Displays the top 3 artworks based on average ratings.
 * 
 * Uses the ArtworkRepository to retrieve artwork data and average ratings.
 */

// Required includes for DB connection and repository
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../repositories/artworkRepository.php';

// Instantiate the artwork repository
$artworkRepo = new ArtworkRepository(new Database());

/**
 * Retrieves the top 3 artworks ranked by average rating.
 * 
 * @var array $topArtworks Each item contains:
 *  - 'artwork' => Artwork object
 *  - 'averageRating' => float
 */
$topArtworks = $artworkRepo->get3TopArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top Artworks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Container for top artworks -->
    <div class="top-artworks-container container py-4 mt-3">
        <h2 class="mb-4 text-center">Top Artworks</h2>

        <?php if (!empty($topArtworks)) { ?>
            <div class="row g-4">
                <?php foreach ($topArtworks as $artworkData) {
                    $artwork = $artworkData['artwork'];
                    $averageRating = $artworkData['averageRating'];

                    $imagePath = "images/works/small/" . $artwork->getImageFileName() . ".jpg";
                    $imageUrl = file_exists($imagePath) ? $imagePath : "images/placeholder.png";
                ?>
                    <div class="col-md-4">
                        <a href="../arts-gallery/src/views/site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                                <div class="mt-3 text-center">
                                    <h5 class="card-title">
                                        <?php echo $artwork->getTitle(); ?>
                                    </h5>
                                    <p class="text-muted small">
                                        Rating: <?php echo number_format((float)$averageRating, 1); ?>
                                        <img src="images/icon_gelberStern.png" alt="Star" style="height: 16px; vertical-align: text-top;">
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-center text-muted">No top artworks found.</p>
        <?php } ?>
    </div>

</body>
</html>
