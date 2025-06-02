<?php
// Einbinden der benötigten Dateien für Datenbankverbindung und Repository-Klassen
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/reviewRepository.php'; 
require_once __DIR__ . '/../../repositories/customerRepository.php'; 

// Instanziierung der Repository-Klassen
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());

// Abruf der 3 neuesten Bewertungen
$recentReviews = $reviewRepo->get3RecentReviews(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Container für die neuesten Bewertungen -->
    <div class="recent-reviews-container">
        <?php if (!empty($recentReviews)){ ?>
            <ul>
                <?php foreach ($recentReviews as $review){ 
                    // Abruf des Kundennamens für die aktuelle Bewertung
                    $customerName = $customerRepo->getCustomerNameById($review->getCustomerId());
                ?>
                    <!-- Link zur Kunstwerk-Seite mit der entsprechenden ID -->
                    <a href="../arts-gallery/src/views/site_artwork.php?id=<?php echo $review->getArtWorkId(); ?>">
                    <li>
                        <!-- Anzeige des Kundennamens und des gekürzten Kommentars -->
                        <strong><?php echo $customerName; ?></strong> wrote:
                            <?php echo substr($review->getComment(), 0, 100); ?>...
                        <br>
                        <small>
                            <!-- Anzeige der Bewertung (1-5 Sterne) und des Datums -->
                            Rating: <?php echo $review->getRating(); ?>/5 
                            <img src="images/icon_gelberStern.png" alt="Star">
                            | Date: <?php echo $review->getReviewDate(); ?>
                        </small>
                    </li>
                    </a>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <!-- Fallback, wenn keine Bewertungen vorhanden sind -->
            <p class="text-center">No recent reviews found.</p>
        <?php } ?>
    </div>
</body>
</html>