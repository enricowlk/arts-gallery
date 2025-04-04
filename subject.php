<?php
// Definition der Klasse "Subject"
class Subject {
    // Private Eigenschaften für die Subject-ID und den Subject-Namen
    private $SubjectID;
    private $SubjectName;

    // Konstruktor, der beim Erstellen eines neuen Subject-Objekts aufgerufen wird
    public function __construct($SubjectID, $SubjectName) {
        $this->SubjectID = $SubjectID; // Setzt die Subject-ID
        $this->SubjectName = $SubjectName; // Setzt den Subject-Namen
    }

    // Getter
    public function getSubjectID() {
        return $this->SubjectID;
    }

    public function getSubjectName() {
        return $this->SubjectName;
    }
}
?>