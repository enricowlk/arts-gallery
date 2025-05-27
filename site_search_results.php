<?php
session_start(); // Startet die Session

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'database.php'; // Bindet die Datenbankverbindung ein
require_once 'artistRepository.php'; // Bindet das ArtistRepository ein
require_once 'artworkRepository.php'; // Bindet das ArtworkRepository ein

if (isset($_GET['query'])) {
    $query = $_GET['query']; // Speichert den Suchbegriff aus der URL
} else {
    $query = ''; // Setzt den Suchbegriff auf leer, falls keiner vorhanden ist
}

// Sortierparameter für Künstler
if (isset($_GET['artistOrder'])) {
    $artistOrder = $_GET['artistOrder'];
} else {
    $artistOrder = 'ASC'; // Standard-Sortierreihenfolge für Künstler
}

// Sortierparameter für Kunstwerke
if (isset($_GET['artworkOrder'])) {
    $artworkOrder = $_GET['artworkOrder'];
} else {
    $artworkOrder = 'ASC'; // Standard-Sortierreihenfolge für Kunstwerke
}

if (isset($_GET['artworkOrderBy'])) {
    $artworkOrderBy = $_GET['artworkOrderBy'];
} else {
    $artworkOrderBy = 'Title'; // Standard-Sortierfeld für Kunstwerke
}

$artistRepo = new ArtistRepository(new Database()); // Erstellt eine Instanz des ArtistRepository
$artworkRepo = new ArtworkRepository(new Database()); // Erstellt eine Instanz des ArtworkRepository

$artists = $artistRepo->searchArtists($query, $artistOrder); // Sucht nach Künstlern mit Sortierung
$artworks = $artworkRepo->searchArtworks($query, $artworkOrderBy, $artworkOrder); // Sucht nach Kunstwerken mit Sortierung
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
                    <!-- Sortieroptionen für Künstler -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Order: <?php echo $artistOrder; ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artistOrder=ASC&artworkOrderBy=<?php echo $artworkOrderBy; ?>&artworkOrder=<?php echo $artworkOrder; ?>">Ascending</a></li>
                                    <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artistOrder=DESC&artworkOrderBy=<?php echo $artworkOrderBy; ?>&artworkOrder=<?php echo $artworkOrder; ?>">Descending</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row row-cols-3 g-2">
                        <?php foreach ($artists as $artist) { // Schleife durch alle Künstler
                            $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                            $imageExists = file_exists($imagePath);
            
                            if (file_exists($imagePath)) {
                                $imageUrl = $imagePath;
                            } else {
                                $imageUrl = "images/placeholder.png";
                            } 
                        ?>
                            <div class="col">
                                <!-- Link zur Künstlerseite mit Bild -->
                                <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                                <div class="card small-card h-100">
                                    <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
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
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <!-- Sort by Dropdown -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Sort by: <?php echo $artworkOrderBy; ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artworkOrderBy=Title&artworkOrder=<?php echo $artworkOrder; ?>&artistOrder=<?php echo $artistOrder; ?>">Title</a></li>
                                        <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artworkOrderBy=ArtistID&artworkOrder=<?php echo $artworkOrder; ?>&artistOrder=<?php echo $artistOrder; ?>">Artist</a></li>
                                        <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artworkOrderBy=YearOfWork&artworkOrder=<?php echo $artworkOrder; ?>&artistOrder=<?php echo $artistOrder; ?>">Year</a></li>
                                    </ul>
                                </div>
                                
                                <!-- Order Dropdown -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Order: <?php echo $artworkOrder; ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artworkOrderBy=<?php echo $artworkOrderBy; ?>&artworkOrder=ASC&artistOrder=<?php echo $artistOrder; ?>">Ascending</a></li>
                                        <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artworkOrderBy=<?php echo $artworkOrderBy; ?>&artworkOrder=DESC&artistOrder=<?php echo $artistOrder; ?>">Descending</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row row-cols-3 g-2">
                        <?php foreach ($artworks as $artwork) {  // Schleife durch alle Kunstwerke
                            $artist = $artistRepo->getArtistByID($artwork->getArtistID()); 
                            $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                            $imageExists = file_exists($imagePath);

                            if (file_exists($imagePath)) {
                                $imageUrl = $imagePath;
                            } else {
                                $imageUrl = "images/placeholder.png";
                            }
                        ?>
                            <div class="col">
                                <!-- Link zur Kunstwerk-Detailseite mit Bild -->
                                <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <div class="card small-card h-100">
                                    <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
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