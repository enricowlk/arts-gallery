<?php
/**
 * Klasse Gallerie
 * 
 * Repräsentiert eine Gallerie mit Eigenschaften wie ID, Name, Native Name, Stadt, Land, Latitude, Longitude und Webseite.
 * Enthält Getter- und Setter-Methoden für den Zugriff auf die Eigenschaften.
 */
class Gallerie {
    // Eigenschaften der Klasse
    private $GalleryID;   
    private $GalleryName;    
    private $GalleryNativeName;          
    private $GalleryCity; 
    private $GalleryCountry;         
    private $Latitude;        
    private $Longitude;    
    private $GalleryWebSite;     

    /**
     * Konstruktor
     * 
     * Initialisiert die Eigenschaften der Gallerie.
     * 
     * Eingabe: GalleryID, GalleryName, GalleryNativeName, GalleryCity, GalleryCountry, Latitude, Longitude und GalleryWebSite.
     */
    public function __construct($GalleryID, $GalleryName, $GalleryNativeName, $GalleryCity, $GalleryCountry, $Latitude, $Longitude, $GalleryWebSite) {
        $this->GalleryID = $GalleryID;
        $this->GalleryName = $GalleryName;
        $this->GalleryNativeName = $GalleryNativeName;
        $this->GalleryCity = $GalleryCity;
        $this->GalleryCountry = $GalleryCountry;
        $this->Latitude = $Latitude;
        $this->Longitude = $Longitude;
        $this->GalleryWebSite = $GalleryWebSite;
    }

    // Getter
    public function getGalleryID() {
        return $this->GalleryID;
    }
    public function getGalleryName() {
        return $this->GalleryName;
    }
   
    public function getGalleryNativeName() {
        return $this->GalleryNativeName;
    }
   
    public function getGalleryCity() {
        return $this->GalleryCity;
    }
    
    public function getGalleryCountry() {    
        return $this->GalleryCountry;
    }
    
    public function getLatitude() {    
        return $this->Latitude;
    }    

    public function getLongitude() {    
        return $this->Longitude;
    }
    
    public function getGalleryWebSite() {    
        return $this->GalleryWebSite;
    }
}