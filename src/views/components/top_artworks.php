<?php
// Einbinden der benötigten Dateien für Datenbankverbindung und Artwork-Repository
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/artworkRepository.php'; 

// Instanziierung des ArtworkRepository
$artworkRepo = new ArtworkRepository(new Database());

// Abruf der Top 3 Kunstwerke basierend auf Bewertungen
$topArtworks = $artworkRepo->get3TopArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Container für die Top-Kunstwerke -->
    <div class="top-artworks-container">
        <h2>Top Artworks</h2>
        <?php if (!empty($topArtworks)) { ?>
            <div class="row">
                <?php foreach ($topArtworks as $artworkData) { 
                    // Extrahiere Kunstwerkobjekt und Durchschnittsbewertung aus den Daten
                    $artwork = $artworkData['artwork'];
                    $averageRating = $artworkData['averageRating'];
                    
                    // Pfad zum Kunstwerkbild erstellen und prüfen ob es existiert
                    $imagePath = "images/works/small/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);
                    
                    // Falls kein Bild existiert, Platzhalter verwenden
                    $imageUrl = $imageExists ? $imagePath : "images/placeholder.png";
                ?>
                    <div class="col-md-4">
                        <!-- Link zur Kunstwerk-Detailseite -->
                        <a href="../arts-gallery/src/views/site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card">
                            <!-- Kunstwerkbild -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="mt-2">
                                <!-- Titel des Kunstwerks -->
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <!-- Durchschnittliche Bewertung (1 Nachkommastelle) mit Stern-Icon -->
                                <p class="small">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Fallback, wenn keine Top-Kunstwerke gefunden wurden -->
            <p class="text-center">No top artworks found.</p>
        <?php } ?>
    </div>
</body>
</html>