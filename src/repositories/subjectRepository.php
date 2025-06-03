<?php
// Include required classes
require_once __DIR__ . '/../entitys/subject.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Repository class for accessing subjects from the database.
 */
class SubjectRepository {
    /**
     * @var Database $db Database connection instance
     */
    private $db;

    /**
     * Constructor for SubjectRepository.
     *
     * @param Database $db An instance of the database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves all subjects from the database, ordered by subject name.
     *
     * @return Subject[] Array of Subject objects
     * @throws Exception If a database error occurs
     */
    public function getAllSubjects() {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM subjects ORDER BY SubjectName";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();

            $subjects = [];

            while ($row = $stmt->fetch()) {
                $subjects[] = new Subject($row['SubjectId'], $row['SubjectName']);
            }

            return $subjects;

        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching subjects");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves a single subject by its ID.
     *
     * @param int $id The ID of the subject
     * @return Subject The matching Subject object
     * @throws Exception If the subject is not found or a database error occurs
     */
    public function getSubjectById($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM subjects WHERE SubjectId = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if (!$row) {
                throw new Exception("Subject not found with ID: $id");
            }

            return new Subject($row['SubjectId'], $row['SubjectName']);

        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching subject");
        } finally {
            $this->db->close();
        }
    }
}
?>
