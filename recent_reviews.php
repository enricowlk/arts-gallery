<?php
require_once 'database.php';
require_once 'ReviewRepository.php';
require_once 'CustomerRepository.php';

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
            <ul class="list-unstyled">
                <?php foreach ($recentReviews as $review){ 
                    $customerName = $customerRepo->getCustomerNameById($review->getCustomerId());
                ?>
                    <li class="mb-3">
                        <strong><?php echo $customerName; ?></strong> wrote:
                        <a href="site_artwork.php?id=<?php echo $review->getArtWorkId(); ?>">
                            <?php echo substr($review->getComment(), 0, 100); ?>...
                        </a>
                        <br>
                        <small>
                            Rating: <?php echo $review->getRating(); ?>/5 
                            <img src="images/icon_gelberStern.png" alt="Star">
                            | Date: <?php echo $review->getReviewDate(); ?>
                        </small>
                    </li>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <p class="text-center">No recent reviews found.</p>
        <?php } ?>
    </div>
</body>
</html>