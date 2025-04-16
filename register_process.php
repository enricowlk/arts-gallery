<?php
session_start();

require_once 'customer.php';
require_once 'customerRepository.php';
require_once 'database.php';

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und bereinigen
    $data = array_map('trim', $_POST);  // Trimmen aller POST-Daten

    // Fehler sammeln
    $errors = [];

    // E-Mail und Passwort-Validierung
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address!';
    }
    if (strlen($data['password']) < 8 || !preg_match('/[a-z]/', $data['password']) || !preg_match('/[A-Z]/', $data['password']) || !preg_match('/[0-9]/', $data['password']) || !preg_match('/[\W_]/', $data['password'])) {
        $errors[] = 'Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.';
    }

    // Falls Fehler vorhanden sind → zurück zur Seite
    if ($errors) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: site_register.php');
        exit();
    }

    // Repository-Instanz erstellen und versuchen, den Kunden hinzuzufügen
    $customerRepo = new CustomerRepository(new Database());
    try {
        if ($customerRepo->emailExists($data['email'])) {
            $_SESSION['error'] = 'The email address is already registered!';
        } else {
            $customer = new Customer(null, $data['firstName'], $data['lastName'], $data['address'], $data['city'], $data['country'], $data['postal'], $data['phone'], $data['email']);
            $customerRepo->addCustomer($customer, $data['password']);
            $_SESSION['success'] = 'Registration successful! You can now log in.';
        }
    } catch (Exception $ex) {
        $_SESSION['error'] = 'An error has occurred: ' . $ex->getMessage();
    }

    // Zurück zur Registrierungsseite weiterleiten
    header('Location: site_register.php');
    exit();
}
?>
