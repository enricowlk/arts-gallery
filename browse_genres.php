<?php
session_start();

require_once 'GenreRepository.php';

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

    <div class="container mt-3">
        <h1 class="text-center">Genres</h1>
        <div class="row">
            <?php foreach ($genres as $genre) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <a href="site_genre.php?id=<?php echo $genre->getGenreID(); ?>">
                            <img src="images/genres/square-medium/<?php echo $genre->getGenreID(); ?>.jpg" class="card-img-top" alt="<?php echo $genre->getGenreName(); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $genre->getGenreName(); ?></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>