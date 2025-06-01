<?php
session_start();

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../repositories/subjectRepository.php';
require_once __DIR__ . '/../repositories/artworkRepository.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?message=Invalid or missing subject ID");
    exit();
}

$subjectId = (int)$_GET['id']; 
$subjectRepo = new SubjectRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

$subject = $subjectRepo->getSubjectById($subjectId);

if ($subject === null) {
    header("Location: error.php?message=Subject not found");
    exit();
}

$artworks = $artworkRepo->getAllArtworksForOneSubjectBySubjectId($subjectId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $subject->getSubjectName(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <?php
                $imagePath = "../../images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "../../images/placeholder.png";
                }
                ?>
                <img src="<?php echo $imageUrl; ?>" class="subject-image shadow" alt="<?php echo $subject->getSubjectName(); ?>">
            </div>
            <div class="col-md-8">
                <div class="info text-center">
                    <h1><?php echo $subject->getSubjectName(); ?></h1>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Artworks in <?php echo $subject->getSubjectName(); ?></h2>
        <?php if (empty($artworks)) { ?>
            <p class="text-center">No artworks found in this subject.</p>
        <?php } else { ?>
            <div class="row">
                <?php foreach ($artworks as $artwork) { 
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                    $imagePath = "../../images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "../../images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card artwork-card">
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <p class="small">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <p class="small">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="../../images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>