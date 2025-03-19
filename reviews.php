<?php
class Review {
    private $ReviewId;
    private $ArtWorkId;
    private $CustomerId;
    private $ReviewDate;
    private $Rating;
    private $Comment;

    public function __construct($ReviewId, $ArtWorkId, $CustomerId, $ReviewDate, $Rating, $Comment) {
        $this->ReviewId = $ReviewId;
        $this->ArtWorkId = $ArtWorkId;
        $this->CustomerId = $CustomerId;
        $this->ReviewDate = $ReviewDate;
        $this->Rating = $Rating;
        $this->Comment = $Comment;
    }

    // Getter und Setter für ReviewId
    public function getReviewId() {
        return $this->ReviewId;
    }

    public function setReviewId($ReviewId) {
        $this->ReviewId = $ReviewId;
    }

    // Getter und Setter für ArtWorkId
    public function getArtWorkId() {
        return $this->ArtWorkId;
    }

    public function setArtWorkId($ArtWorkId) {    
        $this->ArtWorkId = $ArtWorkId;
    }

    // Getter und Setter für CustomerId
    public function getCustomerId() {
        return $this->CustomerId;
    }

    public function setCustomerId($CustomerId) {    
        $this->CustomerId = $CustomerId;
    }

    // Getter und Setter für ReviewDate
    public function getReviewDate() {
        return $this->ReviewDate;
    }

    public function setReviewDate($ReviewDate) {    
        $this->ReviewDate = $ReviewDate;
    }

    // Getter und Setter für Rating
    public function getRating() {
        return $this->Rating;
    }

    public function setRating($Rating) {    
        $this->Rating = $Rating;
    }

    // Getter und Setter für Comment
    public function getComment() {
        return $this->Comment;
    }

    public function setComment($Comment) {    
        $this->Comment = $Comment;
    }
}
?>