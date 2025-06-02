<?php
// Einbindung der benötigten Dateien
require_once __DIR__ . '/../../services/global_exception_handler.php'; 
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/artworkRepository.php'; 

// Initialisiert das ArtworkRepository mit Datenbankverbindung
$artworkRepo = new ArtworkRepository(new Database());

// Holt 3 zufällige Kunstwerke aus der Datenbank
$randomArtworks = $artworkRepo->get3RandomArtworks(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <!-- Bootstrap Carousel für die Kunstwerke -->
    <div id="artCarousel" class="carousel slide shadow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $active = true; // Markiert das erste Element als aktiv
            
            // Durchläuft alle zufälligen Kunstwerke
            foreach ($randomArtworks as $artwork) {
                // Pfad zum Kunstwerk-Bild
                $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                $imageExists = file_exists($imagePath);

                // Fallback auf Platzhalter, wenn Bild nicht existiert
                $imageUrl = $imageExists ? $imagePath : "images/placeholder.png";
            ?>
                <!-- Carousel Item für jedes Kunstwerk -->
                <div class="carousel-item <?= $active ? 'active' : '' ?>">
                    <!-- Link zur Detailseite des Kunstwerks -->
                    <a href="../arts-gallery/src/views/site_artwork.php?id=<?= $artwork->getArtWorkID() ?>">
                        <!-- Kunstwerk-Bild -->
                        <img src="<?= $imageUrl ?>" alt="<?= $artwork->getTitle() ?>">
                    </a>
                    <!-- Bildunterschrift mit Titel -->
                    <div class="carousel-caption">
                        <h5><?= $artwork->getTitle() ?></h5>
                    </div>
                </div>
                <?php
                $active = false; // Nur das erste Element ist aktiv
            }
            ?>
        </div>
        
        <!-- Vorheriger Button -->
        <button class="carousel-control-prev" type="button" data-bs-target="#artCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        
        <!-- Nächster Button -->
        <button class="carousel-control-next" type="button" data-bs-target="#artCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</body>
</html>