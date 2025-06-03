<!-- Only display the gallery section if the gallery object exists and contains valid coordinates -->
<?php if ($gallerie && $gallerie->getLatitude() && $gallerie->getLongitude()) { ?>
    <div class="accordion mt-4">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <!-- Accordion toggle button -->
                <button class="accordion-button collapsed" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#galleryInfo">
                    <strong>Click me to see my Location!</strong>
                </button>
            </h2>
            
            <!-- Collapsible accordion content with gallery details and map -->
            <div id="galleryInfo" class="collapse" data-bs-parent="#galleryAccordion">
                <div class="accordion-body">

                    <!-- Basic gallery information -->
                    <p><strong>Name:</strong> <?php echo $gallerie->getGalleryName(); ?></p>
                    
                    <?php if ($gallerie->getGalleryNativeName()) { ?>
                        <p><strong>Native Name:</strong> <?php echo $gallerie->getGalleryNativeName(); ?></p>
                    <?php } ?>
                    
                    <p>
                        <strong>Location:</strong> 
                        <?php echo $gallerie->getGalleryCity(); ?>, 
                        <?php echo $gallerie->getGalleryCountry(); ?>
                    </p>
                    
                    <?php if ($gallerie->getGalleryWebSite()) { ?>
                        <p><strong>Website:</strong> 
                            <a class="btn btn-secondary" href="<?php echo $gallerie->getGalleryWebSite(); ?>" target="_blank">
                                Visit Museum Website
                            </a>
                        </p>
                    <?php } ?>

                    <!-- Embedded map showing the gallery location -->
                    <div>
                        <strong>Location Map:</strong>
                        
                        <!-- Responsive 16:9 embedded Google Maps iframe using gallery coordinates -->
                        <div class="ratio ratio-16x9 mt-2">
                            <iframe 
                                src="https://maps.google.com/maps?q=<?php echo $gallerie->getLatitude(); ?>,<?php echo $gallerie->getLongitude(); ?>&z=15&output=embed"
                                width="100%" 
                                height="300" 
                                frameborder="0" 
                                style="border:0"
                                allowfullscreen>
                            </iframe>
                        </div>
                        
                        <!-- External link to open map in Google Maps -->
                        <div class="map-links mt-2">
                            <a href="https://www.google.com/maps?q=<?php echo $gallerie->getLatitude(); ?>,<?php echo $gallerie->getLongitude(); ?>" 
                               class="btn btn-secondary btn-sm" target="_blank">
                                Open in Google Maps
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>
