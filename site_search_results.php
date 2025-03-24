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

// Überprüft, ob Sortierparameter (orderBy und order) übergeben wurden, sonst Standardwerte verwenden
if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ASC'; // Standard-Sortierreihenfolge
}

if (isset($_GET['orderBy'])) {
    $orderBy = $_GET['orderBy'];
} else {
    $orderBy = 'Title'; // Standard-Sortierfeld
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
    <style>
        .split-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .left-column, .right-column {
            flex: 1;
        }
        .section-header {
            margin-bottom: 15px;
        }
        .small-card {
            max-width: 200px;
        }
        .small-card img {
            height: 150px;
            object-fit: cover;
        }
        .card-title {
            font-size: 0.9rem;
        }
        .small {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigation ein -->

    <div class="container mt-3">
        <h1>Search Results for "<?php echo $query; ?>"</h1> <!-- Zeigt den Suchbegriff an -->

        <div class="split-container">
            <!-- Left Column for Artists -->
            <div class="left-column">
                <h2 class="section-header">Artists</h2>
                <?php if (empty($artists)){ ?>
                    <p>No artists found.</p> <!-- Zeigt an, wenn keine Künstler gefunden wurden -->
                <?php }else{ ?>
                    <!-- Sortieroptionen -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Order: <?php echo $order; ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?order=ASC">Ascending</a></li>
                                    <li><a class="dropdown-item" href="?order=DESC">Descending</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row row-cols-3 g-2">
                        <?php foreach ($artists as $artist) { ?> <!-- Schleife durch alle Künstler -->
                            <div class="col">
                                <!-- Link zur Künstlerseite mit Bild -->
                                <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                                <div class="card small-card h-100">
                                    <img src="images/artists/medium/<?php echo $artist->getArtistID(); ?>.jpg" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                                    <div class="card-body">
                                        <!-- Künstlername -->
                                        <h5 class="small"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                    </div>
                                </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <!-- Right Column for Artworks -->
            <div class="right-column">
                <h2 class="section-header">Artworks</h2>
                <?php if (empty($artworks)){ ?>
                    <p>No artworks found.</p> <!-- Zeigt an, wenn keine Kunstwerke gefunden wurden -->
                <?php }else{ ?>
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
                            <div class="btn-group ms-1">
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
                </div>
                <div class="row row-cols-3 g-2">
                    <?php foreach ($artworks as $artwork) { ?> <!-- Schleife durch alle Kunstwerke -->
                        <?php $artist = $artistRepo->getArtistByID($artwork->getArtistID()); ?>
                        <div class="col">
                            <!-- Link zur Kunstwerk-Detailseite mit Bild -->
                            <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <div class="card small-card h-100">
                                <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                                <div class="card-body">
                                    <!-- Titel des Kunstwerks -->
                                    <h5 class="small"><strong><?php echo $artwork->getTitle(); ?></strong></h5>
                                    <p class="small">By <?php echo $artist->getFirstName(); ?> <?php echo $artist->getLastName(); ?></p>
                                </div>
                            </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>