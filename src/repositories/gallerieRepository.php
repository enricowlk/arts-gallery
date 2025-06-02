<?php
// Einbinden der benötigten Klassen
require_once __DIR__ . '/../entitys/gallerie.php';  
require_once __DIR__ . '/../../config/database.php'; 

// Repository-Klasse für Gallerie-Operationen
class GallerieRepository {
    private $db;  

    // Konstruktor mit Dependency Injection der Datenbank
    public function __construct($db) {
        $this->db = $db;
    }

    // Methode zum Abrufen aller Galerien aus der Datenbank
    public function getAllGalleries() {
        try {
            $this->db->connect();  // Verbindung zur Datenbank herstellen
            $sql = "SELECT * FROM galleries";  // SQL-Abfrage
            $stmt = $this->db->prepareStatement($sql);  // Statement vorbereiten
            $stmt->execute();  // Statement ausführen
            
            $galleries = [];  // Array für die Ergebnisse
            
            // Ergebnisse zeilenweise verarbeiten
            while ($row = $stmt->fetch()) {
                // Gallerie-Objekte erstellen und zum Array hinzufügen
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

    // Methode zum Abrufen einer Galerie anhand einer Kunstwerk-ID
    public function getGalleryByArtworkId($artworkId) {
        try {
            $this->db->connect();  
            // SQL mit JOIN zwischen galleries und artworks
            $sql = "SELECT galleries.* FROM galleries  
                    INNER JOIN artworks ON galleries.GalleryID = artworks.GalleryID 
                    WHERE artworks.ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);  
            $stmt->execute([$artworkId]);  
            $row = $stmt->fetch();  
            
            if ($row) {
                // Gallerie-Objekt erstellen und zurückgeben
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
            return null;  // Falls keine Galerie gefunden wurde
            
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching gallery");
        } finally {
            $this->db->close(); 
        }
    }
}
?>