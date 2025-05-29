<?php
session_start(); // Startet die Session

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'genreRepository.php'; // Bindet die GenreRepository-Klasse ein

$genreRepo = new GenreRepository(new Database()); // Erstellt eine Instanz von GenreRepository mit der Datenbankverbindung

$genres = $genreRepo->getAllGenres(); // Ruft alle Genres aus der Datenbank ab
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genres</title>
    <!-- Bindet Bootstrap CSS und benutzerdefinierte CSS-Datei ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigationsleiste ein -->

    <div class="container mt-3">
        <h1 class="text-center">Genres</h1> <!-- Überschrift -->
        <div class="row">
            <?php foreach ($genres as $genre) {  // Schleife durch alle Genres
                $imagePath = "images/genres/square-medium/" . $genre->getGenreID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if (file_exists($imagePath)) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <div class="col-md-4 mb-4">
                    <!-- Link zur Genre-Detailseite mit Bild -->
                    <a href="site_genre.php?id=<?php echo $genre->getGenreID(); ?>">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $genre->getGenreName(); ?>">
                        <div class="card-body">
                            <!-- Name des Genres -->
                            <h5 class="card-title"><?php echo $genre->getGenreName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <!-- Bindet Bootstrap JavaScript ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>