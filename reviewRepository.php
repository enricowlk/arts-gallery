<?php
require_once 'reviews.php'; // Bindet die Review-Klasse ein
require_once 'database.php'; // Bindet die Database-Klasse ein

/**
 * Klasse ReviewRepository
 * 
 * Verwaltet den Zugriff auf die Bewertungsdaten in der Datenbank.
 * Enthält Methoden zum Abrufen und Hinzufügen von Bewertungen.
 */
class ReviewRepository {
    private $db; // Datenbankverbindung

    /**
     * Konstruktor
     * 
     * Eingabe: Die Datenbankverbindung.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Holt die neuesten Bewertungen aus der Datenbank.
     * 
     * Eingabe: Optional die maximale Anzahl der Bewertungen (Standard: 3).
     * Ausgabe: Ein Array von Review-Objekten.
     */
    public function get3RecentReviews($limit = 3) {
        $this->db->connect();
        $sql = "SELECT * FROM reviews ORDER BY ReviewDate DESC LIMIT $limit"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute(); // Führt die Abfrage aus
        $reviews = [];
        while ($row = $stmt->fetch()) {
            // Erstellt für jeden Datensatz ein Review-Objekt
            $reviews[] = new Review($row['ReviewId'], $row['ArtWorkId'], $row['CustomerId'], $row['ReviewDate'], $row['Rating'], $row['Comment']);
        }
        $this->db->close(); // Schließt die Datenbankverbindung
        return $reviews; // Gibt das Array von Review-Objekten zurück
    }

    /**
     * Holt die Anzahl der Bewertungen für einen Künstler.
     * 
     * Eingabe: Die ArtistID des Künstlers.
     * Ausgabe: Die Anzahl der Bewertungen als Zahl.
     */
    public function getReviewCountForArtist($artistId) {
        $this->db->connect();
        $sql = "SELECT COUNT(reviews.ReviewID) as ReviewCount 
                FROM reviews 
                INNER JOIN artworks ON reviews.ArtWorkID = artworks.ArtWorkID 
                WHERE artworks.ArtistID = ?"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute([$artistId]); // Führt die Abfrage mit der ArtistID aus
        $row = $stmt->fetch(); // Holt den Datensatz
        $this->db->close(); // Schließt die Datenbankverbindung
        
        if (isset($row['ReviewCount']) && $row['ReviewCount'] !== null) {
            return $row['ReviewCount']; // Gibt die Anzahl der Bewertungen zurück
        } else {
            return 0; // Gibt 0 zurück, falls keine Bewertungen gefunden wurden
        }
    }

    /**
     * Holt alle Bewertungen für ein bestimmtes Kunstwerk.
     * 
     * Eingabe: Die ArtWorkID des Kunstwerks.
     * Ausgabe: Ein Array von Review-Objekten, sortiert nach Datum (neueste zuerst).
     */
    public function getAllReviewsForOneArtworkByArtworkId($artworkId) {
        $this->db->connect();
        $sql = "SELECT * FROM reviews WHERE ArtWorkID = ? ORDER BY ReviewDate DESC"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute([$artworkId]); // Führt die Abfrage mit der ArtWorkID aus
        $reviews = [];
        while ($row = $stmt->fetch()) {
            // Erstellt für jeden Datensatz ein Review-Objekt
            $reviews[] = new Review($row['ReviewId'], $row['ArtWorkId'], $row['CustomerId'], $row['ReviewDate'], $row['Rating'], $row['Comment']);
        }
        $this->db->close(); // Schließt die Datenbankverbindung
        return $reviews; // Gibt das Array von Review-Objekten zurück
    }

    /**
     * Fügt eine neue Bewertung in die Datenbank ein.
     * 
     * Eingabe: ArtWorkID, CustomerID, Rating und Kommentar.
     */
    public function addReview($artworkId, $customerId, $rating, $comment) {
        $this->db->connect();
        $sql = "INSERT INTO reviews (ArtWorkID, CustomerID, Rating, Comment, ReviewDate) VALUES (?, ?, ?, ?, NOW())"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute([$artworkId, $customerId, $rating, $comment]); // Führt die Abfrage aus
        $this->db->close(); // Schließt die Datenbankverbindung
    }

    public function deleteReview($reviewId) {
        $this->db->connect();
        $sql = "DELETE FROM reviews WHERE ReviewID = :reviewId";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['reviewId' => $reviewId]);
        $this->db->close();
        return $stmt;
    }

    public function hasUserReviewedArtwork($artworkId, $customerId) {
        $this->db->connect();
        $sql = "SELECT COUNT(*) as review_count FROM reviews WHERE ArtWorkID = ? AND CustomerID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$artworkId, $customerId]);
        $row = $stmt->fetch();
        $this->db->close();
        
        return ($row['review_count'] > 0);
    }
}
?>