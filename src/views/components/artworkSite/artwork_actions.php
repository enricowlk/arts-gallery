<!-- Aktionsleiste mit Favoriten-Button und Bewertungsanzeige -->
<div class="action-row">
    
    <!-- Favoriten-Formular -->
    <form action="../controllers/favorites_process.php" method="post">
        <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
        
        <!-- Dynamischer Button, der die Farbe basierend auf Favoriten-Status ändert -->
        <button type="submit" class="btn 
            <?php
                if ($isFavoriteArtwork) {
                    echo 'btn-danger'; 
                } else {
                    echo 'btn-secondary'; 
                }
            ?>">
            <?php
                // Dynamischer Button-Text basierend auf Favoriten-Status
                if ($isFavoriteArtwork) {
                    echo 'Remove from Favorites'; 
                } else {
                    echo 'Add to Favorites'; 
                }
            ?>
        </button>
    </form>

    <!-- Bewertungsbereich -->
    <div>
        <?php if ($reviewCount > 0){ ?>
            <!-- Durchschnittsbewertung (1 Dezimalstelle) -->
            <span><strong><?php echo number_format($averageRating, 1); ?>/5 </strong></span>
            
            <!-- Stern-Icon für Bewertungen -->
            <img src="../../images/icon_gelberStern.png" alt="Star">
            
            <!-- Anzahl der Bewertungen -->
            <span>(<?php echo $reviewCount; ?> reviews)</span>
        <?php }else{ ?>
            <!-- Fallback, wenn keine Bewertungen vorhanden sind -->
            <span>No ratings yet</span>
        <?php } ?>
    </div>
</div>