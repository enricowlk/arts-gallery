<?php
session_start();

// Include required files
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../repositories/subjectRepository.php'; 
require_once __DIR__ . '/../repositories/artworkRepository.php'; 

// Check if a valid subject ID was provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /components/error.php?message=Invalid or missing subject ID");
    exit();
}

// Get subject ID from GET parameter and cast to integer
$subjectId = (int)$_GET['id']; 

// Create repository instances
$subjectRepo = new SubjectRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

// Fetch subject data by ID
$subject = $subjectRepo->getSubjectById($subjectId);

// If subject not found, show error page
if ($subject === null) {
    header("Location: /components/error.php?message=Subject not found");
    exit();
}

// Fetch all artworks for this subject
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
                // Create image path for subject
                $imagePath = "../../images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "../../images/placeholder.png"; // Placeholder if no image exists
                }
                ?>
                <!-- Display subject image -->
                <img src="<?php echo $imageUrl; ?>" class="subject-image shadow" alt="<?php echo $subject->getSubjectName(); ?>">
            </div>
            <div class="col-md-8">
                <div class="info text-center">
                    <!-- Display subject name -->
                    <h1><?php echo $subject->getSubjectName(); ?></h1>
                </div>
            </div>
        </div>

        <!-- Section for artworks in this subject -->
        <h2 class="mt-4">Artworks in <?php echo $subject->getSubjectName(); ?></h2>
        <?php if (empty($artworks)) { ?>
            <!-- If no artworks were found -->
            <p class="text-center">No artworks found in this subject.</p>
        <?php } else { ?>
            <div class="row">
                <?php foreach ($artworks as $artwork) { 
                    // Get average rating for the artwork
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                    // Create image path for artwork
                    $imagePath = "../../images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if ($imageExists) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "../../images/placeholder.png"; // Placeholder if no image exists
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <!-- Link to the artwork detail page -->
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card artwork-card">
                            <!-- Artwork image -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <!-- Artwork title -->
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <!-- Year of creation -->
                                <p class="small">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <!-- Average rating with star icon -->
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
