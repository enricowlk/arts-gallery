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


    // Getter 
    public function getArtistID() {
        return $this->ArtistID;
    }

    public function getFirstName() {
        return $this->FirstName;
    }

    public function getLastName() {
        return $this->LastName;
    }

    public function getNationality() {
        return $this->Nationality;
    }

    public function getYearOfBirth() {
        return $this->YearOfBirth;
    }

    public function getYearOfDeath() {
        return $this->YearOfDeath;
    }

    public function getDetails() {
        return $this->Details;
    }

    public function getArtistLink() {
        return $this->ArtistLink;
    }
}
?>