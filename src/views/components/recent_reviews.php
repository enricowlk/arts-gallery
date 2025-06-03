<?php
/**
 * Loads and displays the 3 most recent artwork reviews.
 * 
 * This script connects to the database and fetches recent reviews
 * using the ReviewRepository and CustomerRepository.
 */

// Include required files for database connection and repositories
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../repositories/reviewRepository.php';
require_once __DIR__ . '/../../repositories/customerRepository.php';

// Instantiate repository classes
$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());

/**
 * Retrieves the 3 most recent reviews.
 * 
 * @var array $recentReviews Array of Review objects
 */
$recentReviews = $reviewRepo->get3RecentReviews(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recent Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Container for displaying the recent reviews -->
    <div class="recent-reviews-container">
        <?php if (!empty($recentReviews)) { ?>
            <ul>
                <?php foreach ($recentReviews as $review) { 
                    // Get the name of the customer who wrote the review
                    $customerName = $customerRepo->getCustomerNameById($review->getCustomerId());
                ?>
                    <!-- Link to the artwork detail page -->
                    <a href="../arts-gallery/src/views/site_artwork.php?id=<?php echo $review->getArtWorkId(); ?>">
                        <li>
                            <!-- Display customer's name and truncated comment -->
                            <strong><?php echo $customerName; ?></strong> wrote:
                            <?php echo substr($review->getComment(), 0, 100); ?>...
                            <br>
                            <small>
                                <!-- Display rating and review date -->
                                Rating: <?php echo $review->getRating(); ?>/5 
                                <img src="images/icon_gelberStern.png" alt="Star">
                                | Date: <?php echo $review->getReviewDate(); ?>
                            </small>
                        </li>
                    </a>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <!-- Message shown if no reviews exist -->
            <p class="text-center">No recent reviews found.</p>
        <?php } ?>
    </div>

</body>
</html>
