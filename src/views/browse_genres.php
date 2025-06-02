<?php
session_start();

// Einbinden des globalen Exception Handlers und Genre-Repository
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/genreRepository.php'; 

// GenreRepository instanziieren
$genreRepo = new GenreRepository(new Database()); 

// Alle Genres aus der Datenbank abrufen
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
    <?php include __DIR__ . '/components/navigation.php'; ?> 

    <div class="container">
        <h1 class="text-center">Genres</h1>
        <div class="row">
            <?php foreach ($genres as $genre) {  
                // Bildpfad für das Genre erstellen
                $imagePath = "../../images/genres/square-medium/" . $genre->getGenreID() . ".jpg";
                // Prüfen ob Bild existiert
                $imageExists = file_exists($imagePath);

                // entweder Genre-Bild oder Platzhalter
                $imageUrl = $imageExists ? $imagePath : "../../images/placeholder.png";
                ?>
                
                <div class="col-md-4 mb-4">
                    <!-- Link zur Genre-Detailseite mit Genre-ID als Parameter -->
                    <a href="site_genre.php?id=<?php echo $genre->getGenreID(); ?>">
                    <div class="card">
                        <!-- Genre-Bild anzeigen -->
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $genre->getGenreName(); ?>">
                        <div class="card-body">
                            <!-- Genre-Name anzeigen -->
                            <h5 class="card-title"><?php echo $genre->getGenreName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>