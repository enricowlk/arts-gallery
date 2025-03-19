<?php
require_once 'database.php';
require_once 'ArtworkRepository.php';

$artworkRepo = new ArtworkRepository(new Database());

$randomArtworks = $artworkRepo->get3RandomArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="carousel-container">
        <div id="artCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = true;
                foreach ($randomArtworks as $artwork) {
                    $artworkImage = "images/works/large/{$artwork->getImageFileName()}.jpg";
                    ?>
                    <div class="carousel-item <?php
                            if ($active) {
                                echo 'active';
                            } else {
                                echo '';
                            }
                            ?>">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <img src="<?php echo $artworkImage; ?>" class="d-block w-100" alt="<?php echo $artwork->getTitle(); ?>">
                        </a>
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?php echo $artwork->getTitle(); ?></h5>
                        </div>
                    </div>
                    <?php
                    $active = false;
                }
                ?>
            </div>
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#artCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#artCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</body>
</html>