<?php
/**
 * Initializes the environment and displays a Bootstrap carousel with 3 random artworks.
 * 
 * Loads required service files, connects to the database,
 * retrieves three random artworks, and generates a frontend carousel to display them.
 */

// Includes
require_once __DIR__ . '/../../services/global_exception_handler.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../repositories/artworkRepository.php';

// Initialize the ArtworkRepository with a database connection
$artworkRepo = new ArtworkRepository(new Database());

/**
 * Retrieve three random artworks from the database.
 * 
 * @var array $randomArtworks Array of Artwork objects
 */
$randomArtworks = $artworkRepo->get3RandomArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="artCarousel" class="carousel slide shadow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $active = true; // Marks the first carousel item as active

            /**
             * Loop through the array of artworks and create carousel items.
             *
             * @var object $artwork Artwork object containing artwork details
             */
            foreach ($randomArtworks as $artwork) {
                // Construct the path to the artwork image
                $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                $imageExists = file_exists($imagePath);

                // Use a placeholder image if the original image doesn't exist
                $imageUrl = $imageExists ? $imagePath : "images/placeholder.png";
            ?>
                <!-- Carousel item for each artwork -->
                <div class="carousel-item <?= $active ? 'active' : '' ?>">
                    <!-- Link to the artwork detail page -->
                    <a href="../arts-gallery/src/views/site_artwork.php?id=<?= $artwork->getArtWorkID() ?>">
                        <!-- Artwork image -->
                        <img src="<?= $imageUrl ?>" alt="<?= $artwork->getTitle() ?>">
                    </a>
                    <!-- Caption displaying the artwork title -->
                    <div class="carousel-caption">
                        <h5><?= $artwork->getTitle() ?></h5>
                    </div>
                </div>
                <?php
                $active = false; // Only the first item should be active
            }
            ?>
        </div>

        <!-- Previous button -->
        <button class="carousel-control-prev" type="button" data-bs-target="#artCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>

        <!-- Next button -->
        <button class="carousel-control-next" type="button" data-bs-target="#artCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</body>
</html>
