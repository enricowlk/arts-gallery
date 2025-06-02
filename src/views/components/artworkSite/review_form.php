<div>
    <div>
        <h5>Add Your Review</h5>
        
        <!-- Formular zum Hinzufügen einer Bewertung (wird an add_review.php gesendet) -->
        <form action="../controllers/add_review.php" method="post">
            <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
            
            <div>
                <div class="col-md-3">
                    <label class="form-label">Rating</label>
                    <!-- Dropdown für die Bewertung (1-5 Sterne) -->
                    <select class="form-control" id="rating" name="rating" required>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                
                <!-- Kommentar-Eingabe -->
                <div class="col-md-6 mt-2">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                </div>
            </div>
            
            <!-- Submit-Button -->
            <div class="mt-1">
                <button type="submit" class="btn btn-secondary">Submit Review</button>
            </div>
        </form>
    </div>
</div>