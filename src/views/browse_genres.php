<?php
session_start();

require_once 'global_exception_handler.php';
require_once 'genreRepository.php'; 

$genreRepo = new GenreRepository(new Database()); 

$genres = $genreRepo->getAllGenres(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> 

    <div class="container">
        <h1 class="text-center">Genres</h1>
        <div class="row">
            <?php foreach ($genres as $genre) {  
                $imagePath = "images/genres/square-medium/" . $genre->getGenreID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <div class="col-md-4 mb-4">
                    <a href="site_genre.php?id=<?php echo $genre->getGenreID(); ?>">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $genre->getGenreName(); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $genre->getGenreName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>