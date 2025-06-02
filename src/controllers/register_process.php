<?php
// Session starten für Benachrichtigungen und Benutzerverwaltung
session_start();

// Einbinden der benötigten Klassen und Konfigurationen
require_once __DIR__ . '/../entitys/customer.php';
require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../../config/database.php';

// Nur POST-Requests verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Alle POST-Daten trimmen (Leerzeichen entfernen)
    $data = array_map('trim', $_POST);  

    // Array für Fehlermeldungen initialisieren
    $errors = [];

    // E-Mail-Format validieren
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address!';
    }
    // Passwortstärke überprüfen (Mindestlänge und Komplexität)
    if (strlen($data['password']) < 8 || 
        !preg_match('/[a-z]/', $data['password']) || 
        !preg_match('/[A-Z]/', $data['password']) || 
        !preg_match('/[0-9]/', $data['password']) || 
        !preg_match('/[\W_]/', $data['password'])) {
        $errors[] = 'Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.';
    }

    // Falls Fehler aufgetreten sind:
    if ($errors) {
        // Fehlermeldungen in Session speichern
        $_SESSION['error'] = implode('<br>', $errors);
        // Zurück zum Registrierungsformular
        header('Location: ../views/site_register.php');
        exit();
    }

    // CustomerRepository instanziieren (Datenbankzugriff)
    $customerRepo = new CustomerRepository(new Database());
    
    try {
        // Prüfen ob E-Mail bereits existiert
        if ($customerRepo->emailExists($data['email'])) {
            $_SESSION['error'] = 'The email address is already registered!';
        } else {
            // Neues Customer-Objekt erstellen
            $customer = new Customer(
                null, 
                $data['firstName'], 
                $data['lastName'], 
                $data['address'], 
                $data['city'], 
                $data['country'], 
                $data['postal'], 
                $data['phone'], 
                $data['email']
            );
            
            // Customer in Datenbank speichern
            $customerRepo->addCustomer($customer, $data['password']);
            
            // Erfolgsmeldung setzen
            $_SESSION['success'] = 'Registration successful! You can now log in.';
        }
    } catch (Exception $ex) {
        // Fehlermeldung bei Datenbankfehlern
        $_SESSION['error'] = 'An error has occurred: ' . $ex->getMessage();
    }

    // Zurück zum Registrierungsformular (unabhängig von Erfolg/Fehler)
    header('Location: ../views/site_register.php');
    exit();
}