<?php
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../repositories/reviewRepository.php'; 
require_once __DIR__ . '/../../repositories/customerRepository.php'; 

$reviewRepo = new ReviewRepository(new Database());
$customerRepo = new CustomerRepository(new Database());

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
    <div class="recent-reviews-container">
        <?php if (!empty($recentReviews)){ ?>
            <ul>
                <?php foreach ($recentReviews as $review){ 
                    $customerName = $customerRepo->getCustomerNameById($review->getCustomerId());
                ?>
                    <a href="../arts-gallery/src/views/site_artwork.php?id=<?php echo $review->getArtWorkId(); ?>">
                    <li>
                        <strong><?php echo $customerName; ?></strong> wrote:
                            <?php echo substr($review->getComment(), 0, 100); ?>...
                        <br>
                        <small>
                            Rating: <?php echo $review->getRating(); ?>/5 
                            <img src="images/icon_gelberStern.png" alt="Star">
                            | Date: <?php echo $review->getReviewDate(); ?>
                        </small>
                    </li>
                    </a>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <p class="text-center">No recent reviews found.</p>
        <?php } ?>
    </div>
</body>
</html>