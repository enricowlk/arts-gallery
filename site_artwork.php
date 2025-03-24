<?php
session_start(); // Startet die Session

require_once 'database.php'; // Bindet die Database-Klasse ein
require_once 'ArtworkRepository.php'; // Bindet die ArtworkRepository-Klasse ein
require_once 'ArtistRepository.php'; // Bindet die ArtistRepository-Klasse ein
require_once 'ReviewRepository.php'; // Bindet die ReviewRepository-Klasse ein
require_once 'CustomerRepository.php'; // Bindet die CustomerRepository-Klasse ein

// Überprüft, ob der Benutzer angemeldet ist
$isLoggedIn = isset($_SESSION['user']);

// Überprüft, ob eine Kunstwerk-ID übergeben wurde
if (isset($_GET['id'])) {
    $artworkId = $_GET['id']; // Holt die Kunstwerk-ID aus dem GET-Parameter
} else {
    $artworkId = null; // Falls keine ID übergeben wurde
}

// Erstellt Instanzen der Repository-Klassen
$artworkRepo = new ArtworkRepository(new Database());
$artistRepo = new ArtistRepository(new Database());
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());

// Holt die Kunstwerkdaten, den Künstler und die Bewertungen
$artwork = $artworkRepo->getArtworkById($artworkId); // Holt das Kunstwerk anhand der ID
$artist = $artistRepo->getArtistById($artwork->getArtistID()); // Holt den Künstler des Kunstwerks
$reviews = $reviewRepo->getAllReviewsForOneArtworkByArtworkId($artworkId); // Holt die Bewertungen des Kunstwerks

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
    </style>
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Kunstwerkinformationen -->
        <div class="row">
            <div class="col-md-6">
                <!-- Bild als Link zum Modal -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                    <img src="images/works/medium/<?php echo $artwork->getImageFileName(); ?>.jpg" class="artwork-image" alt="<?php echo $artwork->getTitle(); ?>">
                </a>
                
                <!-- Bewertungsanzahl und Favoriten-Button in einer Zeile -->
                <div class="action-row"> 
                    <!-- Button zum Hinzufügen/Entfernen des Kunstwerks aus den Favoriten -->
                    <form action="favorites_process.php" method="post" class="favorite-form">
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
                    <div class="rating-container">
                        <?php if ($reviewCount > 0){ ?>
                            <span class="rating-text"><strong><?php echo number_format($averageRating, 1); ?>/5 </strong></span>
                            <img src="images/icon_gelberStern.png" alt="Star">
                            <span>(<?php echo $reviewCount; ?> reviews)</span>
                        <?php }else{ ?>
                            <span class="rating-text">No ratings yet</span>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info">
                    <!-- Titel des Kunstwerks -->
                    <h1><?php echo $artwork->getTitle(); ?></h1>
                    <!-- Künstlername -->
                    <p class="lead">By <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></a></p>
                    <!-- Jahr des Kunstwerks -->
                    <p><strong>Year of Work:</strong> <?php echo $artwork->getYearOfWork(); ?></p>
                    <!-- Medium des Kunstwerks -->
                    <p><strong>Medium:</strong> <?php echo $artwork->getMedium(); ?></p>
                    <!-- Beschreibung des Kunstwerks -->
                    <p><strong>Description:</strong> <?php echo $artwork->getDescription(); ?></p>
                    <!-- Ursprünglicher Aufbewahrungsort -->
                    <p><strong>Original Home:</strong> <?php echo $artwork->getOriginalHome(); ?></p>
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
                <h5 class="card-title">Add Your Review</h5>
                <form action="add_review.php" method="post">
                    <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                    <div class="row">
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
                            <button type="submit" class="btn btn-secondary w-100">Submit Review</button>
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

    <!-- Modal für das größere Bild -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"><?php echo $artwork->getTitle(); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Großes Bild des Kunstwerks -->
                    <img src="images/works/large/<?php echo $artwork->getImageFileName(); ?>.jpg" class="img-fluid" alt="<?php echo $artwork->getTitle(); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>