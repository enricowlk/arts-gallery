<?php
// Einbinden der benötigten Dateien
require_once 'database.php'; // Enthält die Datenbankverbindung
require_once 'ArtworkRepository.php'; // Enthält die Klasse "ArtworkRepository"

// Repository-Instanz erstellen
$artworkRepo = new ArtworkRepository(new Database());

// Die Top 3 Kunstwerke aus der Datenbank abrufen
$topArtworks = $artworkRepo->get3TopArtworks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Einbinden von Bootstrap und benutzerdefinierten CSS-Dateien -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="top-artworks-container">
        <?php if (!empty($topArtworks)) { ?>
            <!-- Wenn Top-Kunstwerke vorhanden sind, werden sie angezeigt -->
            <div class="row">
                <?php foreach ($topArtworks as $artworkData) { 
                    // Kunstwerk-Objekt und durchschnittliche Bewertung aus den Daten extrahieren
                    $artwork = $artworkData['artwork'];
                    $averageRating = $artworkData['averageRating'];
                ?>
                    <div class="col-md-4">
                        <!-- Link zur Kunstwerk-Detailseite -->
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card">
                                <!-- Kunstwerkbild anzeigen -->
                                <img src="images/works/small/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <!-- Titel des Kunstwerks anzeigen -->
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <!-- Durchschnittliche Bewertung anzeigen -->
                                <p class="card-text">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Falls keine Top-Kunstwerke gefunden wurden, eine Meldung anzeigen -->
            <p class="text-center">No top artworks found.</p>
        <?php } ?>
    </div>
</body>
</html>