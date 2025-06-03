<?php
/**
 * Artist browse Page
 * 
 * This script fetches all artists from the database, allowing sorting by name (ASC/DESC).
 * It also includes fallback logic for missing artist images and renders a responsive grid.
 */

session_start();

// Required dependencies
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/artistRepository.php';

$artistRepo = new ArtistRepository(new Database());

// Determine sort order from query string (default to ASC)
$order = (isset($_GET['order']) && $_GET['order'] === 'DESC') ? 'DESC' : 'ASC';

// Fetch artists from database with selected sort order
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

        <!-- Sort dropdown -->
        <div class="d-flex justify-content-end mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Order: <?php echo $order; ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?order=ASC">Ascending</a></li>
                    <li><a class="dropdown-item" href="?order=DESC">Descending</a></li>
                </ul>
            </div>
        </div>

        <!-- Artist grid -->
        <div class="row g-4">
            <?php foreach ($artists as $artist): 
                $imagePath = "../../images/artists/square-medium/" . $artist->getArtistID() . ".jpg";
                $imageUrl = file_exists($imagePath) ? $imagePath : "../../images/placeholder.png";
            ?>
                <div class="col-md-4">
                    <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?>
                                </h5>
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
