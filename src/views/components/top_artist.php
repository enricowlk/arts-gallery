<?php
// Einbinden der benötigten Dateien für Datenbankverbindung und Artist-Repository
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/artistRepository.php'; 

// Instanziierung des ArtistRepository
$artistRepo = new ArtistRepository(new Database());

// Abruf der Top 3 Künstler basierend auf Bewertungen
$topArtists = $artistRepo->getTop3Artists();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Container für die Top-Künstler -->
    <div class="top-artist-container">
        <h2>Top Artists</h2>
        <?php if (!empty($topArtists)) { ?>
            <div class="row">
                <?php foreach ($topArtists as $artistData) { 
                    // Extrahiere Künstlerobjekt und Bewertungsanzahl aus den Daten
                    $artist = $artistData['artist'];
                    $reviewCount = $artistData['reviewCount'];
                    
                    // Pfad zum Künstlerbild erstellen und prüfen ob es existiert
                    $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                    $imageExists = file_exists($imagePath);
                    
                    // falls kein Bild existiert, Platzhalter verwenden
                    $imageUrl = $imageExists ? $imagePath : "images/placeholder.png";
                ?>
                    <div class="col-md-4">
                        <!-- Link zur Künstler-Detailseite -->
                        <a href="../arts-gallery/src/views/site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                        <div class="card">
                            <!-- Künstlerbild -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            <div class="mt-2">
                                <!-- Künstlername (Nachname, Vorname) -->
                                <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                <!-- Anzahl der Bewertungen -->
                                <p class="small">(<?php echo $reviewCount; ?> reviews)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Fallback, wenn keine Top-Künstler gefunden wurden -->
            <p class="text-center">No top artists found.</p>
        <?php } ?>
    </div>
</body>
</html>