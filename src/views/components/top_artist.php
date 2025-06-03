<?php
/**
 * Displays the top 3 artists based on user reviews.
 * 
 * This script fetches artist data and review counts using the ArtistRepository.
 * If no artist image is found, a placeholder image is used.
 */

// Include necessary files for database connection and the artist repository
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../repositories/artistRepository.php';

// Instantiate the ArtistRepository
$artistRepo = new ArtistRepository(new Database());

/**
 * Retrieves top 3 artists ranked by review count or average rating.
 * 
 * @var array $topArtists Each item contains:
 *  - 'artist' => Artist object
 *  - 'reviewCount' => int
 */
$topArtists = $artistRepo->getTop3Artists();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top Artists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Container for the top artists display -->
    <div class="top-artist-container container py-4">
        <h2 class="mb-4 text-center">Top Artists</h2>

        <?php if (!empty($topArtists)) { ?>
            <div class="row g-4">
                <?php foreach ($topArtists as $artistData) {
                    // Extract artist object and review count
                    $artist = $artistData['artist'];
                    $reviewCount = $artistData['reviewCount'];

                    // Determine artist image path and fallback
                    $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                    $imageUrl = file_exists($imagePath) ? $imagePath : "images/placeholder.png";
                ?>
                    <div class="col-md-4">
                        <a href="../arts-gallery/src/views/site_artist.php?id=<?php echo $artist->getArtistID(); ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                                <div class="mt-3 text-center">
                                    <h5 class="card-title">
                                        <?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?>
                                    </h5>
                                    <p class="text-muted small">(<?php echo $reviewCount; ?> reviews)</p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Message displayed when no artists are found -->
            <p class="text-center text-muted">No top artists found.</p>
        <?php } ?>
    </div>

</body>
</html>
