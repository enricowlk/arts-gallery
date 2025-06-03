<?php
/**
 * Class representing an art subject or theme.
 * Represents the thematic content of artworks.
 */
class Subject {
    private $SubjectID;
    private $SubjectName;

    /**
     * Subject constructor.
     *
     * @param int $SubjectID Unique identifier for the subject
     * @param string $SubjectName Name or title of the subject
     */
    public function __construct($SubjectID, $SubjectName) {
        $this->SubjectID = $SubjectID;
        $this->SubjectName = $SubjectName;
    }

    /**
     * Get the subject ID.
     *
     * @return int Subject unique identifier
     */
    public function getSubjectID() {
        return $this->SubjectID;
    }

    /**
     * Get the subject name.
     *
     * @return string Name or title of the subject
     */
    public function getSubjectName() {
        return $this->SubjectName;
    }
}
