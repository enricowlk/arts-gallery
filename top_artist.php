<?php
require_once 'database.php'; 
require_once 'artistRepository.php'; 

$artistRepo = new ArtistRepository(new Database());

$topArtists = $artistRepo->getTop3Artists();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="top-artist-container">
        <h2>Top Artists</h2>
        <?php if (!empty($topArtists)) { ?>
            <div class="row">
                <?php foreach ($topArtists as $artistData) { 
                    $artist = $artistData['artist'];
                    $reviewCount = $artistData['reviewCount'];
                    $imagePath = "images/artists/medium/" . $artist->getArtistID() . ".jpg";
                    $imageExists = file_exists($imagePath);
                    
                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4">
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                        <div class="card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                            <div class="mt-2">
                                <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                                <p class="small">(<?php echo $reviewCount; ?> reviews)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-center">No top artists found.</p>
        <?php } ?>
    </div>
</body>
</html>