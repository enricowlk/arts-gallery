<?php
session_start(); // Startet die Session

require_once 'database.php'; // Bindet die Datenbankverbindung ein
require_once 'ArtistRepository.php'; // Bindet das ArtistRepository ein
require_once 'ArtworkRepository.php'; // Bindet das ArtworkRepository ein

if (isset($_GET['query'])) {
    $query = $_GET['query']; // Speichert den Suchbegriff aus der URL
} else {
    $query = ''; // Setzt den Suchbegriff auf leer, falls keiner vorhanden ist
}

$artistRepo = new ArtistRepository(new Database()); // Erstellt eine Instanz des ArtistRepository
$artworkRepo = new ArtworkRepository(new Database()); // Erstellt eine Instanz des ArtworkRepository

$artists = $artistRepo->searchArtists($query); // Sucht nach Künstlern basierend auf dem Suchbegriff
$artworks = $artworkRepo->searchArtworks($query); // Sucht nach Kunstwerken basierend auf dem Suchbegriff
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigation ein -->

    <div class="container mt-3">
        <h1>Search Results for "<?php echo $query; ?>"</h1> <!-- Zeigt den Suchbegriff an -->

        <h2>Artists</h2>
        <?php if (empty($artists)){ ?>
            <p>No artists found.</p> <!-- Zeigt an, wenn keine Künstler gefunden wurden -->
        <?php }else{ ?>
            <ul class="list-unstyled">
                <?php foreach ($artists as $artist){ ?>
                    <li>
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                            <?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?>
                        </a> <!-- Zeigt den Namen des Künstlers und verlinkt zur Detailseite -->
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <h2>Artworks</h2>
        <?php if (empty($artworks)){ ?>
            <p>No artworks found.</p> <!-- Zeigt an, wenn keine Kunstwerke gefunden wurden -->
        <?php }else{ ?>
            <ul class="list-unstyled">
                <?php foreach ($artworks as $artwork){ ?>
                    <li>
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <?php echo $artwork->getTitle(); ?>
                        </a> <!-- Zeigt den Titel des Kunstwerks und verlinkt zur Detailseite -->
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>