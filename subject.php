<?php
class Subject {
    private $SubjectID;
    private $SubjectName;

    public function __construct($SubjectID, $SubjectName) {
        $this->SubjectID = $SubjectID; 
        $this->SubjectName = $SubjectName; 
    }

    public function getSubjectID() {
        return $this->SubjectID;
    }

    public function getSubjectName() {
        return $this->SubjectName;
    }
}
?>