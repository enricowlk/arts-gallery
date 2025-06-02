<?php
session_start();

// Required-Dateien einbinden
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../repositories/artistRepository.php'; 
require_once __DIR__ . '/../repositories/artworkRepository.php'; 

// Query-Parameter aus GET-Request holen oder leeren String setzen
if (isset($_GET['query'])) {
    $query = $_GET['query']; 
} else {
    $query = ''; 
}

// Sortierreihenfolge für Künstler aus GET-Request holen oder Standardwert setzen
if (isset($_GET['artistOrder'])) {
    $artistOrder = $_GET['artistOrder'];
} else {
    $artistOrder = 'ASC'; // Aufsteigend als Standard
}

// Sortierreihenfolge für Kunstwerke aus GET-Request holen oder Standardwert setzen
if (isset($_GET['artworkOrder'])) {
    $artworkOrder = $_GET['artworkOrder'];
} else {
    $artworkOrder = 'ASC'; // Aufsteigend als Standard
}

// Sortierkriterium für Kunstwerke aus GET-Request holen oder Standardwert setzen
if (isset($_GET['artworkOrderBy'])) {
    $artworkOrderBy = $_GET['artworkOrderBy'];
} else {
    $artworkOrderBy = 'Title'; // Titel als Standard
}

// Repository-Instanzen erstellen
$artistRepo = new ArtistRepository(new Database()); 
$artworkRepo = new ArtworkRepository(new Database());

// Daten aus den Repositories abfragen
$artists = $artistRepo->searchArtists($query, $artistOrder); // Künstler suchen
$artworks = $artworkRepo->searchArtworks($query, $artworkOrderBy, $artworkOrder); // Kunstwerke suchen
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
    <style>
        .small {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?> 

    <div class="container mt-3">
        <h1>Search Results for "<?php echo $query; ?>"</h1> 

        <div class="split-container">
            <!-- Linke Spalte für Künstler -->
            <div class="left-column">
                <h2>Artists</h2>
                <?php if (empty($artists)){ ?>
                    <p>No artists found.</p> 
                <?php }else{ ?>
                    <!-- Sortierdropdown für Künstler -->
                    <div class="row mb-3">
                        <div>
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Order: <?php echo $artistOrder; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artistOrder=ASC&artworkOrderBy=<?php echo $artworkOrderBy; ?>&artworkOrder=<?php echo $artworkOrder; ?>">Ascending</a></li>
                                <li><a class="dropdown-item" href="?query=<?php echo urlencode($query); ?>&artistOrder=DESC&artworkOrderBy=<?php echo $artworkOrderBy; ?>&artworkOrder=<?php echo $artworkOrder; ?>">Descending</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Künstler als Karten anzeigen -->
                    <div class="row g-2">
                        <?php foreach ($artists as $artist) { 
                            // Bildpfad für Künstler erstellen
                            $imagePath = "../../images/artists/square-medium/" . $artist->getArtistID() . ".jpg";
                            $imageExists = file_exists($imagePath);
            
                            if ($imageExists) {
                                $imageUrl = $imagePath;
                            } else {
                                $imageUrl = "../../images/placeholder.png"; // Platzhalter falls kein Bild existiert
                            } 
                        ?>
                            <div class="col">
                                <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                                <div class="card">
                                    <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                                    <div class="card-body">
                                        <h5 class="small"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                    </div>
                                </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <!-- Rechte Spalte für Kunstwerke -->
            <div class="right-column">
                <h2>Artworks</h2>
                <?php if (empty($artworks)){ ?>
                    <p>No artworks found.</p> 
                <?php }else{ ?>
                    <!-- Sortierdropdowns für Kunstwerke -->
                    <div class="row mb-3">
                            <div>
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
                    
                    <!-- Kunstwerke als Karten anzeigen -->
                    <div class="row g-2">
                        <?php foreach ($artworks as $artwork) {  
                            // Künstlerinformationen für das Kunstwerk holen
                            $artist = $artistRepo->getArtistByID($artwork->getArtistID()); 
                            // Bildpfad für Kunstwerk erstellen
                            $imagePath = "../../images/works/square-medium/" . $artwork->getImageFileName() . ".jpg";
                            $imageExists = file_exists($imagePath);

                            if ($imageExists) {
                                $imageUrl = $imagePath;
                            } else {
                                $imageUrl = "../../images/placeholder.png"; // Platzhalter falls kein Bild existiert
                            }
                        ?>
                            <div class="col">
                                <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <div class="card">
                                    <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                                    <div class="card-body">
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

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>