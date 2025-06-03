<?php
/**
 * Artworks browse Page
 * 
 * Displays all artworks with options to sort by title, artist, or year
 * in ascending or descending order.
 */

session_start();

// Dependencies
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/artistRepository.php';
require_once __DIR__ . '/../repositories/artworkRepository.php';

$database = new Database();
$artworkRepo = new ArtworkRepository($database);
$artistRepo = new ArtistRepository($database);

// Define valid sort options
$validOrderBy = ['Title', 'ArtistID', 'YearOfWork'];
$validOrder = ['ASC', 'DESC'];

// Validate and assign sort parameters from GET
$orderBy = in_array($_GET['orderBy'] ?? '', $validOrderBy) ? $_GET['orderBy'] : 'Title';
$order = in_array($_GET['order'] ?? '', $validOrder) ? $_GET['order'] : 'ASC';

// Fetch artworks
$artworks = $artworkRepo->getAllArtworks($orderBy, $order);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artworks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>

    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container">
        <h1 class="text-center">Artworks</h1>

        <!-- Sort controls -->
        <div class="d-flex justify-content-between mb-4 flex-wrap gap-2">
            <!-- Sort by -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Sort by: <?php echo $orderBy; ?>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach ($validOrderBy as $sortKey): ?>
                        <li>
                            <a class="dropdown-item" href="?orderBy=<?php echo $sortKey; ?>&order=<?php echo $order; ?>">
                                <?php echo $sortKey; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Order -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Order: <?php echo $order; ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?orderBy=<?php echo $orderBy; ?>&order=ASC">Ascending</a></li>
                    <li><a class="dropdown-item" href="?orderBy=<?php echo $orderBy; ?>&order=DESC">Descending</a></li>
                </ul>
            </div>
        </div>

        <!-- Artwork grid -->
        <div class="row g-4">
            <?php foreach ($artworks as $artwork): 
                $artist = $artistRepo->getArtistByID($artwork->getArtistID());
                $imagePath = "../../images/works/square-medium/" . $artwork->getImageFileName() . ".jpg";
                $imageUrl = file_exists($imagePath) ? $imagePath : "../../images/placeholder.png";
            ?>
                <div class="col-md-4">
                    <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="small mb-0">By <?php echo $artist->getFirstName() . ' ' . $artist->getLastName(); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
