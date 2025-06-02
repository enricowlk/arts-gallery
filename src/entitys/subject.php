<?php
/**
 * Klasse zur Darstellung eines Kunst-Motivs/Themas
 * Repräsentiert die inhaltlichen Themen von Kunstwerken
 */
class Subject {
    // Private Eigenschaften
    private $SubjectID;   
    private $SubjectName;


    // Konstruktor für Subject-Objekte
    public function __construct($SubjectID, $SubjectName) {
        $this->SubjectID = $SubjectID;
        $this->SubjectName = $SubjectName;
    }

    // --- Getter-Methoden ---
    public function getSubjectID() {
        return $this->SubjectID;
    }

    public function getSubjectName() {
        return $this->SubjectName;
    }
}