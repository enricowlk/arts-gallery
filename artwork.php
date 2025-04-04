<?php
/**
 * Repräsentiert ein Kunstwerk mit Eigenschaften wie Titel, Jahr, Bilddatei usw.
 * Enthält Getter und Setter für den Zugriff auf die Eigenschaften.
 */
class Artwork {
    private $ArtWorkID;       // Eindeutige ID des Kunstwerks
    private $Title;           // Titel des Kunstwerks
    private $YearOfWork;      // Jahr der Entstehung
    private $ImageFileName;   // Dateiname des Bildes
    private $ArtistID;        // ID des zugehörigen Künstlers
    private $Description;     // Beschreibung des Kunstwerks
    private $Excerpt;         // Kurzbeschreibung
    private $Medium;          // Medium (z. B. Öl auf Leinwand)
    private $OriginalHome;    // Ursprünglicher Aufbewahrungsort
    private $ArtWorkLink;     // Link zum Kunstwerk
    private $GoogleLink;      // Google-Link (z. B. Maps)

    /**
     * Konstruktor zur Initialisierung der Kunstwerkdaten.
     */
    public function __construct($ArtWorkID, $Title, $YearOfWork, $ImageFileName, $ArtistID, $Description, $Excerpt, $Medium, $OriginalHome, $ArtWorkLink, $GoogleLink) {
        $this->ArtWorkID = $ArtWorkID;
        $this->Title = $Title;
        $this->YearOfWork = $YearOfWork;
        $this->ImageFileName = $ImageFileName;
        $this->ArtistID = $ArtistID;
        $this->Description = $Description;
        $this->Excerpt = $Excerpt;
        $this->Medium = $Medium;
        $this->OriginalHome = $OriginalHome;
        $this->ArtWorkLink = $ArtWorkLink;
        $this->GoogleLink = $GoogleLink;
    }

    // Getter
    public function getArtWorkID() {
        return $this->ArtWorkID;
    }

    public function getTitle() {
        return $this->Title;
    }

    public function getYearOfWork() {
        return $this->YearOfWork;
    }

    public function getImageFileName() {
        return $this->ImageFileName;
    }

    public function getArtistID() {
        return $this->ArtistID;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function getExcerpt() {
        return $this->Excerpt;
    }

    public function getMedium() {
        return $this->Medium;
    }

    public function getOriginalHome() {
        return $this->OriginalHome;
    }

    public function getArtWorkLink() {
        return $this->ArtWorkLink;
    }

    public function getGoogleLink() {
        return $this->GoogleLink;
    }
}
?>