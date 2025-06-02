<?php 
// Definition der Artist-Klasse zur Darstellung eines Künstlers
class Artist {
    // Private Eigenschaften - nur innerhalb der Klasse zugänglich
    private $ArtistID;       
    private $FirstName;     
    private $LastName;      
    private $Nationality;     
    private $YearOfBirth;     
    private $YearOfDeath;    
    private $Details;       
    private $ArtistLink;      

    // Konstruktor zur Initialisierung eines neuen Artist-Objekts
    public function __construct(
        $ArtistID, 
        $FirstName, 
        $LastName, 
        $Nationality, 
        $YearOfBirth, 
        $YearOfDeath, 
        $Details, 
        $ArtistLink
    ) {
        // Zuweisung der Parameter zu den Klassen-Eigenschaften
        $this->ArtistID = $ArtistID;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->Nationality = $Nationality;
        $this->YearOfBirth = $YearOfBirth;
        $this->YearOfDeath = $YearOfDeath;
        $this->Details = $Details;
        $this->ArtistLink = $ArtistLink;
    }

    // --- Getter-Methoden ---    
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