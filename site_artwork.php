<?php
session_start(); // Startet die Session

require_once 'database.php'; // Bindet die Database-Klasse ein
require_once 'artworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein
require_once 'artistRepository.php'; // Bindet die ArtistRepository-Klasse ein
require_once 'reviewRepository.php'; // Bindet die ReviewRepository-Klasse ein
require_once 'customerRepository.php'; // Bindet die CustomerRepository-Klasse ein
require_once 'genreRepository.php'; // Bindet die GenreRepository-Klasse ein
require_once 'subjectRepository.php'; // Bindet die SubjectRepository-Klasse ein
require_once 'gallerieRepository.php'; // Bindet die GallerieRepository-Klasse ein

// Überprüft, ob der Benutzer angemeldet ist
$isLoggedIn = isset($_SESSION['user']);

// Überprüft, ob der Benutzer ein Admin ist
$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;


// Überprüft, ob eine gültige Kunstwerk-ID übergeben wurde
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?message=Invalid or missing artwork ID");
    exit();
}

$artworkId = (int)$_GET['id']; // Validiert und konvertiert die ID

// Erstellt Instanzen der Repository-Klassen
$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());
$genreRepo = new GenreRepository(new Database());
$subjectRepo = new SubjectRepository(new Database());
$gallerieRepo = new GallerieRepository(new Database());


$artwork = $artworkRepo->getArtworkById($artworkId); // Holt das Kunstwerk anhand der ID
// Überprüft, ob das Kunstwerk existiert
if ($artwork === null) {
    header("Location: error.php?message=Artwork not found");
    exit();
}

$artist = $artistRepo->getArtistById($artwork->getArtistID()); // Holt den Künstler des Kunstwerks
$reviews = $reviewRepo->getAllReviewsForOneArtworkByArtworkId($artworkId); // Holt die Bewertungen des Kunstwerks

// Holt das Genre und Subject für das Kunstwerk
$genres = $artworkRepo->getGenreForOneArtworkByArtworkId($artworkId); // Holt das Genre des Kunstwerks
$subjects = $artworkRepo->getSubjectForOneArtworkByArtworkId($artworkId); // Holt das Subject des Kunstwerks

$gallerie = $gallerieRepo->getGalleryByArtworkId($artworkId); // Holt die Gallerie des Kunstwerks

// Berechnet die durchschnittliche Bewertung und Anzahl der Bewertungen
$averageRating = $artworkRepo->getAverageRatingForArtwork($artworkId);
$reviewCount = count($reviews);

