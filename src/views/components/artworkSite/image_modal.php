<!-- Modal dialog for displaying a larger version of the artwork image -->
<div class="modal fade" id="imageModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal header section -->
            <div class="modal-header">
                <!-- Artwork title displayed as modal title -->
                <h5 class="modal-title"><?php echo $artwork->getTitle(); ?></h5>
                
                <!-- Close button for dismissing the modal -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal body containing the full-size image -->
            <div>
                <img src="<?php echo $imageUrl; ?>" alt="<?php echo $artwork->getTitle(); ?>">
            </div>

        </div>
    </div>
</div>
