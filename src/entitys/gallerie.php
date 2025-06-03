<?php
/**
 * Represents a gallery/museum entity with location and identification data.
 */
class Gallerie {
    private $GalleryID;         
    private $GalleryName;       
    private $GalleryNativeName; 
    private $GalleryCity;       
    private $GalleryCountry;  
    private $Latitude;          
    private $Longitude;         
    private $GalleryWebSite;    

    /**
     * Constructs a new Gallery instance.
     *
     * @param int $GalleryID Unique identifier for the gallery.
     * @param string $GalleryName Name of the gallery in English.
     * @param string $GalleryNativeName Native name of the gallery.
     * @param string $GalleryCity City where the gallery is located.
     * @param string $GalleryCountry Country where the gallery is located.
     * @param float $Latitude Geographical latitude coordinate.
     * @param float $Longitude Geographical longitude coordinate.
     * @param string|null $GalleryWebSite Website URL of the gallery (optional).
     */
    public function __construct(
        $GalleryID, 
        $GalleryName, 
        $GalleryNativeName, 
        $GalleryCity, 
        $GalleryCountry, 
        $Latitude, 
        $Longitude, 
        $GalleryWebSite
    ) {
        $this->GalleryID = $GalleryID;
        $this->GalleryName = $GalleryName;
        $this->GalleryNativeName = $GalleryNativeName;
        $this->GalleryCity = $GalleryCity;
        $this->GalleryCountry = $GalleryCountry;
        $this->Latitude = $Latitude;
        $this->Longitude = $Longitude;
        $this->GalleryWebSite = $GalleryWebSite;
    }

    // --- Getter Methods ---

    /**
     * Gets the gallery's unique identifier.
     *
     * @return int The gallery ID.
     */
    public function getGalleryID() {
        return $this->GalleryID;
    }
    
    /**
     * Gets the gallery's English name.
     *
     * @return string The gallery name.
     */
    public function getGalleryName() {
        return $this->GalleryName;
    }
   
    /**
     * Gets the gallery's native name.
     *
     * @return string The native name of the gallery.
     */
    public function getGalleryNativeName() {
        return $this->GalleryNativeName;
    }
   
    /**
     * Gets the city where the gallery is located.
     *
     * @return string The gallery city.
     */
    public function getGalleryCity() {
        return $this->GalleryCity;
    }
    
    /**
     * Gets the country where the gallery is located.
     *
     * @return string The gallery country.
     */
    public function getGalleryCountry() {    
        return $this->GalleryCountry;
    }
    
    /**
     * Gets the geographical latitude coordinate of the gallery.
     *
     * @return float The latitude value.
     */
    public function getLatitude() {    
        return $this->Latitude;
    }    

    /**
     * Gets the geographical longitude coordinate of the gallery.
     *
     * @return float The longitude value.
     */
    public function getLongitude() {    
        return $this->Longitude;
    }
    
    /**
     * Gets the gallery's website URL.
     *
     * @return string|null The website URL or null if not available.
     */
    public function getGalleryWebSite() {    
        return $this->GalleryWebSite;
    }
}