// Überprüft, ob das Kunstwerk in den Favoriten ist
$isFavoriteArtwork = isset($_SESSION['favorite_artworks']) && in_array($artworkId, $_SESSION['favorite_artworks']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Titel der Seite mit dem Namen des Kunstwerks -->
    <title><?php echo $artwork->getTitle(); ?> - Art Gallery</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Stil für das Kunstwerkbild */
        .artwork-image {
            width: 100%;
            height: 300px; /* Feste Höhe für die Bilder */
            object-fit: cover; /* Bild wird zugeschnitten, um den Container zu füllen */
            border-radius: 10px; /* Abgerundete Ecken */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten hinzufügen */
        }
        /* Stil für die Bewertungs- und Favoritenzeile */
        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .favorite-form {
            margin: 0;
        }
        /* Spezifisch für die Links in genre-subject-links */
        .artwork-links a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .artwork-links a:hover {
            color: black ;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Kunstwerkinformationen -->
        <div class="row">
            <div class="col-md-6">
                <!-- Kunstwerk -->
                <?php
                $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                $imageExists = file_exists($imagePath);

                if (file_exists($imagePath)) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <!-- Bild als Link zum Modal -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                    <img src="<?php echo $imageUrl; ?>" class="artwork-image" alt="<?php echo $artwork->getTitle(); ?>">
                </a>

                <!-- Modal für das größere Bild -->
                <div class="modal fade" id="imageModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel"><?php echo $artwork->getTitle(); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Großes Bild des Kunstwerks -->
                                <img src="<?php echo $imageUrl; ?>" class="img-thumbnail" alt="<?php echo $artwork->getTitle(); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bewertungsanzahl und Favoriten-Button in einer Zeile -->
                <div class="action-row"> 
                    <!-- Button zum Hinzufügen/Entfernen des Kunstwerks aus den Favoriten -->
                    <form action="favorites_process.php" method="post">
                        <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                        <button type="submit" class="btn 
                            <?php
                                if ($isFavoriteArtwork) {
                                    echo 'btn-danger'; // Roter Button, wenn das Kunstwerk in den Favoriten ist
                                } else {
                                    echo 'btn-secondary'; // Grauer Button, wenn das Kunstwerk nicht in den Favoriten ist
                                }
                            ?>">
                            <?php
                                if ($isFavoriteArtwork) {
                                    echo 'Remove from Favorites'; // Text für den Button, wenn das Kunstwerk in den Favoriten ist
                                } else {
                                    echo 'Add to Favorites'; // Text für den Button, wenn das Kunstwerk nicht in den Favoriten ist
                                }
                            ?>
                        </button>
                    </form>
                    <!-- Bewertungsanzeige -->
                    <div>
                        <?php if ($reviewCount > 0){ ?>
                            <span class="rating-text"><strong><?php echo number_format($averageRating, 1); ?>/5 </strong></span>
                            <img src="images/icon_gelberStern.png" alt="Star">
                            <span>(<?php echo $reviewCount; ?> reviews)</span>
                        <?php }else{ ?>
                            <span>No ratings yet</span>
                        <?php } ?>
                    </div>
                </div>

                <!-- Gallery Information Accordion -->
                <?php if ($gallerie && $gallerie->getLatitude() && $gallerie->getLongitude()) { ?>
                        <div class="accordion mt-3 mb-3" id="galleryAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#galleryInfo">
                                        <strong>Home of this Artwork</strong>
                                    </button>
                                </h2>
                                <div id="galleryInfo" class="accordion-collapse collapse" 
                                    data-bs-parent="#galleryAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Name:</strong> <?php echo $gallerie->getGalleryName(); ?></p>
                                        <?php if ($gallerie->getGalleryNativeName()) { ?>
                                            <p><strong>Native Name:</strong> <?php echo $gallerie->getGalleryNativeName(); ?></p>
                                        <?php } ?>
                                        <p><strong>Location:</strong> <?php echo $gallerie->getGalleryCity(); ?>, <?php echo $gallerie->getGalleryCountry(); ?></p>
                                        
                                        <?php if ($gallerie->getGalleryWebSite()) { ?>
                                            <p><strong>Website:</strong> 
                                                <a class="btn btn-secondary btn-sm" href="<?php echo $gallerie->getGalleryWebSite(); ?>">
                                                    Visit Museum Website
                                                </a>
                                            </p>
                                        <?php } ?>
                                        
                                        <div class="mt-3">
                                            <strong>Location Map:</strong>
                                            <!-- Eingebettete Google Maps Karte -->
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
                    <!-- Titel des Kunstwerks -->
                    <h1><?php echo $artwork->getTitle(); ?></h1>
                    <!-- Künstlername -->
                    <p class="artwork-links">By <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></a></p>
                    <!-- Jahr des Kunstwerks -->
                    <p><strong>Year of Work:</strong> <?php echo $artwork->getYearOfWork(); ?></p>
                    <!-- Medium des Kunstwerks -->
                    <p><strong>Medium:</strong> <?php echo $artwork->getMedium(); ?></p>
                    <!-- Beschreibung des Kunstwerks -->
                    <p><strong>Description:</strong> <?php echo $artwork->getDescription(); ?></p>
                    
                    <!-- Genres und Subjects anzeigen -->
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

                    <!-- Link zu weiteren Informationen -->
                    <p> You want to know more about "<strong><?php echo $artwork->getTitle(); ?></strong>"?</p>
                    <a href="<?php echo $artwork->getArtWorkLink(); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Bewertungen -->
        <h2 class="mt-5">Reviews</h2>

        <!-- Formular zum Hinzufügen einer Bewertung (nur für angemeldete Benutzer) -->
        <?php if($isLoggedIn){ ?>
        <div>
            <div>
                <h5>Add Your Review</h5>
                <form action="add_review.php" method="post">
                    <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                    <div class="card-column">
                        <div class="col-md-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-control" id="rating" name="rating" required>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Good</option>
                                <option value="4">4 - Very Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary">Submit Review</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>

        <!-- Tabelle der Bewertungen -->
        <div class="table-responsive">
            <table class="table table-hover review-table">
                <thead class="table-light">
                    <tr>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>City (Country)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reviews)) { ?>
                        <?php foreach ($reviews as $review) { ?>
                            <?php
                                // Holt die Kundendaten für die Bewertung
                                $customer = $customerRepo->getCustomerByID($review->getCustomerId());
                            ?>
                            <tr>
                                <!-- Kundenname -->
                                <td><?php echo $customer->getFirstName() . ' ' . $customer->getLastName(); ?></td>
                                <!-- Bewertung -->
                                <td class="rating">
                                    <?php echo $review->getRating(); ?>/5 
                                    <img src="images/icon_gelberStern.png" alt="Star" class="star-icon">
                                </td>
                                <!-- Kommentar -->
                                <td class="comment"><?php echo $review->getComment(); ?></td>
                                <!-- Datum der Bewertung -->
                                <td class="date"><?php echo $review->getReviewDate(); ?></td>
                                <!-- Stadt und Land des Kunden -->
                                <td class="city(country)"><?php echo $customer->getCity(); ?> (<?php echo $customer->getCountry(); ?>)</td>
                                <!-- Loeschbutton (nur fuer Admins) -->
                                <?php if ($isAdmin) { ?>
                                    <td>
                                        <form action="delete_review.php" method="post" style="display: inline;">
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
                        <!-- Meldung, falls keine Bewertungen gefunden wurden -->
                        <tr>
                            <td colspan="5" class="text-center">No reviews found for this artwork.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>