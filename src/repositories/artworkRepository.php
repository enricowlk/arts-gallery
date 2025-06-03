<?php
/**
 * Handles all database operations related to artworks including retrieval, searching, and rating calculations.
 */
class ArtworkRepository {
    /**
     * @var Database The database connection instance.
     */
    private $db; 

    /**
     * Constructor with dependency injection of the database.
     *
     * @param Database $db The database connection instance.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves all artworks with optional sorting.
     *
     * @param string $orderBy The column to sort by (default: 'Title').
     * @param string $order Sorting direction ('ASC' or 'DESC', default: 'ASC').
     * @return Artwork[] An array of Artwork objects.
     * @throws Exception If a database error occurs.
     */
    public function getAllArtworks($orderBy = 'Title', $order = 'ASC') {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks ORDER BY $orderBy $order";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                        $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                        $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                        $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artworks");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves a single artwork by its ID.
     *
     * @param int $id The ID of the artwork to retrieve.
     * @return Artwork The requested Artwork object.
     * @throws Exception If the artwork is not found or a database error occurs.
     */
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
            
            return new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                             $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                             $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                             $row['ArtWorkLink'], $row['GoogleLink']);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves all artworks for a specific artist.
     *
     * @param int $id The ID of the artist.
     * @return Artwork[] An array of Artwork objects by the specified artist.
     * @throws Exception If a database error occurs.
     */
    public function getAllArtworksForOneArtistByArtistId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks WHERE ArtistID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                        $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                        $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                        $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artist's artworks");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves all artworks for a specific genre.
     *
     * @param int $genreId The ID of the genre.
     * @return Artwork[] An array of Artwork objects in the specified genre.
     * @throws Exception If a database error occurs.
     */
    public function getAllArtworksForOneGenreByGenreId($genreId) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks 
                    INNER JOIN (genres INNER JOIN artworkgenres ON genres.GenreID = artworkgenres.GenreID) 
                    ON artworks.ArtWorkID = artworkgenres.ArtWorkID
                    WHERE genres.GenreID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$genreId]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                        $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                        $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                        $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching genre artworks");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves the genre(s) for a specific artwork.
     *
     * @param int $id The ID of the artwork.
     * @return Genre[] An array of Genre objects associated with the artwork.
     * @throws Exception If a database error occurs.
     */
    public function getGenreForOneArtworkByArtworkId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM genres 
                    INNER JOIN artworkgenres ON genres.GenreID = artworkgenres.GenreID 
                    WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $genres = [];
            while ($row = $stmt->fetch()) {
                $genres[] = new Genre($row['GenreID'], $row['GenreName'], 
                                    $row['Era'], $row['Description'], $row['Link']);
            }
            return $genres;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork genres");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves all artworks for a specific subject.
     *
     * @param int $id The ID of the subject.
     * @return Artwork[] An array of Artwork objects with the specified subject.
     * @throws Exception If a database error occurs.
     */
    public function getAllArtworksForOneSubjectBySubjectId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks 
                    INNER JOIN (subjects INNER JOIN artworksubjects ON subjects.SubjectId = artworksubjects.SubjectID) 
                    ON artworks.ArtWorkID = artworksubjects.ArtWorkID
                    WHERE subjects.SubjectId = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                        $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                        $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                        $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching subject artworks");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves the subject(s) for a specific artwork.
     *
     * @param int $id The ID of the artwork.
     * @return Subject[] An array of Subject objects associated with the artwork.
     * @throws Exception If a database error occurs.
     */
    public function getSubjectForOneArtworkByArtworkId($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM subjects 
                    INNER JOIN artworksubjects ON subjects.SubjectId = artworksubjects.SubjectID 
                    WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $subjects = [];
            while ($row = $stmt->fetch()) {
                $subjects[] = new Subject($row['SubjectId'], $row['SubjectName']);
            }
            return $subjects;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork subjects");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Searches for artworks by title or artist name with optional sorting.
     *
     * @param string $query The search term.
     * @param string $orderBy The column to sort by (default: 'Title').
     * @param string $order Sorting direction ('ASC' or 'DESC', default: 'ASC').
     * @return Artwork[] An array of matching Artwork objects.
     * @throws Exception If a database error occurs.
     */
    public function searchArtworks($query, $orderBy = 'Title', $order = 'ASC') {
        try {
            $this->db->connect();
            
            $validOrderColumns = ['Title', 'ArtistID', 'YearOfWork'];
            if (!in_array($orderBy, $validOrderColumns)) {
                $orderBy = 'Title';
            }
            
            $sql = "SELECT artworks.* FROM artworks
                    LEFT JOIN artists ON artworks.ArtistID = artists.ArtistID
                    WHERE artworks.Title LIKE :query
                    OR artists.LastName LIKE :query
                    OR artists.FirstName LIKE :query
                    OR CONCAT(artists.FirstName, ' ', artists.LastName) LIKE :query
                    OR CONCAT(artists.LastName, ' ', artists.FirstName) LIKE :query";
            
            if ($orderBy === 'ArtistID') {
                $sql .= " ORDER BY artists.LastName $order, artists.FirstName $order";
            } else {
                $sql .= " ORDER BY artworks.$orderBy $order";
            }
            
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['query' => "%$query%"]);
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                        $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                        $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                        $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while searching artworks");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves a specified number of random artworks.
     *
     * @param int $limit The number of random artworks to retrieve (default: 3).
     * @return Artwork[] An array of randomly selected Artwork objects.
     * @throws Exception If a database error occurs.
     */
    public function get3RandomArtworks($limit = 3) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artworks ORDER BY RAND() LIMIT $limit";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $artworks = [];
            while ($row = $stmt->fetch()) {
                $artworks[] = new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                        $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                        $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                        $row['ArtWorkLink'], $row['GoogleLink']);
            }
            return $artworks;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching random artworks");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves the top-rated artworks (minimum 3 reviews required).
     *
     * @param int $limit The number of top artworks to retrieve (default: 3).
     * @return array An array of associative arrays containing Artwork objects and their average ratings.
     * @throws Exception If a database error occurs.
     */
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
                    'artwork' => new Artwork($row['ArtWorkID'], $row['Title'], $row['YearOfWork'], 
                                           $row['ImageFileName'], $row['ArtistID'], $row['Description'], 
                                           $row['Excerpt'], $row['Medium'], $row['OriginalHome'], 
                                           $row['ArtWorkLink'], $row['GoogleLink']),
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

    /**
     * Calculates the average rating for a specific artwork.
     *
     * @param int $artworkId The ID of the artwork.
     * @return float The average rating (returns 0 if no reviews exist).
     * @throws Exception If a database error occurs.
     */
    public function getAverageRatingForArtwork($artworkId) {
        try {
            $this->db->connect();
            $sql = "SELECT AVG(Rating) as AverageRating FROM reviews WHERE ArtWorkID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId]);
            $row = $stmt->fetch();
            
            return $row['AverageRating'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork rating");
        } finally {
            $this->db->close();
        }
    }
}