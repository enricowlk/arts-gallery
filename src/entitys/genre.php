<?php
/**
 * Klasse zur Darstellung eines Kunstgenres/Stils
 * Enthält Informationen über kunsthistorische Stilepochen
 */
class Genre {
    // Private Eigenschaften
    private $GenreID;       
    private $GenreName;    
    private $Era;           
    private $Description;   
    private $Link;         

    // Konstruktor für Genre-Objekte
    public function __construct($GenreID, $GenreName, $Era, $Description, $Link) {
        $this->GenreID = $GenreID;
        $this->GenreName = $GenreName;
        $this->Era = $Era;
        $this->Description = $Description;
        $this->Link = $Link;
    }

    // --- Getter-Methoden ---
    public function getGenreID() {
        return $this->GenreID;
    }
    
    public function getGenreName() {
        return $this->GenreName;
    }

    public function getEra() {
        return $this->Era;
    }
    
    public function getDescription() {
        return $this->Description;
    }

    public function getLink() {
        return $this->Link;    
    }
}