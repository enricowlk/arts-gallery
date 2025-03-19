<?php
class Genre {
    private $GenreID;
    private $GenreName;
    private $Era;
    private $Description;
    private $Link;

    public function __construct($GenreID, $GenreName, $Era, $Description, $Link) {
        $this->GenreID = $GenreID;
        $this->GenreName = $GenreName;
        $this->Era = $Era;
        $this->Description = $Description;
        $this->Link = $Link;
    }

    // Getter und Setter für GenreID
    public function getGenreID() {
        return $this->GenreID;
    }

    public function setGenreID($GenreID) {
        $this->GenreID = $GenreID;
    }

    // Getter und Setter für GenreName
    public function getGenreName() {
        return $this->GenreName;
    }

    public function setGenreName($GenreName) {
        $this->GenreName = $GenreName;
    }

    // Getter und Setter für Era
    public function getEra() {
        return $this->Era;
    }

    public function setEra($Era) {
        $this->Era = $Era;
    }

    // Getter und Setter fuer Description    
    public function getDescription() {
        return $this->Description;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
    }

    // Getter und Setter fuer Link
    public function getLink() {
        return $this->Link;    
    }

    public function setLink($Link) {    
        $this->Link = $Link;
    }
}
?>