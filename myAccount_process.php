<?php
session_start();

// Prüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

// Eingaben aus POST-Request holen und trimmen
$customerID = $_SESSION['user']['CustomerID'];
$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirmPassword = trim($_POST['confirmPassword']);

// Überprüfen der E-Mail und der Passwörter
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address!';
} elseif ($password !== $confirmPassword) {
    $_SESSION['error'] = 'The passwords do not match!';
} elseif (!empty($password)) { // Nur wenn ein Passwort eingegeben wurde
    // Passwortvalidierung
    if (strlen($password) < 8 || !preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
        $_SESSION['error'] = 'Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.';
    }
}

if (!isset($_SESSION['error'])) { // Wenn keine Fehler aufgetreten sind
    // Datenbank-Update
    require_once 'CustomerRepository.php';
    require_once 'database.php';

    $customerRepo = new CustomerRepository(new Database());
    $customerRepo->updateCustomer($customerID, $firstName, $lastName, $email, $password);

    $_SESSION['success'] = 'Your changes have been saved!';
}

// Weiterleitung zur MyAccount-Seite
header("Location: site_myaccount.php?id=$customerID");
exit();
?>
