<?php
session_start(); 

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . 'artistRepository.php'; 

$artistRepo = new ArtistRepository(new Database()); 

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ASC'; 
}

$artists = $artistRepo->getAllArtists($order); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include __DIR__ . 'navigation.php'; ?> 

    <div class="container">
        <h1 class="text-center">Artists</h1> 
        
        <div class="mb-3">
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Order: <?php echo $order; ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?order=ASC">Ascending</a></li>
                    <li><a class="dropdown-item" href="?order=DESC">Descending</a></li>
                </ul>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($artists as $artist) { 
                $imagePath = "images/artists/square-medium/" . $artist->getArtistID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
            ?>
                <div class="col-md-4 mb-3">
                    <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include __DIR__ . 'footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>