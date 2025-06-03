<?php
// Start session for user data
session_start(); 

// Include all required files
require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../repositories/artworkRepository.php'; 
require_once __DIR__ . '/../repositories/artistRepository.php'; 
require_once __DIR__ . '/../repositories/reviewRepository.php'; 
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../repositories/genreRepository.php'; 
require_once __DIR__ . '/../repositories/subjectRepository.php'; 
require_once __DIR__ . '/../repositories/gallerieRepository.php'; 

// Check if user is logged in and is an admin
$isLoggedIn = isset($_SESSION['user']);
$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;

// Check if a valid artwork ID was provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /components/error.php?message=Invalid or missing artwork ID");
    exit();
}

$artworkId = (int)$_GET['id']; // Get artwork ID from GET parameter

// Create repository instances
$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());
$genreRepo = new GenreRepository(new Database());
$subjectRepo = new SubjectRepository(new Database());
$gallerieRepo = new GallerieRepository(new Database());

// Load artwork data
$artwork = $artworkRepo->getArtworkById($artworkId); 
if ($artwork === null) {
    header("Location: /components/error.php?message=Artwork not found");
    exit();
}

// Load additional data
$artist = $artistRepo->getArtistById($artwork->getArtistID()); // Artist of the artwork
$reviews = $reviewRepo->getAllReviewsForOneArtworkByArtworkId($artworkId); // Reviews for the artwork
$genres = $artworkRepo->getGenreForOneArtworkByArtworkId($artworkId); // Genres assigned to the artwork
$subjects = $artworkRepo->getSubjectForOneArtworkByArtworkId($artworkId); // Subjects assigned to the artwork
$gallerie = $gallerieRepo->getGalleryByArtworkId($artworkId); // Gallery info

// Load average rating of the artwork & calculate review count
$averageRating = $artworkRepo->getAverageRatingForArtwork($artworkId);
$reviewCount = count($reviews);

// Check if artwork is in favorites
$isFavoriteArtwork = isset($_SESSION['favorite_artworks']) && in_array($artworkId, $_SESSION['favorite_artworks']);

// Check if user has already reviewed the artwork
$hasReviewed = false;
if ($isLoggedIn) {
    $hasReviewed = $reviewRepo->hasUserReviewedArtwork($artworkId, $_SESSION['user']['CustomerID']);
}

// Build and validate image path
$imagePath = "../../images/works/medium/" . $artwork->getImageFileName() . ".jpg";
$imageExists = file_exists($imagePath);
$imageUrl = $imageExists ? $imagePath : "../../images/placeholder.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $artwork->getTitle(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Left column: Artwork image and actions -->
            <div class="col-md-6">
                <a data-bs-toggle="modal" data-bs-target="#imageModal">
                    <img src="<?php echo $imageUrl; ?>" 
                        class="modal-artwork-image img-fluid rounded border border-secondary shadow" 
                        alt="<?php echo $artwork->getTitle(); ?>">
                </a>

                <?php include __DIR__ . '/components/artworkSite/image_modal.php'; ?>
                <?php include __DIR__ . '/components/artworkSite/artwork_actions.php'; ?>
                <?php include __DIR__ . '/components/artworkSite/gallery_info.php'; ?>
            </div>
            
            <!-- Right column: Artwork information -->
            <div class="col-md-6">
                <div class="info">
                    <h1><?php echo $artwork->getTitle(); ?></h1>
                    <p class="artwork-links">By <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></a></p>
                    <p><strong>Year of Work:</strong> <?php echo $artwork->getYearOfWork(); ?></p>
                    <p><strong>Medium:</strong> <?php echo $artwork->getMedium(); ?></p>
                    <p><strong>Excerpt:</strong> <?php echo $artwork->getExcerpt(); ?></p>
                    <p><strong>Description:</strong> <?php echo $artwork->getDescription(); ?></p>
                    
                    <!-- Genres and subjects of the artwork with links to their pages -->
                    <div class="artwork-links">
                        <p><strong>Genres:</strong> 
                            <?php 
                            if (!empty($genres)) { 
                                foreach ($genres as $genre) { 
                            ?>
                                    <a href="site_genre.php?id=<?php echo $genre->getGenreID(); ?>"><?php echo $genre->getGenreName(); ?></a><?php 
                                    if ($genre !== end($genres)) { 
                                        echo ', '; 
                                    } 
                                } 
                            } else { 
                                echo 'No genres assigned'; 
                            } 
                            ?>
                        </p>
                            
                        <p><strong>Subjects:</strong>
                            <?php 
                            if (!empty($subjects)) { 
                                foreach ($subjects as $subject) { 
                            ?>
                                    <a href="site_subject.php?id=<?php echo $subject->getSubjectID(); ?>"><?php echo $subject->getSubjectName(); ?></a><?php 
                                    if ($subject !== end($subjects)) { 
                                        echo ', '; 
                                    } 
                                } 
                            } else { 
                                echo 'No subjects assigned'; 
                            } 
                            ?>
                        </p>
                    </div>

                    <!-- External link for more information about the artwork -->
                    <p>You want to know more about "<strong><?php echo $artwork->getTitle(); ?></strong>"?</p>
                    <a href="<?php echo $artwork->getArtWorkLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <h2 class="mt-4">Reviews</h2>
        <?php 
        if(!$isLoggedIn) { 
        ?>
            <div class="alert alert-danger col-md-3">
                Log in to leave a review.
            </div>
        <?php 
        } elseif($isLoggedIn && !$hasReviewed) { 
            include __DIR__ . '/components/artworkSite/review_form.php';
        } else { 
        ?>
            <div class="alert alert-success col-md-4">
                You have already reviewed this artwork.
            </div>
        <?php 
        } 
        ?>

        <?php include __DIR__ . '/components/artworkSite/reviews_table.php'; ?>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
