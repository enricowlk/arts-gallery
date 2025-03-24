<?php
session_start(); // Startet die Session

require_once 'ArtworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein
require_once 'ArtistRepository.php'; // Bindet die ArtistRepository-Klasse ein

$artworkRepo = new ArtworkRepository(new Database()); // Erstellt eine Instanz von ArtworkRepository mit der Datenbankverbindung
$artistRepo = new ArtistRepository(new Database()); // Erstellt eine Instanz von ArtistRepository mit der Datenbankverbindung

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

$artworks = $artworkRepo->getAllArtworks($orderBy, $order); // Ruft alle Kunstwerke sortiert aus der Datenbank ab
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
        
        <!-- Sortieroptionen -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort by: <?php echo ucfirst(strtolower($orderBy)); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?orderBy=Title&order=<?php echo $order; ?>">Title</a></li>
                        <li><a class="dropdown-item" href="?orderBy=ArtistID&order=<?php echo $order; ?>">Artist</a></li>
                        <li><a class="dropdown-item" href="?orderBy=YearOfWork&order=<?php echo $order; ?>">Year</a></li>
                    </ul>
                </div>
                
                <div class="btn-group ms-2">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Order: <?php echo $order; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?orderBy=<?php echo $orderBy; ?>&order=ASC">Ascending</a></li>
                        <li><a class="dropdown-item" href="?orderBy=<?php echo $orderBy; ?>&order=DESC">Descending</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($artworks as $artwork) { ?> <!-- Schleife durch alle Kunstwerke -->
                <?php $artist = $artistRepo->getArtistByID($artwork->getArtistID()); ?>
                <div class="col-md-3 mb-4">
                    <!-- Link zur Kunstwerk-Detailseite mit Bild -->
                    <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                    <div class="card">
                        <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                        <div class="card-body">
                            <!-- Titel des Kunstwerks -->
                            <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                            <p class = "small"> By <?php echo $artist->getFirstName(); ?> <?php echo $artist->getLastName(); ?></p>
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