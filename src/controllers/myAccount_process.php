<?php
session_start(); // Session starten für Daten aus der Session

// Prüft ob User eingeloggt ist
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

// Holt User-Daten aus Session und POST
$customerID = $_SESSION['user']['CustomerID'];
$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirmPassword = trim($_POST['confirmPassword']);

// Validierungsschritte:
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Wrong Email!';
} elseif ($password !== $confirmPassword) {
    $_SESSION['error'] = 'Passwords do not match!';
} elseif (!empty($password)) { 
    // Passwortstärke-Check
    if (strlen($password) < 8 || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W_]/', $password)) {
        $_SESSION['error'] = 'Password is to weak!';
    }
}

// Wenn keine Fehler, dann Datenbank-Update der User-Daten
if (!isset($_SESSION['error'])) {
    require_once __DIR__ . '/../repositories/customerRepository.php';
    require_once __DIR__ . '/../../config/database.php';

    $customerRepo = new CustomerRepository(new Database());
    $customerRepo->updateCustomer($customerID, $firstName, $lastName, $email, $password);

    $_SESSION['success'] = 'Changes saved!';
}

// Zurück zur myAccount Seite
header("Location: ../views/site_myaccount.php?id=$customerID");
exit();
?>