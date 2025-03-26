<?php
require_once 'artist.php';
require_once 'database.php';

/**
 * Klasse ArtistRepository
 * 
 * Verwaltet den Zugriff auf die Künstlerdaten in der Datenbank.
 * Enthält Methoden zum Abrufen, Suchen und Filtern von Künstlern.
 */
class ArtistRepository {

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
     * Gibt alle Künstler zurück, sortiert nach Nachname und Vorname.
     * 
     * Eingabe: Optional die Sortierreihenfolge (ASC oder DESC). Standard: ASC.
     * Ausgabe: Ein Array von Artist-Objekten.
     */
    public function getAllArtists($order = 'ASC') {
        $this->db->connect();
        $sql = "SELECT * FROM artists ORDER BY LastName $order, FirstName $order";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $artists = [];
        while ($row = $stmt->fetch()) {
            $artists[] = new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], $row['Details'], $row['ArtistLink']);
        }
        $this->db->close();
        return $artists;
    }

    /**
     * Gibt einen Künstler anhand seiner ID zurück.
     * 
     * Eingabe: Die ID des Künstlers.
     * Ausgabe: Ein Artist-Objekt.
     */
    public function getArtistById($id) {
        $this->db->connect();
        $sql = "SELECT * FROM artists WHERE ArtistID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $this->db->close();
        return new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], $row['Details'], $row['ArtistLink']);
    }

    /**
     * Sucht Künstler anhand eines Suchbegriffs (Name oder Kombination aus Vor- und Nachname).
     * 
     * Eingabe: Der Suchbegriff.
     * Ausgabe: Ein Array von Artist-Objekten.
     */
    public function searchArtists($query) {
        $this->db->connect();
        $sql = "SELECT * FROM artists 
                WHERE LastName LIKE :query 
                OR FirstName LIKE :query 
                OR CONCAT(FirstName, ' ', LastName) LIKE :query
                OR CONCAT(LastName, ' ', FirstName) LIKE :query";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['query' => "%$query%"]);
        $artists = [];
        while ($row = $stmt->fetch()) {
            $artists[] = new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], $row['Details'], $row['ArtistLink']);
        }
        $this->db->close();
        return $artists;
    }

    /**
     * Gibt die Top 3 Künstler basierend auf der Anzahl der Bewertungen zurück.
     * 
     * Eingabe: Optional die Anzahl der zurückzugebenden Künstler (Standard: 3).
     * Ausgabe: Ein Array mit Künstlern und deren Bewertungsanzahl.
     */
    public function getTop3Artists($limit = 3) {
        $this->db->connect();
        $sql = "SELECT artists.*, COUNT(reviews.ReviewID) as ReviewCount 
                FROM artists 
                LEFT JOIN artworks ON artists.ArtistID = artworks.ArtistID 
                LEFT JOIN reviews ON artworks.ArtWorkID = reviews.ArtWorkID 
                GROUP BY artists.ArtistID 
                ORDER BY ReviewCount DESC 
                LIMIT $limit";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $artists = [];
        while ($row = $stmt->fetch()) {
            $artists[] = [
                'artist' => new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], $row['Details'], $row['ArtistLink']),
                'reviewCount' => $row['ReviewCount']
            ];
        }
        $this->db->close();
        return $artists;
    }
}
?>