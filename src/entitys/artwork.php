<?php
// Definition der Artwork-Klasse zur Darstellung eines Kunstwerks
class Artwork {
    // Private Eigenschaften - nur innerhalb der Klasse zugänglich
    private $ArtWorkID;       
    private $Title;          
    private $YearOfWork;      
    private $ImageFileName;   
    private $ArtistID;       
    private $Description;    
    private $Excerpt;         
    private $Medium;         
    private $OriginalHome;   
    private $ArtWorkLink;     
    private $GoogleLink;      

    // Konstruktor zur Initialisierung eines neuen Artwork-Objekts
    public function __construct(
        $ArtWorkID,
        $Title,
        $YearOfWork,
        $ImageFileName,
        $ArtistID,
        $Description,
        $Excerpt,
        $Medium,
        $OriginalHome,
        $ArtWorkLink,
        $GoogleLink
    ) {
        // Zuweisung der Parameter zu den Klassen-Eigenschaften
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

    // --- Getter-Methoden ---
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