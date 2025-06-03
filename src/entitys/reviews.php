<?php
/**
 * Class representing an artwork review.
 * Contains customer reviews for artworks in the system.
 */
class Review {
    private $ReviewId;     
    private $ArtWorkId;   
    private $CustomerId;   
    private $ReviewDate;
    private $Rating;      
    private $Comment;     

    /**
     * Review constructor.
     *
     * @param int $ReviewId Unique identifier for the review
     * @param int $ArtWorkId Identifier of the reviewed artwork
     * @param int $CustomerId Identifier of the customer
     * @param string $ReviewDate Date when the review was created
     * @param int $Rating Rating given by the customer
     * @param string $Comment Text comment of the review
     */
    public function __construct($ReviewId, $ArtWorkId, $CustomerId, $ReviewDate, $Rating, $Comment) {
        $this->ReviewId = $ReviewId;
        $this->ArtWorkId = $ArtWorkId;
        $this->CustomerId = $CustomerId;
        $this->ReviewDate = $ReviewDate;
        $this->Rating = $Rating;
        $this->Comment = $Comment;
    }

    /**
     * Get the review ID.
     *
     * @return int Review unique identifier
     */
    public function getReviewId() {
        return $this->ReviewId;
    }

    /**
     * Get the artwork ID related to this review.
     *
     * @return int Artwork identifier
     */
    public function getArtWorkId() {
        return $this->ArtWorkId;
    }

    /**
     * Get the customer ID who wrote the review.
     *
     * @return int Customer identifier
     */
    public function getCustomerId() {
        return $this->CustomerId;
    }

    /**
     * Get the date when the review was created.
     *
     * @return string Review creation date
     */
    public function getReviewDate() {
        return $this->ReviewDate;
    }

    /**
     * Get the rating given in the review.
     *
     * @return int Rating value
     */
    public function getRating() {
        return $this->Rating;
    }

    /**
     * Get the comment text of the review.
     *
     * @return string Review comment
     */
    public function getComment() {
        return $this->Comment;
    }
}
