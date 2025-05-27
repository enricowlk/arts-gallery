<?php
session_start(); // Startet die Session

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'artistRepository.php'; // Bindet die ArtistRepository-Klasse ein

$artistRepo = new ArtistRepository(new Database()); // Erstellt eine Instanz von ArtistRepository mit der Datenbankverbindung

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ASC'; // Standard-Sortierreihenfolge
}

$artists = $artistRepo->getAllArtists($order); // Ruft alle Künstler aus der Datenbank ab
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
    <!-- Bindet Bootstrap CSS und benutzerdefinierte CSS-Datei ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigationsleiste ein -->

    <div class="container mt-3">
        <h1 class="text-center">Artists</h1> <!-- Überschrift -->
        
        <!-- Sortieroptionen -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Order: <?php echo $order; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?order=ASC">Ascending</a></li>
                        <li><a class="dropdown-item" href="?order=DESC">Descending</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($artists as $artist) { 
                $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if (file_exists($imagePath)) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
            ?>
                <div class="col-md-3 mb-4">
                    <!-- Link zur Künstlerseite mit Bild -->
                    <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                    <div class="card">
                        <!-- Artistbild anzeigen oder Platzhalter, falls Bild fehlt -->
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                        <div class="card-body">
                            <!-- Künstlername -->
                            <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
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