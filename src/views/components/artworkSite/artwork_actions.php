<!-- Action bar containing favorites button and rating display -->
<div class="action-row">
    
    <!-- Favorites form -->
    <form action="../controllers/favorites_process.php" method="post">
        <!-- Hidden field to store the artwork ID -->
        <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
        
        <!-- Dynamic button: color changes based on whether artwork is in favorites -->
        <button type="submit" class="btn 
            <?php
                // Apply style: red for "Remove", beige for "Add"
                echo $isFavoriteArtwork ? 'btn-danger' : 'btn-secondary';
            ?>">
            <?php
                // Display appropriate label based on favorite status
                echo $isFavoriteArtwork ? 'Remove from Favorites' : 'Add to Favorites';
            ?>
        </button>
    </form>

    <!-- Rating display area -->
    <div>
        <?php if ($reviewCount > 0){ ?>
            <!-- Average rating with one decimal -->
            <span><strong><?php echo number_format($averageRating, 1); ?>/5 </strong></span>
            
            <!-- Star icon representing rating visually -->
            <img src="../../images/icon_gelberStern.png" alt="Star">
            
            <!-- Total number of reviews -->
            <span>(<?php echo $reviewCount; ?> reviews)</span>
        <?php } else { ?>
            <!-- Fallback message if no reviews are available -->
            <span>No ratings yet</span>
        <?php } ?>
    </div>
</div>
