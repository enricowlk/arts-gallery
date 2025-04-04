<?php
/**
 * Klasse Genre
 * 
 * Repräsentiert ein Genre mit Eigenschaften wie Name, Epoche, Beschreibung und Link.
 * Enthält Getter- und Setter-Methoden für den Zugriff auf die Eigenschaften.
 */
class Genre {
    // Eigenschaften der Klasse
    private $GenreID;      // Eindeutige ID des Genres
    private $GenreName;    // Name des Genres
    private $Era;          // Epoche, zu der das Genre gehört
    private $Description;  // Beschreibung des Genres
    private $Link;         // Link zu weiteren Informationen

    /**
     * Konstruktor
     * 
     * Initialisiert die Eigenschaften des Genres.
     * 
     * Eingabe: GenreID, GenreName, Era, Description und Link.
     */
    public function __construct($GenreID, $GenreName, $Era, $Description, $Link) {
        $this->GenreID = $GenreID;
        $this->GenreName = $GenreName;
        $this->Era = $Era;
        $this->Description = $Description;
        $this->Link = $Link;
    }

    // Getter
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
?>