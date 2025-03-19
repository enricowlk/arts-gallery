<?php
session_start(); // Session starten, um Meldungen zu speichern

require_once 'Customer.php';
require_once 'CustomerRepository.php';
require_once 'database.php';

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und bereinigen
    $password = trim($_POST['password']);
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $country = trim($_POST['country']);
    $postal = trim($_POST['postal']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Repository-Instanz erstellen
    $customerRepo = new CustomerRepository(new Database());

    try {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = 'Invalid email address!';
            header('Location: site_register.php');
            exit();
        }
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
        $_SESSION['error'] = 'An error has occurred:' . $ex->getMessage();
    }

    // Zurück zur Registrierungsseite weiterleiten
    header('Location: site_register.php');
    exit();
}