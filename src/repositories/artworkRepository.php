<?php
require_once __DIR__ . '/../entitys/artwork.php';
require_once __DIR__ . '/../entitys/reviews.php';
require_once __DIR__ . '/../../config/database.php';

class ArtworkRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllArtworks($orderBy = 'Title', $order = 'ASC') {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks ORDER BY $orderBy $order";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artworks");
        } finally {
            $this->db->close();
        }
    }

    public function getArtworkById($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            
            if (!$row) {
                throw new Exception("Artwork not found with ID: $id");
            }
            
            return new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork");
        } finally {
            $this->db->close();
        }
    }

    public function getAllArtworksForOneArtistByArtistId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks WHERE ArtistID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artist's artworks");
        } finally {
            $this->db->close();
        }
    }

    public function getAllArtworksForOneGenreByGenreId($genreId) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks 
                    INNER JOIN (genres INNER JOIN artworkgenres ON genres.GenreID = artworkgenres.GenreID) ON artworks.ArtWorkID = artworkgenres.ArtWorkID
                    WHERE genres.GenreID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$genreId]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching genre artworks");
        } finally {
            $this->db->close();
        }
    }

    public function getGenreForOneArtworkByArtworkId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM genres INNER JOIN artworkgenres ON genres.GenreID = artworkgenres.GenreID WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork genres");
        } finally {
            $this->db->close();
        }
    }

    public function getAllArtworksForOneSubjectBySubjectId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks 
                    INNER JOIN (subjects INNER JOIN artworksubjects ON subjects.SubjectId = artworksubjects.SubjectID) ON artworks.ArtWorkID = artworksubjects.ArtWorkID
                    WHERE subjects.SubjectId = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching subject artworks");
        } finally {
            $this->db->close();
        }
    }

    public function getSubjectForOneArtworkByArtworkId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM subjects INNER JOIN artworksubjects ON subjects.SubjectId = artworksubjects.SubjectID WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Subject($row['SubjectId'], $row['SubjectName']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork subjects");
        } finally {
            $this->db->close();
        }
    }

    public function searchArtworks($query, $orderBy = 'Title', $order = 'ASC') {
        try {
            $this->db->connect();
            
            // Sicherstellen, dass nur gültige Spalten für ORDER BY verwendet werden
            $validOrderColumns = ['Title', 'ArtistID', 'YearOfWork'];
            if (!in_array($orderBy, $validOrderColumns)) {
                $orderBy = 'Title'; // Fallback auf Standardwert
            }
            
            $sql = "SELECT artworks.* FROM artworks
                    LEFT JOIN artists ON artworks.ArtistID = artists.ArtistID
                    WHERE artworks.Title LIKE :query
                    OR artists.LastName LIKE :query
                    OR artists.FirstName LIKE :query
                    OR CONCAT(artists.FirstName, ' ', artists.LastName) LIKE :query
                    OR CONCAT(artists.LastName, ' ', artists.FirstName) LIKE :query";
            
            // Spezielle Behandlung für ArtistID-Sortierung
            if ($orderBy === 'ArtistID') {
                $sql .= " ORDER BY artists.LastName $order, artists.FirstName $order";
            } else {
                $sql .= " ORDER BY artworks.$orderBy $order";
            }
            
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['query' => "%$query%"]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while searching artworks");
        } finally {
            $this->db->close();
        }
    }

    public function get3RandomArtworks($limit = 3) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks ORDER BY RAND() LIMIT $limit";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], $row['ImageFileName'], $row['ArtistID'], $row['Description'], $row['Excerpt'], $row['Medium'], $row['OriginalHome'], $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching random artworks");
        } finally {
            $this->db->close();
        }
    }

    public function get3TopArtworks($limit = 3) {
        try {
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
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching top artworks");
        } finally {
            $this->db->close();
        }
    }

    public function getAverageRatingForArtwork($artworkId) {
        try {
            $this->db->connect();
            $sql = "SELECT AVG(Rating) as AverageRating FROM reviews WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId]);
            $row = $stmt->fetch();
            
            if (isset($row['AverageRating']) && $row['AverageRating'] !== null) {
                return $row['AverageRating'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork rating");
        } finally {
            $this->db->close();
        }
    }
}
?>