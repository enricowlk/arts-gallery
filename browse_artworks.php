<?php
session_start();
require_once 'ArtworkRepository.php';

$artworkRepo = new ArtworkRepository(new Database());

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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">Artworks</h1>
        <div class="row">
            <?php foreach ($artworks as $artwork) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                            <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>