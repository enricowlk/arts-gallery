<?php
/**
 * Class Artist
 *
 * Represents an artist entity.
 */
class Artist {
    /**
     * @var int Artist ID
     */
    private $ArtistID;

    /**
     * @var string|null First name of the artist
     */
    private $FirstName;

    /**
     * @var string Last name of the artist
     */
    private $LastName;

    /**
     * @var string|null Nationality of the artist
     */
    private $Nationality;

    /**
     * @var int|null Year of birth
     */
    private $YearOfBirth;

    /**
     * @var int|null Year of death
     */
    private $YearOfDeath;

    /**
     * @var string|null Additional details about the artist
     */
    private $Details;

    /**
     * @var string|null Link to artist-related information
     */
    private $ArtistLink;

    /**
     * Artist constructor.
     *
     * @param int $ArtistID Artist ID
     * @param string|null $FirstName First name of the artist
     * @param string $LastName Last name of the artist
     * @param string|null $Nationality Nationality of the artist
     * @param int|null $YearOfBirth Year of birth
     * @param int|null $YearOfDeath Year of death
     * @param string|null $Details Additional details
     * @param string|null $ArtistLink Link to more information
     */
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
        $this->ArtistID = $ArtistID;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->Nationality = $Nationality;
        $this->YearOfBirth = $YearOfBirth;
        $this->YearOfDeath = $YearOfDeath;
        $this->Details = $Details;
        $this->ArtistLink = $ArtistLink;
    }

    /**
     * @return int Artist ID
     */
    public function getArtistID() {
        return $this->ArtistID;
    }

    /**
     * @return string|null First name of the artist
     */
    public function getFirstName() {
        return $this->FirstName;
    }

    /**
     * @return string Last name of the artist
     */
    public function getLastName() {
        return $this->LastName;
    }

    /**
     * @return string|null Nationality of the artist
     */
    public function getNationality() {
        return $this->Nationality;
    }

    /**
     * @return int|null Year of birth
     */
    public function getYearOfBirth() {
        return $this->YearOfBirth;
    }

    /**
     * @return int|null Year of death
     */
    public function getYearOfDeath() {
        return $this->YearOfDeath;
    }

    /**
     * @return string|null Additional details about the artist
     */
    public function getDetails() {
        return $this->Details;
    }

    /**
     * @return string|null Link to artist-related information
     */
    public function getArtistLink() {
        return $this->ArtistLink;
    }
}
?>
