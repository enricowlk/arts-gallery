<?php
session_start(); // Startet die Session

require_once 'ArtworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein

$artworkRepo = new ArtworkRepository(new Database()); // Erstellt eine Instanz von ArtworkRepository mit der Datenbankverbindung

// Überprüft, ob Sortierparameter (orderBy und order) übergeben wurden, sonst Standardwerte verwenden
if (isset($_GET['orderBy'])) {
    $orderBy = $_GET['orderBy'];
} else {
    $orderBy = 'Title'; // Standard-Sortierfeld
}

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ASC'; // Standard-Sortierreihenfolge
}

$artworks = $artworkRepo->getAllArtworks($orderBy, $order); // Ruft alle Kunstwerke sortiert ab
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artworks</title>
    <!-- Bindet Bootstrap CSS und benutzerdefinierte CSS-Datei ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigationsleiste ein -->

    <div class="container mt-3">
        <h1 class="text-center">Artworks</h1> <!-- Überschrift -->
        <div class="row">
            <?php foreach ($artworks as $artwork) { ?> <!-- Schleife durch alle Kunstwerke -->
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <!-- Link zur Kunstwerk-Detailseite mit Bild -->
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                        </a>
                        <div class="card-body">
                            <!-- Titel des Kunstwerks -->
                            <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <!-- Bindet Bootstrap JavaScript ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>