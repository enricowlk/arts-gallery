<!-- Modal-Dialog für die Bildanzeige -->
<div class="modal fade" id="imageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Titel des Kunstwerks-->
                <h5 class="modal-title"><?php echo $artwork->getTitle(); ?></h5>
                <!-- Button zum Schließen des Modals -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Hauptinhalt des Modals - das Bild -->
            <div>
                <img src="<?php echo $imageUrl; ?>" alt="<?php echo $artwork->getTitle(); ?>">
            </div>
        </div>
    </div>
</div>