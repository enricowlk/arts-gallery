<?php
session_start();
require_once 'database.php';
require_once 'ArtworkRepository.php';
require_once 'ArtistRepository.php';
require_once 'ReviewRepository.php';
require_once 'CustomerRepository.php';

if (isset($_GET['id'])) {
    $artworkId = $_GET['id'];
} else {
    $artworkId = null;
}

$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());

$artwork = $artworkRepo->getArtworkById($artworkId);
$artist = $artistRepo->getArtistById($artwork->getArtistID());
$reviews = $reviewRepo->getAllReviewsForOneArtworkByArtworkId($artworkId);

$isFavoriteArtwork = isset($_SESSION['favorite_artworks']) && in_array($artworkId, $_SESSION['favorite_artworks']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $artwork->getTitle(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Kunstwerkbild */
        .artwork-image {
            width: 100%;
            height: 300px; /* Feste Höhe für die Bilder */
            object-fit: cover; /* Bild wird zugeschnitten, um den Container zu füllen */
            border-radius: 10px; /* Abgerundete Ecken */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten hinzufügen */
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Kunstwerkinformationen -->
        <div class="row">
            <div class="col-md-6">
                <!-- Bild als Link zum Modal -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                    <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="artwork-image" alt="<?php echo $artwork->getTitle(); ?>">
                </a>
                <!-- Add to Favorite/Remove Button -->
                <form action="favorites_process.php" method="post" class="mt-3">
                    <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                    <button type="submit" class="btn 
                        <?php
                            if ($isFavoriteArtwork) {
                                echo 'btn-danger';
                            } else {
                                echo 'btn-secondary';
                            }
                        ?>">
                        <?php
                            if ($isFavoriteArtwork) {
                                echo 'Remove from Favorites';
                            } else {
                                echo 'Add to Favorites';
                            }
                        ?>
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="info">
                    <h1><?php echo $artwork->getTitle(); ?></h1>
                    <p class="lead">By <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></a></p>
                    <p><strong>Year of Work:</strong> <?php echo $artwork->getYearOfWork(); ?></p>
                    <p><strong>Medium:</strong> <?php echo $artwork->getMedium(); ?></p>
                    <p><strong>Description:</strong> <?php echo $artwork->getDescription(); ?></p>
                    <p><strong>Original Home:</strong> <?php echo $artwork->getOriginalHome(); ?></p>
                    <p> You want to know more about "<strong><?php echo $artwork->getTitle(); ?></strong>"?</p>
                    <a href="<?php echo $artwork->getArtWorkLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <h2 class="mt-5 mb-4">Reviews</h2>
        <?php if (!empty($reviews)){ ?>
            <div class="table-responsive">
                <table class="review-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review){ ?>
                            <tr>
                                <td><?php echo $customerRepo->getCustomerNameById($review->getCustomerId()); ?></td>
                                <td class="rating"><?php echo $review->getRating(); ?>/5 <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;"></td>
                                <td class="comment"><?php echo $review->getComment(); ?></td>
                                <td class="date"><?php echo $review->getReviewDate(); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php }else{ ?>
            <p class="text-center">No reviews found for this artwork.</p>
        <?php } ?>
    </div>

    <!-- Modal für das größere Bild -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"><?php echo $artwork->getTitle(); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="images/works/large/<?php echo $artwork->getImageFileName(); ?>.jpg" class="img-fluid" alt="<?php echo $artwork->getTitle(); ?>">
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>