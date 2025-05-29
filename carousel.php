<?php
require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'database.php'; // Bindet die Database-Klasse ein
require_once 'artworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein

$artworkRepo = new ArtworkRepository(new Database()); // Erstellt eine Instanz von ArtworkRepository mit der Datenbankverbindung

$randomArtworks = $artworkRepo->get3RandomArtworks(); // Ruft 3 zufällige Kunstwerke aus der Datenbank ab
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css"> <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
</head>
<body>
    <div class="carousel-container">
        <!-- Bootstrap Carousel für die Anzeige der Kunstwerke -->
        <div id="artCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = true; // Steuert, welches Kunstwerk als erstes aktiv ist
                foreach ($randomArtworks as $artwork) {
                    $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if (file_exists($imagePath)) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
                    <!-- Carousel Item für jedes Kunstwerk -->
                    <div class="carousel-item <?php
                            if ($active) {
                                echo 'active'; // Setzt das erste Kunstwerk als aktiv
                            } else {
                                echo '';
                            }
                            ?>">
                        <!-- Link zur Detailseite des Kunstwerks -->
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <img src="<?php echo $imageUrl; ?>" class="d-block w-100" alt="<?php echo $artwork->getTitle(); ?>">
                        </a>
                        <!-- Titel des Kunstwerks als Bildunterschrift -->
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?php echo $artwork->getTitle(); ?></h5>
                        </div>
                    </div>
                    <?php
                    $active = false; // Deaktiviert das "active"-Flag nach dem ersten Kunstwerk
                }
                ?>
            </div>
            <!-- Carousel Controls (Vorheriger/Nächster Button) -->
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