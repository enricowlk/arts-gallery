<?php
/**
 * Class representing an art genre/style.
 * Contains information about art historical styles and epochs.
 */
class Genre {
    private $GenreID;       
    private $GenreName;    
    private $Era;           
    private $Description;   
    private $Link;         

    /**
     * Genre constructor.
     *
     * @param int $GenreID Unique identifier for the genre
     * @param string $GenreName Name of the genre
     * @param string $Era Era or period of the genre
     * @param string $Description Description of the genre
     * @param string $Link Link to more information about the genre
     */
    public function __construct($GenreID, $GenreName, $Era, $Description, $Link) {
        $this->GenreID = $GenreID;
        $this->GenreName = $GenreName;
        $this->Era = $Era;
        $this->Description = $Description;
        $this->Link = $Link;
    }

    /**
     * Get the genre ID.
     *
     * @return int Genre unique identifier
     */
    public function getGenreID() {
        return $this->GenreID;
    }
    
    /**
     * Get the genre name.
     *
     * @return string Name of the genre
     */
    public function getGenreName() {
        return $this->GenreName;
    }

    /**
     * Get the era or period of the genre.
     *
     * @return string Era or period
     */
    public function getEra() {
        return $this->Era;
    }
    
    /**
     * Get the description of the genre.
     *
     * @return string Description text
     */
    public function getDescription() {
        return $this->Description;
    }

    /**
     * Get the link to more information about the genre.
     *
     * @return string URL link
     */
    public function getLink() {
        return $this->Link;    
    }
}
