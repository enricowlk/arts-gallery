<?php
require_once 'gallerie.php';
require_once 'database.php';

class GallerieRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllGalleries() {
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
        $this->db->close();
        return $galleries;
    }

    public function getGalleryByArtworkId($artworkId) {
        $this->db->connect();
        $sql = "SELECT galleries.* FROM galleries  
                INNER JOIN artworks ON galleries.GalleryID = artworks.GalleryID 
                WHERE artworks.ArtWorkID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$artworkId]);
        $row = $stmt->fetch();
        $this->db->close();
        
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
    }
}
?>