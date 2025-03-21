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
    // Getter und Setter für ArtWorkID
    public function getArtWorkID() {
        return $this->ArtWorkID;
    }

    public function setArtWorkID($ArtWorkID) {
        $this->ArtWorkID = $ArtWorkID;
    }

    // Getter und Setter für Title
    public function getTitle() {
        return $this->Title;
    }

    public function setTitle($Title) {
        $this->Title = $Title;
    }

    // Getter und Setter für YearOfWork
    public function getYearOfWork() {
        return $this->YearOfWork;
    }

    public function setYearOfWork($YearOfWork) {
        $this->YearOfWork = $YearOfWork;
    }

    // Getter und Setter für ImageFileName
    public function getImageFileName() {
        return $this->ImageFileName;
    }

    public function setImageFileName($ImageFileName) {
        $this->ImageFileName = $ImageFileName;
    }

    // Getter und Setter für ArtistID
    public function getArtistID() {
        return $this->ArtistID;
    }

    public function setArtistID($ArtistID) {
        $this->ArtistID = $ArtistID;
    }

    // Getter und Setter für Description
    public function getDescription() {
        return $this->Description;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
    }

    // Getter und Setter für Excerpt
    public function getExcerpt() {
        return $this->Excerpt;
    }

    public function setExcerpt($Excerpt) {
        $this->Excerpt = $Excerpt;
    }

    // Getter und Setter für Medium
    public function getMedium() {
        return $this->Medium;
    }

    public function setMedium($Medium) {
        $this->Medium = $Medium;
    }

    // Getter und Setter für OriginalHome
    public function getOriginalHome() {
        return $this->OriginalHome;
    }

    public function setOriginalHome($OriginalHome) {
        $this->OriginalHome = $OriginalHome;
    }

    // Getter und Setter für ArtWorkLink
    public function getArtWorkLink() {
        return $this->ArtWorkLink;
    }

    public function setArtWorkLink($ArtWorkLink) {
        $this->ArtWorkLink = $ArtWorkLink;
    }

    // Getter und Setter für GoogleLink
    public function getGoogleLink() {
        return $this->GoogleLink;
    }

    public function setGoogleLink($GoogleLink) {
        $this->GoogleLink = $GoogleLink;
    }
}
?>