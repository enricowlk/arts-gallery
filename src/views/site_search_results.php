<?php
session_start();

// Include required files
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../repositories/artistRepository.php'; 
require_once __DIR__ . '/../repositories/artworkRepository.php'; 

// Get query parameter from GET request or set to empty string
if (isset($_GET['query'])) {
    $query = $_GET['query']; 
} else {
    $query = ''; 
}

// Get artist sort order from GET request or set default
if (isset($_GET['artistOrder'])) {
    $artistOrder = $_GET['artistOrder'];
} else {
    $artistOrder = 'ASC'; // Default is ascending
}

// Get artwork sort order from GET request or set default
if (isset($_GET['artworkOrder'])) {
    $artworkOrder = $_GET['artworkOrder'];
} else {
    $artworkOrder = 'ASC'; // Default is ascending
}

// Get artwork sort criterion from GET request or set default
if (isset($_GET['artworkOrderBy'])) {
    $artworkOrderBy = $_GET['artworkOrderBy'];
} else {
    $artworkOrderBy = 'Title'; // Default is Title
}

// Create repository instances
$artistRepo = new ArtistRepository(new Database()); 
$artworkRepo = new ArtworkRepository(new Database());

// Query data from repositories
$artists = $artistRepo->searchArtists($query, $artistOrder); // Search artists
$artworks = $artworkRepo->searchArtworks($query, $artworkOrderBy, $artworkOrder); // Search artworks
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
            <!-- Left column for artists -->
            <div class="left-column">
                <h2>Artists</h2>
                <?php if (empty($artists)){ ?>
                    <p>No artists found.</p> 
                <?php }else{ ?>
                    <!-- Sort dropdown for artists -->
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
                    
                    <!-- Display artists as cards -->
                    <div class="row g-2">
                        <?php foreach ($artists as $artist) { 
                            // Create image path for artist
                            $imagePath = "../../images/artists/square-medium/" . $artist->getArtistID() . ".jpg";
                            $imageExists = file_exists($imagePath);
            
                            if ($imageExists) {
                                $imageUrl = $imagePath;
                            } else {
                                $imageUrl = "../../images/placeholder.png"; // Placeholder if image does not exist
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

            <!-- Right column for artworks -->
            <div class="right-column">
                <h2>Artworks</h2>
                <?php if (empty($artworks)){ ?>
                    <p>No artworks found.</p> 
                <?php }else{ ?>
                    <!-- Sort dropdowns for artworks -->
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
                    
                    <!-- Display artworks as cards -->
                    <div class="row g-2">
                        <?php foreach ($artworks as $artwork) {  
                            // Get artist info for the artwork
                            $artist = $artistRepo->getArtistByID($artwork->getArtistID()); 
                            // Create image path for artwork
                            $imagePath = "../../images/works/square-medium/" . $artwork->getImageFileName() . ".jpg";
                            $imageExists = file_exists($imagePath);

                            if ($imageExists) {
                                $imageUrl = $imagePath;
                            } else {
                                $imageUrl = "../../images/placeholder.png"; // Placeholder if image does not exist
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
