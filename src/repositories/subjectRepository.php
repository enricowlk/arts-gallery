<?php
// Einbindung der benötigten Klassen
require_once __DIR__ . '/../entitys/subject.php';    
require_once __DIR__ . '/../../config/database.php';

// Repository-Klasse für Datenbankoperationen mit Subjects
class SubjectRepository {
    private $db; 

    // Konstruktor mit Dependency Injection der Datenbankverbindung
    public function __construct($db) {
        $this->db = $db;
    }

    // Holt alle Subjects aus der Datenbank, sortiert nach Namen
    public function getAllSubjects() {
        try {
            $this->db->connect();  
            $sql = "SELECT * FROM subjects ORDER BY SubjectName";  // SQL mit Sortierung
            $stmt = $this->db->prepareStatement($sql);  
            $stmt->execute();  
            
            $subjects = [];  // Initialisiert leeres Array für Subjects
            
            // Verarbeitet jede Ergebniszeile der Abfrage
            while ($row = $stmt->fetch()) {
                // Erstellt Subject-Objekte und fügt sie dem Array hinzu
                $subjects[] = new Subject($row['SubjectId'], $row['SubjectName']);
            }
            
            return $subjects;  
            
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching subjects");
        } finally {
            $this->db->close();  
        }
    }

    // Holt ein bestimmtes Subject anhand seiner ID
    public function getSubjectById($id) {
        try {
            $this->db->connect();  
            $sql = "SELECT * FROM subjects WHERE SubjectId = ?";  
            $stmt = $this->db->prepareStatement($sql);  
            $stmt->execute([$id]);  
            $row = $stmt->fetch();  
            
            // Wenn kein Subject gefunden wurde
            if (!$row) {
                throw new Exception("Subject not found with ID: $id");
            }
            
            // Gibt das gefundene Subject als Objekt zurück
            return new Subject($row['SubjectId'], $row['SubjectName']);
            
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching subject");
        } finally {
            $this->db->close(); 
        }
    }
}
?>