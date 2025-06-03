<?php
/**
 * Class Artwork
 *
 * Represents an artwork entity.
 */
class Artwork {
    /**
     * @var int Artwork ID
     */
    private $ArtWorkID;

    /**
     * @var string Title of the artwork
     */
    private $Title;

    /**
     * @var int|null Year the artwork was created
     */
    private $YearOfWork;

    /**
     * @var string Image file name for the artwork
     */
    private $ImageFileName;

    /**
     * @var int ID of the artist who created the artwork
     */
    private $ArtistID;

    /**
     * @var string|null Full description of the artwork
     */
    private $Description;

    /**
     * @var string|null Short excerpt or summary
     */
    private $Excerpt;

    /**
     * @var string|null Medium used in the artwork
     */
    private $Medium;

    /**
     * @var string|null Original location or museum
     */
    private $OriginalHome;

    /**
     * @var string|null Link to more information about the artwork
     */
    private $ArtWorkLink;

    /**
     * @var string|null Google Maps link to the artwork's location
     */
    private $GoogleLink;

    /**
     * Artwork constructor.
     *
     * @param int $ArtWorkID Artwork ID
     * @param string $Title Title of the artwork
     * @param int|null $YearOfWork Year the artwork was created
     * @param string $ImageFileName Image file name
     * @param int $ArtistID ID of the artist
     * @param string|null $Description Description of the artwork
     * @param string|null $Excerpt Short excerpt
     * @param string|null $Medium Medium used
     * @param string|null $OriginalHome Original location
     * @param string|null $ArtWorkLink Link to more info
     * @param string|null $GoogleLink Google Maps link
     */
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

    /**
     * @return int Artwork ID
     */
    public function getArtWorkID() {
        return $this->ArtWorkID;
    }

    /**
     * @return string Title of the artwork
     */
    public function getTitle() {
        return $this->Title;
    }

    /**
     * @return int|null Year the artwork was created
     */
    public function getYearOfWork() {
        return $this->YearOfWork;
    }

    /**
     * @return string Image file name
     */
    public function getImageFileName() {
        return $this->ImageFileName;
    }

    /**
     * @return int Artist ID
     */
    public function getArtistID() {
        return $this->ArtistID;
    }

    /**
     * @return string|null Description of the artwork
     */
    public function getDescription() {
        return $this->Description;
    }

    /**
     * @return string|null Short excerpt
     */
    public function getExcerpt() {
        return $this->Excerpt;
    }

    /**
     * @return string|null Medium used
     */
    public function getMedium() {
        return $this->Medium;
    }

    /**
     * @return string|null Original location
     */
    public function getOriginalHome() {
        return $this->OriginalHome;
    }

    /**
     * @return string|null Link to more information
     */
    public function getArtWorkLink() {
        return $this->ArtWorkLink;
    }

    /**
     * @return string|null Google Maps link
     */
    public function getGoogleLink() {
        return $this->GoogleLink;
    }
}
?>
