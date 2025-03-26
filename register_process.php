<?php
session_start(); // Startet die Session, um Erfolgs- oder Fehlermeldungen zu speichern

require_once 'customer.php'; // Bindet die Customer-Klasse ein
require_once 'customerRepository.php'; // Bindet die CustomerRepository-Klasse ein
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

    // Repository-Instanz erstellen
    $customerRepo = new CustomerRepository(new Database());

    try {
        // Überprüfen, ob die E-Mail gültig ist
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email address!'; // Fehlermeldung bei ungültiger E-Mail
            header('Location: site_register.php'); // Weiterleitung zur Registrierungsseite
            exit();
        }

        // Überprüfen, ob die E-Mail bereits existiert
        if ($customerRepo->emailExists($email)) {
            $_SESSION['error'] = 'The email address is already registered!'; // Fehlermeldung bei bereits registrierter E-Mail
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
        $_SESSION['error'] = 'An error has occurred:' . $ex->getMessage();
    }

    // Zurück zur Registrierungsseite weiterleiten
    header('Location: site_register.php');
    exit();
}
?>