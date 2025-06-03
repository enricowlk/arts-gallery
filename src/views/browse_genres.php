<?php
/**
 * Genres browse Page
 *
 * This script initializes the session, sets up the exception handler, retrieves all genres from the database,
 * and renders them in a Bootstrap-based grid layout. Each genre is displayed as a card with an image and a name.
 * If a genre-specific image is not available, a placeholder image is used.
 */

session_start();

// Include the global exception handler and the GenreRepository
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/genreRepository.php'; 

/**
 * Instantiate the GenreRepository using a Database connection.
 *
 * @var GenreRepository $genreRepo Repository for handling genre-related database operations.
 */
$genreRepo = new GenreRepository(new Database()); 

/**
 * Retrieve all genres from the database.
 *
 * @var Genre[] $genres Array of Genre objects.
 */
$genres = $genreRepo->getAllGenres(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php 
    include __DIR__ . '/components/navigation.php'; 
    ?> 

    <div class="container">
        <h1 class="text-center">Genres</h1>
        <div class="row">
            <?php foreach ($genres as $genre) {  
                /**
                 * Construct image path for each genre based on genre ID.
                 *
                 * @var string $imagePath Path to the genre image.
                 * @var bool $imageExists Whether the image file exists.
                 * @var string $imageUrl URL to display (genre image or placeholder).
                 */
                $imagePath = "../../images/genres/square-medium/" . $genre->getGenreID() . ".jpg";
                $imageExists = file_exists($imagePath);
                $imageUrl = $imageExists ? $imagePath : "../../images/placeholder.png";
                ?>
                
                <div class="col-md-4 mb-4">
                    <!-- Link to genre detail page with genre ID as parameter -->
                    <a href="site_genre.php?id=<?php echo $genre->getGenreID(); ?>">
                        <div class="card">
                            <!-- Display genre image -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $genre->getGenreName(); ?>">
                            <div class="card-body">
                                <!-- Display genre name -->
                                <h5 class="card-title"><?php echo $genre->getGenreName(); ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php 
    include __DIR__ . '/components/footer.php'; 
    ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
