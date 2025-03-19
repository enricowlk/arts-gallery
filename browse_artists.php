<?php
session_start();

require_once 'ArtistRepository.php';

$artistRepo = new ArtistRepository(new Database());

$artists = $artistRepo->getAllArtists();
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
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">Artists</h1>
        <div class="row">
            <?php foreach ($artists as $artist) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                            <img src="images/artists/medium/<?php echo $artist->getArtistID(); ?>.jpg" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
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