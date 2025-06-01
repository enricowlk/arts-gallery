<?php
require_once __DIR__ . '/../../services/global_exception_handler.php';
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/artworkRepository.php'; 

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
    <div id="artCarousel" class="carousel slide shadow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $active = true;
            foreach ($randomArtworks as $artwork) {
                $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
            ?>
                <div class="carousel-item <?php
                        if ($active) {
                            echo 'active';
                        } else {
                            echo '';
                        }
                        ?>">
                    <a href="../arts-gallery/src/views/site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <img src="<?php echo $imageUrl; ?>" alt="<?php echo $artwork->getTitle(); ?>">
                    </a>
                    <div class="carousel-caption">
                        <h5><?php echo $artwork->getTitle(); ?></h5>
                    </div>
                </div>
                <?php
                $active = false;
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#artCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#artCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</body>
</html>