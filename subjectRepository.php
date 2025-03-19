<?php
require_once 'Subject.php';
require_once 'database.php';

class SubjectRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Holt alle Subjects aus der Datenbank, sortiert nach SubjectName.
     */
    public function getAllSubjects() {
        $this->db->connect();
        $sql = "SELECT * FROM subjects ORDER BY SubjectName";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $subjects = [];
        while ($row = $stmt->fetch()) {
            $subjects[] = new Subject($row['SubjectId'], $row['SubjectName']);
        }
        $this->db->close();
        return $subjects;
    }

    /**
     * Holt ein einzelnes Subject anhand der SubjectID.
     */
    public function getSubjectById($id) {
        $this->db->connect();
        $sql = "SELECT * FROM subjects WHERE SubjectId = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $this->db->close();
        return new Subject($row['SubjectId'], $row['SubjectName']);
    }
}
?>