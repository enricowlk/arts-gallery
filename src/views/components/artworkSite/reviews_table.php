<!-- Tabelle zur Anzeige von Bewertungen -->
<table class="table table-hover review-table">
    <thead>
        <tr>
            <th>Customer</th>  
            <th>Rating</th>    
            <th>Comment</th>  
            <th>Date</th>      
            <th>City (Country)</th>  
            <?php if ($isAdmin) { ?>
                <th> </th>  <!-- Zusätzliche Spalte für Admin-Löschbutton -->
            <?php } ?>
        </tr>
    </thead>
    
    <!-- Tabelleninhalt -->
    <tbody>
        <?php if (!empty($reviews)) { ?>
            <!-- Schleife durch alle Bewertungen -->
            <?php foreach ($reviews as $review) { ?>
                <?php
                    // Holt Kundendaten für die aktuelle Bewertung
                    $customer = $customerRepo->getCustomerByID($review->getCustomerId());
                ?>
                <tr>
                    <!-- Zeigt Vor- und Nachname des Kunden -->
                    <td><?php echo $customer->getFirstName() . ' ' . $customer->getLastName(); ?></td>
                    
                    <!-- Bewertung (1-5) mit Stern-Icon -->
                    <td class="small">
                        <?php echo $review->getRating(); ?>/5 
                        <img src="../../images/icon_gelberStern.png" alt="Star" class="star-icon">
                    </td>
                    
                    <!-- Bewertungskommentar -->
                    <td><?php echo $review->getComment(); ?></td>
                    
                    <!-- Datum der Bewertung -->
                    <td class="small"><?php echo $review->getReviewDate(); ?></td>
                    
                    <!-- Stadt und Land des Kunden -->
                    <td><?php echo $customer->getCity(); ?> (<?php echo $customer->getCountry(); ?>)</td>
                    
                    <?php if ($isAdmin) { ?>
                        <!-- Nur für Admins sichtbar: Lösch-Button -->
                        <td>
                            <form action="../controllers/delete_review.php" method="post">
                                <input type="hidden" name="review_id" value="<?php echo $review->getReviewID(); ?>">
                                <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                                
                                <!-- Lösch-Button mit Bestätigungsdialog -->
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
            <!-- Falls keine Bewertungen vorhanden sind -->
            <tr>
                <td colspan="5" class="text-center">No reviews found for this artwork.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>