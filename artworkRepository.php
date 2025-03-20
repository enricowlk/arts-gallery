<?php
require_once 'Artwork.php';
require_once 'reviews.php';
require_once 'database.php';

class ArtworkRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllArtworks($orderBy = 'Title', $order = 'ASC') {

        $this->db->connect();

        $sql = "SELECT * FROM artworks ORDER BY $orderBy $order";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        }

        $this->db->close();

        return $artworks;
    }

    public function getArtworkById($id) {

        $this->db->connect();

        $sql = "SELECT * FROM artworks WHERE ArtWorkID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        $this->db->close();

        return new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
    }

    public function getAllArtworksForOneArtistByArtistId($id) {

        $this->db->connect();

        $sql = "SELECT * FROM artworks WHERE ArtistID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);

        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        }

        $this->db->close();

        return $artworks;
    }

    public function getAllArtworksForOneGenreByGenreId($genreId) {

        $this->db->connect();

        $sql = "SELECT * FROM artworks 
                INNER JOIN (genres INNER JOIN artworkgenres ON genres.GenreID = artworkgenres.GenreID) ON artworks.ArtWorkID = artworkgenres.ArtWorkID"
                . " WHERE genres.GenreID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$genreId]);

        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        }

        $this->db->close();

        return $artworks;
    }

    public function getAllArtworksForOneSubjectBySubjectId($id) {

        $this->db->connect();

        $sql = "SELECT * FROM artworks 
                INNER JOIN (subjects INNER JOIN artworksubjects ON subjects.SubjectId = artworksubjects.SubjectID) ON artworks.ArtWorkID = artworksubjects.ArtWorkID"
                . " WHERE subjects.SubjectId = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);

        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        }

        $this->db->close();

        return $artworks;
    }

    public function searchArtworks($query) {

        $this->db->connect();

        $sql = "SELECT * FROM artworks WHERE Title LIKE :query";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['query' => "%$query%"]);

        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        }

        $this->db->close();

        return $artworks;
    }

    public function get3RandomArtworks($limit = 3) {

        $this->db->connect();

        $sql = "SELECT * FROM artworks ORDER BY RAND() LIMIT $limit";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();

        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        }

        $this->db->close();

        return $artworks;
    }

    public function get3TopArtworks($limit = 3) {

        $this->db->connect();

        $sql = "SELECT artworks.*, AVG(reviews.Rating) as AverageRating 
                FROM artworks 
                LEFT JOIN reviews ON artworks.ArtWorkID = reviews.ArtWorkID 
                GROUP BY artworks.ArtWorkID 
                HAVING COUNT(reviews.ReviewID) >= 3 
                ORDER BY AverageRating DESC 
                LIMIT $limit";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();

        $artworks = [];
        while ($row = $stmt->fetch()) {
            $artworks[] = [
                'artwork' => new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']),
                'averageRating' => $row['AverageRating']
            ];
        }

        $this->db->close();

        return $artworks;
    }

    public function getAverageRatingForArtwork($artworkId) {

        $this->db->connect();

        $sql = "SELECT AVG(Rating) as AverageRating FROM reviews WHERE ArtWorkID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$artworkId]);
        $row = $stmt->fetch();

        $this->db->close();

        if (isset($row['AverageRating']) && $row['AverageRating'] !== null) {
            return $row['AverageRating'];
        } else {
            return 0;
        }
    }
}
?>