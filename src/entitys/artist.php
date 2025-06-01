<?php 
class Artist {
    private $ArtistID;        
    private $FirstName;       
    private $LastName;        
    private $Nationality;     
    private $YearOfBirth;     
    private $YearOfDeath;     
    private $Details;         
    private $ArtistLink;      


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