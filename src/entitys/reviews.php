<?php
/**
 * Klasse zur Darstellung einer Kunstwerk-Bewertung
 * Enthält Kundenbewertungen für Kunstwerke im System
 */
class Review {
    // Private Eigenschaften
    private $ReviewId;     
    private $ArtWorkId;   
    private $CustomerId;   
    private $ReviewDate;
    private $Rating;      
    private $Comment;     

    //Konstruktor für Review-Objekte
    public function __construct($ReviewId, $ArtWorkId, $CustomerId, $ReviewDate, $Rating, $Comment) {
        $this->ReviewId = $ReviewId;
        $this->ArtWorkId = $ArtWorkId;
        $this->CustomerId = $CustomerId;
        $this->ReviewDate = $ReviewDate;
        $this->Rating = $Rating;
        $this->Comment = $Comment;
    }

    // --- Getter-Methoden ---
    public function getReviewId() {
        return $this->ReviewId;
    }

    public function getArtWorkId() {
        return $this->ArtWorkId;
    }

    public function getCustomerId() {
        return $this->CustomerId;
    }

    public function getReviewDate() {
        return $this->ReviewDate;
    }

    public function getRating() {
        return $this->Rating;
    }

    public function getComment() {
        return $this->Comment;
    }
}