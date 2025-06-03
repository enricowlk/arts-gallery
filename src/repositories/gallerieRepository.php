<?php
// Include required classes
require_once __DIR__ . '/../entitys/gallerie.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Repository class for accessing gallery data from the database.
 */
class GallerieRepository {
    /**
     * @var Database $db Database connection instance
     */
    private $db;

    /**
     * Constructor for the GallerieRepository.
     *
     * @param Database $db An instance of the database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves all galleries from the database.
     *
     * @return Gallerie[] Array of Gallerie objects
     * @throws Exception If a database error occurs
     */
    public function getAllGalleries() {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM galleries";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();

            $galleries = [];

            while ($row = $stmt->fetch()) {
                $galleries[] = new Gallerie(
                    $row['GalleryID'],
                    $row['GalleryName'],
                    $row['GalleryNativeName'],
                    $row['GalleryCity'],
                    $row['GalleryCountry'],
                    $row['Latitude'],
                    $row['Longitude'],
                    $row['GalleryWebSite']
                );
            }
            return $galleries;

        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching galleries");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves a gallery by a related artwork ID.
     *
     * @param int $artworkId The ID of the artwork
     * @return Gallerie|null A Gallerie object or null if not found
     * @throws Exception If a database error occurs
     */
    public function getGalleryByArtworkId($artworkId) {
        try {
            $this->db->connect();
            $sql = "SELECT galleries.* FROM galleries  
                    INNER JOIN artworks ON galleries.GalleryID = artworks.GalleryID 
                    WHERE artworks.ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId]);
            $row = $stmt->fetch();

            if ($row) {
                return new Gallerie(
                    $row['GalleryID'],
                    $row['GalleryName'],
                    $row['GalleryNativeName'],
                    $row['GalleryCity'],
                    $row['GalleryCountry'],
                    $row['Latitude'],
                    $row['Longitude'],
                    $row['GalleryWebSite']
                );
            }
            return null;

        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching gallery");
        } finally {
            $this->db->close();
        }
    }
}
?>
