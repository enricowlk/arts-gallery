<?php
// Include required classes
require_once __DIR__ . '/../entitys/reviews.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Repository class for accessing review data from the database.
 */
class ReviewRepository {
    /**
     * @var Database $db Database connection instance
     */
    private $db;

    /**
     * Constructor for ReviewRepository.
     *
     * @param Database $db An instance of the database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves the most recent reviews from the database.
     *
     * @param int $limit The maximum number of reviews to return (default is 3)
     * @return Review[] Array of Review objects
     * @throws Exception If a database error occurs
     */
    public function get3RecentReviews($limit = 3) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM reviews ORDER BY ReviewDate DESC LIMIT $limit";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch()) {
                $reviews[] = new Review(
                    $row['ReviewId'],
                    $row['ArtWorkId'],
                    $row['CustomerId'],
                    $row['ReviewDate'],
                    $row['Rating'],
                    $row['Comment']
                );
            }
            return $reviews;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching recent reviews");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Counts the number of reviews for a given artist.
     *
     * @param int $artistId The artist's ID
     * @return int Number of reviews
     * @throws Exception If a database error occurs
     */
    public function getReviewCountForArtist($artistId) {
        try {
            $this->db->connect();
            $sql = "SELECT COUNT(reviews.ReviewID) as ReviewCount 
                    FROM reviews 
                    INNER JOIN artworks ON reviews.ArtWorkID = artworks.ArtWorkID 
                    WHERE artworks.ArtistID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artistId]);
            $row = $stmt->fetch();

            return $row['ReviewCount'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while counting artist reviews");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves all reviews for a specific artwork.
     *
     * @param int $artworkId The artwork's ID
     * @return Review[] Array of Review objects
     * @throws Exception If a database error occurs
     */
    public function getAllReviewsForOneArtworkByArtworkId($artworkId) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM reviews WHERE ArtWorkID = ? ORDER BY ReviewDate DESC";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId]);

            $reviews = [];

            while ($row = $stmt->fetch()) {
                $reviews[] = new Review(
                    $row['ReviewId'],
                    $row['ArtWorkId'],
                    $row['CustomerId'],
                    $row['ReviewDate'],
                    $row['Rating'],
                    $row['Comment']
                );
            }
            return $reviews;

        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork reviews");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Adds a new review to the database.
     *
     * @param int $artworkId The ID of the artwork being reviewed
     * @param int $customerId The ID of the user submitting the review
     * @param int $rating Rating value (1–5)
     * @param string $comment The review comment
     * @throws Exception If a database error occurs
     */
    public function addReview($artworkId, $customerId, $rating, $comment) {
        try {
            $this->db->connect();
            $sql = "INSERT INTO reviews (ArtWorkID, CustomerID, Rating, Comment, ReviewDate) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId, $customerId, $rating, $comment]);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while adding review");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The review's ID
     * @return PDOStatement The executed statement
     * @throws Exception If a database error occurs
     */
    public function deleteReview($reviewId) {
        try {
            $this->db->connect();
            $sql = "DELETE FROM reviews WHERE ReviewID = :reviewId";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['reviewId' => $reviewId]);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while deleting review");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Checks if a user has already submitted a review for a specific artwork.
     *
     * @param int $artworkId The ID of the artwork
     * @param int $customerId The ID of the user
     * @return bool true if a review exists, false otherwise
     * @throws Exception If a database error occurs
     */
    public function hasUserReviewedArtwork($artworkId, $customerId) {
        try {
            $this->db->connect();
            $sql = "SELECT COUNT(*) as review_count 
                    FROM reviews 
                    WHERE ArtWorkID = ? AND CustomerID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId, $customerId]);
            $row = $stmt->fetch();
            return ($row['review_count'] > 0);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while checking user reviews");
        } finally {
            $this->db->close();
        }
    }
}
?>
