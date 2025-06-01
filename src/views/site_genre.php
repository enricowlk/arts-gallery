<?php
session_start(); 

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../repositories/genreRepository.php'; 
require_once __DIR__ . '/../repositories/artworkRepository.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?message=Invalid or missing genre ID");
    exit();
}

$genreId = (int)$_GET['id'];

$genreRepo = new GenreRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

$genre = $genreRepo->getGenreById($genreId); 

if ($genre === null) {
    header("Location: error.php?message=Genre not found");
    exit();
}

$artworks = $artworkRepo->getAllArtworksForOneGenreByGenreId($genreId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $genre->getGenreName(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
    <style>
        .genre-image {
            width: 322px;
            height: 400px;
            object-fit: cover; /* Bild wird zugeschnitten, um den Container zu füllen */
            border-radius: 10px; /* Abgerundete Ecken */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten hinzufügen */
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <?php
                $imagePath = "../../images/genres/square-medium/" . $genre->getGenreID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "../../images/placeholder.png";
                }
                ?>
                <img src="<?php echo $imageUrl; ?>" class="genre-image" alt="<?php echo $genre->getGenreName(); ?>">
            </div>
            <div class="col-md-8">
                <div class="info">
                    <h1><?php echo $genre->getGenreName(); ?></h1>
                    <p>Era: <?php echo $genre->getEra(); ?></p>
                    <p><?php echo $genre->getDescription(); ?></p>
                    <br>
                    <p>You want to know more about <strong><?php echo $genre->getGenreName(); ?></strong>?</p>
                    <a href="<?php echo $genre->getLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <h2 class="mt-5">Artworks in <?php echo $genre->getGenreName(); ?></h2>
        <?php if (empty($artworks)){ ?>
            <p class="text-center">No artworks found in this genre.</p>
        <?php }else{ ?>
            <div class="row">
                <?php foreach ($artworks as $artwork){ 
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                    $imagePath = "../../images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "../../images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card artwork-card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="small">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <p class="small">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="../../images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>