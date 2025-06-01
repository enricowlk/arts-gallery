<?php
session_start(); 

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . 'database.php'; 
require_once __DIR__ . 'artworkRepository.php'; 
require_once __DIR__ . 'artistRepository.php'; 
require_once __DIR__ . 'reviewRepository.php'; 
require_once __DIR__ . 'customerRepository.php'; 
require_once __DIR__ . 'genreRepository.php';
require_once __DIR__ . 'subjectRepository.php'; 
require_once __DIR__ . 'gallerieRepository.php';

$isLoggedIn = isset($_SESSION['user']);

$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?message=Invalid or missing artwork ID");
    exit();
}

$artworkId = (int)$_GET['id']; 

$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());
$genreRepo = new GenreRepository(new Database());
$subjectRepo = new SubjectRepository(new Database());
$gallerieRepo = new GallerieRepository(new Database());


$artwork = $artworkRepo->getArtworkById($artworkId); 
if ($artwork === null) {
    header("Location: error.php?message=Artwork not found");
    exit();
}

$artist = $artistRepo->getArtistById($artwork->getArtistID()); 
$reviews = $reviewRepo->getAllReviewsForOneArtworkByArtworkId($artworkId);

$genres = $artworkRepo->getGenreForOneArtworkByArtworkId($artworkId);
$subjects = $artworkRepo->getSubjectForOneArtworkByArtworkId($artworkId);

$gallerie = $gallerieRepo->getGalleryByArtworkId($artworkId);

$averageRating = $artworkRepo->getAverageRatingForArtwork($artworkId);
$reviewCount = count($reviews);

$isFavoriteArtwork = isset($_SESSION['favorite_artworks']) && in_array($artworkId, $_SESSION['favorite_artworks']);

$hasReviewed = $reviewRepo->hasUserReviewedArtwork($artworkId, $_SESSION['user']['CustomerID']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $artwork->getTitle(); ?> - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . 'navigation.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <?php
                $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <a data-bs-toggle="modal" data-bs-target="#imageModal">
                <img src="<?php echo $imageUrl; ?>" 
                    class="modal-artwork-image img-fluid rounded border border-secondary" 
                    alt="<?php echo $artwork->getTitle(); ?>">
                </a>

                <div class="modal fade" id="imageModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo $artwork->getTitle(); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div>
                                <img src="<?php echo $imageUrl; ?>" alt="<?php echo $artwork->getTitle(); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-row"> 
                    <form action="favorites_process.php" method="post">
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

                    <div>
                        <?php if ($reviewCount > 0){ ?>
                            <span><strong><?php echo number_format($averageRating, 1); ?>/5 </strong></span>
                            <img src="images/icon_gelberStern.png" alt="Star">
                            <span>(<?php echo $reviewCount; ?> reviews)</span>
                        <?php }else{ ?>
                            <span>No ratings yet</span>
                        <?php } ?>
                    </div>
                </div>

                <?php if ($gallerie && $gallerie->getLatitude() && $gallerie->getLongitude()) { ?>
                        <div class="accordion mt-4">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#galleryInfo">
                                        <strong>Click me to see my Location!</strong>
                                    </button>
                                </h2>
                                <div id="galleryInfo" class="collapse" data-bs-parent="#galleryAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Name:</strong> <?php echo $gallerie->getGalleryName(); ?></p>
                                        <?php if ($gallerie->getGalleryNativeName()) { ?>
                                            <p><strong>Native Name:</strong> <?php echo $gallerie->getGalleryNativeName(); ?></p>
                                        <?php } ?>
                                        <p><strong>Location:</strong> <?php echo $gallerie->getGalleryCity(); ?>, <?php echo $gallerie->getGalleryCountry(); ?></p>
                                        
                                        <?php if ($gallerie->getGalleryWebSite()) { ?>
                                            <p><strong>Website:</strong> 
                                                <a class="btn btn-secondary" href="<?php echo $gallerie->getGalleryWebSite(); ?>">
                                                    Visit Museum Website
                                                </a>
                                            </p>
                                        <?php } ?>
                                        
                                        <div>
                                            <strong>Location Map:</strong>
                                            <div class="ratio ratio-16x9 mt-2">
                                                <iframe 
                                                    src="https://maps.google.com/maps?q=<?php echo $gallerie->getLatitude(); ?>,<?php echo $gallerie->getLongitude(); ?>&z=15&output=embed"
                                                    width="100%" 
                                                    height="300" 
                                                    frameborder="0" 
                                                    style="border:0"
                                                    allowfullscreen>
                                                </iframe>
                                            </div>
                                            
                                            <div class="map-links mt-2">
                                                <a href="https://www.google.com/maps?q=<?php echo $gallerie->getLatitude(); ?>,<?php echo $gallerie->getLongitude(); ?>" 
                                                class="btn btn-secondary btn-sm">
                                                    Open in Google Maps
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            </div>
            <div class="col-md-6">
                <div class="info">
                    <h1><?php echo $artwork->getTitle(); ?></h1>
                    <p class="artwork-links">By <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></a></p>
                    <p><strong>Year of Work:</strong> <?php echo $artwork->getYearOfWork(); ?></p>
                    <p><strong>Medium:</strong> <?php echo $artwork->getMedium(); ?></p>
                    <p><strong>Excerpt:</strong> <?php echo $artwork->getExcerpt(); ?></p>
                    <p><strong>Description:</strong> <?php echo $artwork->getDescription(); ?></p>
                    
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

                    <p> You want to know more about "<strong><?php echo $artwork->getTitle(); ?></strong>"?</p>
                    <a href="<?php echo $artwork->getArtWorkLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Reviews</h2>
        <?php if(isset($_SESSION['error_message'])){ ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php } ?>

        <?php 
        if($isLoggedIn && !$hasReviewed){ 
        ?>
        <div>
            <div>
                <h5>Add Your Review</h5>
                <form action="add_review.php" method="post">
                    <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                    <div>
                        <div class="col-md-3">
                            <label class="form-label">Rating</label>
                            <select class="form-control" id="rating" name="rating" required>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Good</option>
                                <option value="4">4 - Very Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="mt-1">
                            <button type="submit" class="btn btn-secondary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
        <?php 
            } else { 
        ?>
        <div class="alert alert-info">
            You have already reviewed this artwork.
        </div>
        <?php 
            } 
        
        ?>

        <div>
            <table class="table table-hover review-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>City (Country)</th>
                        <?php if ($isAdmin) { ?>
                            <th> </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reviews)) { ?>
                        <?php foreach ($reviews as $review) { ?>
                            <?php
                                $customer = $customerRepo->getCustomerByID($review->getCustomerId());
                            ?>
                            <tr>
                                <td><?php echo $customer->getFirstName() . ' ' . $customer->getLastName(); ?></td>
                                <td class="small">
                                    <?php echo $review->getRating(); ?>/5 
                                    <img src="images/icon_gelberStern.png" alt="Star" class="star-icon">
                                </td>
                                <td><?php echo $review->getComment(); ?></td>
                                <td class="small"><?php echo $review->getReviewDate(); ?></td>
                                <td><?php echo $customer->getCity(); ?> (<?php echo $customer->getCountry(); ?>)</td>
                                <?php if ($isAdmin) { ?>
                                    <td>
                                        <form action="delete_review.php" method="post">
                                            <input type="hidden" name="review_id" value="<?php echo $review->getReviewID(); ?>">
                                            <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center">No reviews found for this artwork.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include __DIR__ . 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>