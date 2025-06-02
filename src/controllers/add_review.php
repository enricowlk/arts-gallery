<?php
// Session starten (für Benutzerinformationen und Fehlermeldungen)
session_start();

// Einbindung der benötigten Dateien
require_once __DIR__ . '/../../config/database.php';       
require_once __DIR__ . '/../repositories/reviewRepository.php'; 

// Nur POST-Requests erlauben = Beendet, wenn kein POST-Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(); 
}

// Formulardaten aus POST-Request auslesen
$artworkId = $_POST['artwork_id'];
$rating = $_POST['rating'];         
$comment = $_POST['comment'];    

// Überprüfen ob Benutzer eingeloggt ist | Wenn nicht eingeloggt, dann zur Login-Seite weiterleiten
if (!isset($_SESSION['user'])) {
    header("Location: ../views/site_login.php");
    exit();
}

// CustomerID aus der Session holen
$customerId = $_SESSION['user']['CustomerID'];

// ReviewRepository mit Datenbankverbindung initialisieren
$reviewRepo = new ReviewRepository(new Database());

// Neue Bewertung in Datenbank speichern
$reviewRepo->addReview($artworkId, $customerId, $rating, $comment);

// Zurück zur Kunstwerk-Seite (mit erfolgreicher Bewertung)
header("Location: ../views/site_artwork.php?id=$artworkId");
exit();
?>