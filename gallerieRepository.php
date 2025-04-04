<?php
require_once 'gallerie.php';
require_once 'database.php';
require_once 'logging.php';

class GallerieRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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
            Logging::LogError("Error in getAllGalleries: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching galleries");
        } finally {
            $this->db->close();
        }
    }

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
            Logging::LogError("Error in getGalleryByArtworkId: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching gallery");
        } finally {
            $this->db->close();
        }
    }
}
?>