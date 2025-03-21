<?php
 // Repräsentiert einen Künstler mit grundlegenden Eigenschaften und Methoden zur Datenverwaltung.
 
class Artist {
    private $ArtistID;        // Eindeutige ID
    private $FirstName;       // Vorname
    private $LastName;        // Nachname
    private $Nationality;     // Nationalität
    private $YearOfBirth;     // Geburtsjahr
    private $YearOfDeath;     // Todesjahr
    private $Details;         // Weitere Details
    private $ArtistLink;      // Link zu weiteren Informationen



     // Konstruktor zur Initialisierung der Künstlerdaten.
    public function __construct($ArtistID, $FirstName, $LastName, $Nationality, $YearOfBirth, $YearOfDeath, $Details, $ArtistLink) {
        $this->ArtistID = $ArtistID;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->Nationality = $Nationality;
        $this->YearOfBirth = $YearOfBirth;
        $this->YearOfDeath = $YearOfDeath;
        $this->Details = $Details;
        $this->ArtistLink = $ArtistLink;
    }


    // Getter und Setter für ArtistID
    public function getArtistID() {
        return $this->ArtistID;
    }

    public function setArtistID($ArtistID) {
        $this->ArtistID = $ArtistID;
    }

    // Getter und Setter für FirstName
    public function getFirstName() {
        return $this->FirstName;
    }

    public function setFirstName($FirstName) {
        $this->FirstName = $FirstName;
    }

    // Getter und Setter für LastName
    public function getLastName() {
        return $this->LastName;
    }

    public function setLastName($LastName) {
        $this->LastName = $LastName;
    }

    // Getter und Setter für Nationality
    public function getNationality() {
        return $this->Nationality;
    }

    public function setNationality($Nationality) {
        $this->Nationality = $Nationality;
    }

    // Getter und Setter für YearOfBirth
    public function getYearOfBirth() {
        return $this->YearOfBirth;
    }

    public function setYearOfBirth($YearOfBirth) {
        $this->YearOfBirth = $YearOfBirth;
    }

    // Getter und Setter für YearOfDeath
    public function getYearOfDeath() {
        return $this->YearOfDeath;
    }

    public function setYearOfDeath($YearOfDeath) {
        $this->YearOfDeath = $YearOfDeath;
    }

    // Getter und Setter für Details
    public function getDetails() {
        return $this->Details;
    }

    public function setDetails($Details) {
        $this->Details = $Details;
    }

    // Getter und Setter für ArtistLink
    public function getArtistLink() {
        return $this->ArtistLink;
    }

    public function setArtistLink($ArtistLink) {
        $this->ArtistLink = $ArtistLink;
    }
}
?>