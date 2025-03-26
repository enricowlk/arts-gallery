<?php
// Einbinden der benötigten Dateien
require_once 'database.php'; // Enthält die Datenbankverbindung
require_once 'ArtistRepository.php'; // Enthält die Klasse "ArtistRepository"

// Repository-Instanz erstellen
$artistRepo = new ArtistRepository(new Database());

// Die Top 3 Künstler aus der Datenbank abrufen
$topArtists = $artistRepo->getTop3Artists();
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
    <div class="top-artist-container">
        <?php if (!empty($topArtists)) { ?>
            <!-- Wenn Top-Künstler vorhanden sind, werden sie angezeigt -->
            <div class="row">
                <?php foreach ($topArtists as $artistData) { 
                    // Künstler-Objekt und Review-Anzahl aus den Daten extrahieren
                    $artist = $artistData['artist'];
                    $reviewCount = $artistData['reviewCount'];
                    $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                    
                    if (file_exists($imagePath)) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4">
                        <!-- Link zur Künstler-Detailseite -->
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                        <div class="card">
                            <!-- Künstlerbild anzeigen oder Platzhalter, falls Bild fehlt -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            <div class="card-body">
                                <!-- Künstlername anzeigen -->
                                <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                <!-- Anzahl der Reviews anzeigen -->
                                <p class="card-text">(<?php echo $reviewCount; ?> reviews)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Falls keine Top-Künstler gefunden wurden, eine Meldung anzeigen -->
            <p class="text-center">No top artists found.</p>
        <?php } ?>
    </div>
</body>
</html>