<?php
/**
 * Klasse Review
 * 
 * Repräsentiert eine Bewertung mit Eigenschaften wie Bewertungs-ID, Kunstwerk-ID, Kunden-ID, Datum, Bewertung und Kommentar.
 * Enthält Getter- und Setter-Methoden für den Zugriff auf die Eigenschaften.
 */
class Review {
    // Eigenschaften der Klasse
    private $ReviewId;    // Eindeutige ID der Bewertung
    private $ArtWorkId;   // ID des bewerteten Kunstwerks
    private $CustomerId;  // ID des Kunden, der die Bewertung abgegeben hat
    private $ReviewDate;  // Datum der Bewertung
    private $Rating;      // Bewertung (z. B. 1–5 Sterne)
    private $Comment;     // Kommentar zur Bewertung

    /**
     * Konstruktor
     * 
     * Initialisiert die Eigenschaften der Bewertung.
     * 
     * Eingabe: ReviewId, ArtWorkId, CustomerId, ReviewDate, Rating und Comment.
     */
    public function __construct($ReviewId, $ArtWorkId, $CustomerId, $ReviewDate, $Rating, $Comment) {
        $this->ReviewId = $ReviewId;
        $this->ArtWorkId = $ArtWorkId;
        $this->CustomerId = $CustomerId;
        $this->ReviewDate = $ReviewDate;
        $this->Rating = $Rating;
        $this->Comment = $Comment;
    }

    // Getter
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
?>