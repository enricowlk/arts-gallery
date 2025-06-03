<div>
    <div>
        <h5>Add Your Review</h5>
        
        <!-- Form to submit a review, sends data to add_review.php controller -->
        <form action="../controllers/add_review.php" method="post">
            <!-- Hidden field to pass the associated artwork ID -->
            <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
            
            <div>
                <!-- Rating input (dropdown from 1 to 5) -->
                <div class="col-md-3">
                    <label class="form-label">Rating</label>
                    <select class="form-control" id="rating" name="rating" required>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                
                <!-- Textarea for user to leave a comment -->
                <div class="col-md-6 mt-2">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                </div>
            </div>
            
            <!-- Submit button to send the review -->
            <div class="mt-1">
                <button type="submit" class="btn btn-secondary">Submit Review</button>
            </div>
        </form>
    </div>
</div>
