<?php
session_start(); // Startet die Session

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'database.php'; // Bindet die Database-Klasse ein
require_once 'artistRepository.php'; // Bindet die ArtistRepository-Klasse ein
require_once 'artworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein

// Überprüft, ob eine gültige Künstler-ID übergeben wurde
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?message=Invalid or missing artist ID");
    exit();
}

$artistId = (int)$_GET['id']; // Holt und validiert die Künstler-ID

// Erstellt Instanzen der Repository-Klassen
$artistRepo = new ArtistRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

// Holt die Künstlerdaten
$artist = $artistRepo->getArtistById($artistId); // Holt den Künstler anhand der ID

// Überprüft, ob der Künstler existiert
if ($artist === null) {
    header("Location: error.php?message=Artist not found");
    exit();
}

// Holt die zugehörigen Kunstwerke
$artworks = $artworkRepo->getAllArtworksForOneArtistByArtistId($artistId); // Holt die Kunstwerke des Künstlers

// Überprüft, ob der Künstler in den Favoriten ist
$isFavoriteArtist = isset($_SESSION['favorite_artists']) && in_array($artistId, $_SESSION['favorite_artists']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Titel der Seite mit dem Namen des Künstlers -->
    <title><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?> - Art Gallery</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Künstlerinformationen -->
        <div class="row">
            <div class="col-md-6">
                <!-- Künstlerbild -->
                <?php
                $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if (file_exists($imagePath)) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <img src="<?php echo $imageUrl; ?>" 
                class="artist-image-small rounded shadow-sm border border-secondary" 
                loading="lazy"
                alt="Portrait of <?php echo $artist->getFirstName() . ' ' . $artist->getLastName(); ?>">


                <!-- Button zum Hinzufügen/Entfernen des Künstlers aus den Favoriten -->
                <form action="favorites_process.php" method="post" class="mt-3">
                    <input type="hidden" name="artist_id" value="<?php echo $artistId; ?>">
                    <button type="submit" class="btn 
                        <?php
                            if ($isFavoriteArtist) {
                                echo 'btn-danger'; // Roter Button, wenn der Künstler in den Favoriten ist
                            } else {
                                echo 'btn-secondary'; // Grauer Button, wenn der Künstler nicht in den Favoriten ist
                            }
                        ?>">
                        <?php
                            if ($isFavoriteArtist) {
                                echo 'Remove Artist from Favorites'; // Text für den Button, wenn der Künstler in den Favoriten ist
                            } else {
                                echo 'Add Artist to Favorites'; // Text für den Button, wenn der Künstler nicht in den Favoriten ist
                            }
                        ?>
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="info">
                    <!-- Künstlername -->
                    <h1><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h1>
                    <!-- Nationalität und Lebensdaten -->
                    <p class="lead"><?php echo $artist->getNationality(); ?> (<?php echo $artist->getYearOfBirth(); ?> - <?php echo $artist->getYearOfDeath(); ?>)</p>
                    <!-- Details zum Künstler -->
                    <p><?php echo $artist->getDetails(); ?></p>
                    <br>
                    <!-- Link zu weiteren Informationen -->
                    <p> You want to know more about <strong><?php echo $artist->getFirstName(); ?> <?php echo $artist->getLastName(); ?></strong>?</p>
                    <a href="<?php echo $artist->getArtistLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Kunstwerke des Künstlers -->
        <h2 class="mt-5 mb-4">Artworks by <?php echo $artist->getLastName(); ?></h2>
        <?php if (empty($artworks)){ ?>
            <!-- Meldung, falls keine Kunstwerke gefunden wurden -->
            <p class="text-center">No artworks found for this artist.</p>
        <?php }else{ ?>
            <div class="row">
                <?php foreach ($artworks as $artwork){ 
                    // Holt die durchschnittliche Bewertung des Kunstwerks
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                    // Artwork Image Path and Exists Check 
                    $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if (file_exists($imagePath)) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <!-- Link zur Kunstwerkseite -->
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card artwork-card">
                            <!-- Kunstwerkbild -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <!-- Titel des Kunstwerks -->
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <!-- Jahr des Kunstwerks -->
                                <p class="card-text">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <!-- Durchschnittliche Bewertung -->
                                <p class="card-text">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>