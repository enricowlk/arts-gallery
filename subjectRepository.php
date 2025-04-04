<?php
require_once 'subject.php';
require_once 'database.php';
require_once 'logging.php';

class SubjectRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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
            Logging::LogError("Error in getAllSubjects: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching subjects");
        } finally {
            $this->db->close();
        }
    }

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
            Logging::LogError("Error in getSubjectById: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching subject");
        } finally {
            $this->db->close();
        }
    }
}
?>