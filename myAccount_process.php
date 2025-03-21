<?php
session_start(); // Startet die Session

// Überprüft, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php"); // Weiterleitung zur Login-Seite, wenn nicht angemeldet
    exit();
}

// Bindet die benötigten Klassen ein
require_once 'CustomerRepository.php';
require_once 'database.php';

// Erstellt eine Instanz von CustomerRepository mit der Datenbankverbindung
$customerRepo = new CustomerRepository(new Database());

// Holt die Kundendaten aus der Session und dem POST-Request
$customerID = $_SESSION['user']['CustomerID']; // CustomerID aus der Session
$firstName = trim($_POST['firstName']); // Vorname aus dem POST-Request
$lastName = trim($_POST['lastName']); // Nachname aus dem POST-Request
$email = trim($_POST['email']); // E-Mail aus dem POST-Request
$password = trim($_POST['password']); // Passwort aus dem POST-Request
$confirmPassword = trim($_POST['confirmPassword']); // Passwortbestätigung aus dem POST-Request

// Überprüft die Eingaben
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address!'; // Fehlermeldung bei ungültiger E-Mail
} elseif ($password !== $confirmPassword) {
    $_SESSION['error'] = 'The passwords do not match!'; // Fehlermeldung bei nicht übereinstimmenden Passwörtern
} else {
    // Aktualisiert die Kundendaten in der Datenbank
    $customerRepo->updateCustomer($customerID, $firstName, $lastName, $email, $password);
    $_SESSION['success'] = 'Your changes have been saved!'; // Erfolgsmeldung
}

// Weiterleitung zur MyAccount-Seite
header("Location: site_myaccount.php");
exit();
?>