<?php
class Subject {
    private $SubjectID;
    private $SubjectName;

    public function __construct($SubjectID, $SubjectName) {
        $this->SubjectID = $SubjectID;
        $this->SubjectName = $SubjectName;
    }

    // Getter und Setter für SubjectID
    public function getSubjectID() {
        return $this->SubjectID;
    }

    public function setSubjectID($SubjectID) {
        $this->SubjectID = $SubjectID;
    }

    // Getter und Setter für SubjectName
    public function getSubjectName() {
        return $this->SubjectName;
    }

    public function setSubjectName($SubjectName) {
        $this->SubjectName = $SubjectName;
    }
}
?>