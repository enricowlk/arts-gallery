<?php
// Session starten für Session-Variablen
session_start(); 

// Einbinden des globalen Exception Handlers und Artist-Repository
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/artistRepository.php'; 

// ArtistRepository instanziieren
$artistRepo = new ArtistRepository(new Database()); 

// Sortierparameter aus URL auslesen (falls vorhanden), Standard ist ASC (aufsteigend)
if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ASC'; 
}

// Alle Künstler mit Sortierreihenfolge aus der Datenbank holen
$artists = $artistRepo->getAllArtists($order); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?> 

    <div class="container">
        <h1 class="text-center">Artists</h1> 
        
        <!-- Sortier-Dropdown -->
        <div class="mb-3">
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Order: <?php echo $order; ?> <!-- Aktuelle Sortierreihenfolge anzeigen -->
                </button>
                <ul class="dropdown-menu">
                    <!-- Optionen für auf- und absteigende Sortierung -->
                    <li><a class="dropdown-item" href="?order=ASC">Ascending</a></li>
                    <li><a class="dropdown-item" href="?order=DESC">Descending</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Künstler-Grid -->
        <div class="row">
            <?php foreach ($artists as $artist) { 
                // Bildpfad erstellen und prüfen ob Bild existiert
                $imagePath = "../../images/artists/square-medium/" . $artist->getArtistID() . ".jpg";
                $imageExists = file_exists($imagePath);

                // Platzhalter falls kein Bild existiert
                $imageUrl = $imageExists ? $imagePath : "../../images/placeholder.png";
            ?>
                <div class="col-md-4 mb-3">
                    <!-- Link zur Künstler-Detailseite -->
                    <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                    <div class="card">
                        <!-- Künstlerbild -->
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                        <div class="card-body">
                            <!-- Künstlername (Nachname, Vorname) -->
                            <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>