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

    public function getReviewId() {
        return $this->ReviewId;
    }

    public function getArtWorkId() {
        return $this->ArtWorkId;
    }

    public function getCustomerId() {
        return $this->CustomerId;
    }

    public function getReviewDate() {
        return $this->ReviewDate;
    }

    public function getRating() {
        return $this->Rating;
    }

    public function getComment() {
        return $this->Comment;
    }
}
?>