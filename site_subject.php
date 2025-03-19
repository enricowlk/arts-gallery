<?php
session_start();

require_once 'database.php';
require_once 'SubjectRepository.php';
require_once 'ArtworkRepository.php';

if (isset($_GET['id'])) {
    $subjectId = $_GET['id'];
} else {
    $subjectId = null;
}

$subjectRepo = new SubjectRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

$subject = $subjectRepo->getSubjectById($subjectId);
$artworks = $artworkRepo->getAllArtworksForOneSubjectBySubjectId($subjectId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $subject->getSubjectName(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Subject-Bild */
        .subject-image {
            width: 322px; /* Gewünschte Breite */
            height: 300px; /* Gewünschte Höhe */
            object-fit: cover; /* Bild wird zugeschnitten, um den Container zu füllen */
            border-radius: 10px; /* Abgerundete Ecken */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten hinzufügen */
        }
        </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Subject-Informationen -->
        <div class="row">
            <div class="col-md-4">
                <!-- Subject-Bild -->
                <img src="images/subjects/square-medium/<?php echo $subject->getSubjectID(); ?>.jpg" class="subject-image" alt="<?php echo $subject->getSubjectName(); ?>">
            </div>
            <div class="col-md-8">
                <div class="info">
                    <h1><?php echo $subject->getSubjectName(); ?></h1>
                </div>
            </div>
        </div>

        <!-- Kunstwerke in diesem Subject -->
        <h2 class="mt-5 mb-4">Artworks in <?php echo $subject->getSubjectName(); ?></h2>
        <?php if (empty($artworks)){ ?>
            <p class="text-center">No artworks found in this subject.</p>
        <?php }else{ ?>
            <div class="row">
                <?php foreach ($artworks as $artwork){ 
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card artwork-card">
                            <!-- Kunstwerkbild -->
                            <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                                <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="card-text">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <p class="card-text">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>