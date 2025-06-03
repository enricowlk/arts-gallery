<?php
/**
 * Class representing an artwork review.
 * Contains customer reviews for artworks in the system.
 */
class Review {
    /**
     * @var int Unique identifier for the review
     */
    private $ReviewId;     

    /**
     * @var int Identifier of the reviewed artwork
     */
    private $ArtWorkId;   

    /**
     * @var int Identifier of the customer who wrote the review
     */
    private $CustomerId;   

    /**
     * @var string Date when the review was created (e.g. YYYY-MM-DD)
     */
    private $ReviewDate;

    /**
     * @var int Rating given in the review (e.g. 1 to 5)
     */
    private $Rating;      

    /**
     * @var string Comment or text content of the review
     */
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
