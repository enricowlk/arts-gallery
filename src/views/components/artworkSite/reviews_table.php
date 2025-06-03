<!-- Table for displaying all reviews related to an artwork -->
<table class="table table-hover review-table">
    <thead>
        <tr>
            <th>Customer</th>             
            <th>Rating</th>              
            <th>Comment</th>             
            <th>Date</th>                 
            <th>City (Country)</th>       

            <!-- Extra column shown only to admins for delete functionality -->
            <?php if ($isAdmin) { ?>
                <th> </th>
            <?php } ?>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($reviews)) { ?>
            <!-- Loop through all available reviews -->
            <?php foreach ($reviews as $review) { ?>
                <?php
                    // Fetch the customer data associated with this review
                    $customer = $customerRepo->getCustomerByID($review->getCustomerId());
                ?>
                <tr>
                    <!-- Display customer full name -->
                    <td><?php echo $customer->getFirstName() . ' ' . $customer->getLastName(); ?></td>

                    <!-- Display rating with star icon -->
                    <td class="small">
                        <?php echo $review->getRating(); ?>/5 
                        <img src="../../images/icon_gelberStern.png" alt="Star" class="star-icon">
                    </td>

                    <!-- Display the review comment -->
                    <td><?php echo $review->getComment(); ?></td>

                    <!-- Display review submission date -->
                    <td class="small"><?php echo $review->getReviewDate(); ?></td>

                    <!-- Display customer's city and country -->
                    <td><?php echo $customer->getCity(); ?> (<?php echo $customer->getCountry(); ?>)</td>

                    <?php if ($isAdmin) { ?>
                        <!-- Admin-only: Delete button for review -->
                        <td>
                            <form action="../controllers/delete_review.php" method="post">
                                <!-- Hidden fields to pass review and artwork ID -->
                                <input type="hidden" name="review_id" value="<?php echo $review->getReviewID(); ?>">
                                <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">

                                <!-- Delete button with confirmation prompt -->
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
            <!-- Message shown when no reviews are found -->
            <tr>
                <td colspan="5" class="text-center">No reviews found for this artwork.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
