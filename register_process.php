<?php
session_start(); // Startet die Session, um Erfolgs- oder Fehlermeldungen zu speichern

require_once 'Customer.php'; // Bindet die Customer-Klasse ein
require_once 'CustomerRepository.php'; // Bindet die CustomerRepository-Klasse ein
require_once 'database.php'; // Bindet die Database-Klasse ein

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und bereinigen
    $password = trim($_POST['password']); // Passwort
    $firstName = trim($_POST['first_name']); // Vorname
    $lastName = trim($_POST['last_name']); // Nachname
    $address = trim($_POST['address']); // Adresse
    $city = trim($_POST['city']); // Stadt
    $country = trim($_POST['country']); // Land
    $postal = trim($_POST['postal']); // Postleitzahl
    $phone = trim($_POST['phone']); // Telefonnummer
    $email = trim($_POST['email']); // E-Mail

    // Fehler sammeln
    $errors = [];

    // Überprüfen, ob die E-Mail gültig ist
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address!';
    }

    // Passwort-Validierung
    if (
        strlen($password) < 8 ||  // Mindestlänge 8 Zeichen
        !preg_match('/[a-z]/', $password) ||  // Muss Kleinbuchstaben enthalten
        !preg_match('/[A-Z]/', $password) ||  // Muss Großbuchstaben enthalten
        !preg_match('/[0-9]/', $password) ||  // Muss Zahl enthalten
        !preg_match('/[\W_]/', $password)     // Muss Sonderzeichen oder _ enthalten
    ) {
        $errors[] = 'Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.';
    }

    // Falls Fehler vorhanden sind → zurück zur Seite
    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: site_register.php');
        exit();
    }

    // Repository-Instanz erstellen
    $customerRepo = new CustomerRepository(new Database());

    try {
        // Überprüfen, ob die E-Mail bereits existiert
        if ($customerRepo->emailExists($email)) {
            $_SESSION['error'] = 'The email address is already registered!';
        } else {
            // Neuen Kunden erstellen
            $customer = new Customer(null, $firstName, $lastName, $address, $city, $country, $postal, $phone, $email);

            // Kunden und Login-Daten in die Datenbank einfügen
            $customerRepo->addCustomer($customer, $password);

            // Erfolgsmeldung setzen
            $_SESSION['success'] = 'Registration successful! You can now log in.';
        }
    } catch (Exception $ex) {
        // Fehlermeldung bei einem Datenbankfehler
        $_SESSION['error'] = 'An error has occurred: ' . $ex->getMessage();
    }

    // Zurück zur Registrierungsseite weiterleiten
    header('Location: site_register.php');
    exit();
}
?>