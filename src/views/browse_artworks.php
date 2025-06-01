<?php
session_start(); 

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../repositories/artistRepository.php'; 
require_once __DIR__ . '/../repositories/artworkRepository.php';

$artworkRepo = new ArtworkRepository(new Database()); 
$artistRepo = new ArtistRepository(new Database()); 

if (isset($_GET['orderBy'])) {
    $orderBy = $_GET['orderBy'];
} else {
    $orderBy = 'Title'; 
}

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ASC';
}

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
        
        <div class="mb-3">
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Sort by: <?php echo ucfirst(strtolower($orderBy)); ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?orderBy=Title&order=<?php echo $order; ?>">Title</a></li>
                    <li><a class="dropdown-item" href="?orderBy=ArtistID&order=<?php echo $order; ?>">Artist</a></li>
                    <li><a class="dropdown-item" href="?orderBy=YearOfWork&order=<?php echo $order; ?>">Year</a></li>
                </ul>
            </div>
            
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Order: <?php echo $order; ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?orderBy=<?php echo $orderBy; ?>&order=ASC">Ascending</a></li>
                    <li><a class="dropdown-item" href="?orderBy=<?php echo $orderBy; ?>&order=DESC">Descending</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <?php foreach ($artworks as $artwork) { 
                $artist = $artistRepo->getArtistByID($artwork->getArtistID());
                $imagePath = "../../images/works/square-medium/" . $artwork->getImageFileName() .".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "../../images/placeholder.png";
                }
                ?>
                <div class="col-md-4 mb-4">
                    <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                            <p class = "small"> By <?php echo $artist->getFirstName(); ?> <?php echo $artist->getLastName(); ?></p>
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