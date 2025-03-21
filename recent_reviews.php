<?php
require_once 'database.php'; // Bindet die Database-Klasse ein
require_once 'ReviewRepository.php'; // Bindet die ReviewRepository-Klasse ein
require_once 'CustomerRepository.php'; // Bindet die CustomerRepository-Klasse ein

// Erstellt Instanzen der Repository-Klassen
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());

// Holt die 3 neuesten Bewertungen
$recentReviews = $reviewRepo->get3RecentReviews(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="recent-reviews-container">
        <?php if (!empty($recentReviews)){ ?>
            <!-- Liste der neuesten Bewertungen -->
            <ul class="list-unstyled">
                <?php foreach ($recentReviews as $review){ 
                    // Holt den Namen des Kunden anhand der CustomerID
                    $customerName = $customerRepo->getCustomerNameById($review->getCustomerId());
                ?>
                    <li class="mb-3">
                        <!-- Kundenname und Bewertungstext -->
                        <strong><?php echo $customerName; ?></strong> wrote:
                        <a href="site_artwork.php?id=<?php echo $review->getArtWorkId(); ?>">
                            <?php echo substr($review->getComment(), 0, 100); ?>...
                        </a>
                        <br>
                        <!-- Bewertungsdetails (Rating und Datum) -->
                        <small>
                            Rating: <?php echo $review->getRating(); ?>/5 
                            <img src="images/icon_gelberStern.png" alt="Star">
                            | Date: <?php echo $review->getReviewDate(); ?>
                        </small>
                    </li>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <!-- Meldung, falls keine Bewertungen gefunden wurden -->
            <p class="text-center">No recent reviews found.</p>
        <?php } ?>
    </div>
</body>
</html>