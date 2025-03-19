<?php
require_once 'Artist.php';
require_once 'database.php';

class ArtistRepository {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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

    public function getArtistById($id) {

        $this->db->connect();

        $sql = "SELECT * FROM artists WHERE ArtistID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        $this->db->close();

        return new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], $row['Details'], $row['ArtistLink']);
    }

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

    public function getTop3Artists($limit = 3) {

        $this->db->connect();

        $sql = "SELECT artists.*, COUNT(reviews.ReviewID) as ReviewCount 
                FROM artists 
                LEFT JOIN artworks ON artists.ArtistID = artworks.ArtistID 
                LEFT JOIN reviews ON artworks.ArtWorkID = reviews.ArtWorkID 
                GROUP BY artists.ArtistID 
                ORDER BY ReviewCount DESC 
                LIMIT $limit"; // Parameter direkt in die Abfrage einfügen
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