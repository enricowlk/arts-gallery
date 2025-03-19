<?php
session_start(); // Session starten, um den Benutzer nach der Anmeldung zu speichern

require_once 'CustomerRepository.php';
require_once 'database.php';

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Formulardaten validieren und bereinigen
    $email = trim($_POST['username']); // E-Mail wird als "username" verwendet
    $password = trim($_POST['password']);

        
    // Repository-Instanz erstellen
    $customerRepo = new CustomerRepository(new Database());

    // Benutzer anhand der E-Mail-Adresse suchen
    $user = $customerRepo->getCustomerByEmail($db,$email);

    // Überprüfen, ob der Benutzer existiert und das Passwort korrekt ist
    if ($user && password_verify($password, $user['Pass'])) {
        // Benutzerdaten in der Session speichern
        $_SESSION['user'] = [
            'CustomerID' => $user['CustomerID'],
            'FirstName' => $user['FirstName'],
            'LastName' => $user['LastName'],
            'Email' => $user['UserName'],
            'Type' => $user['Type']
        ];

        // Weiterleitung zur Startseite
        header("Location: index.php");
        exit();
    } else {
        // Passwort oder E-Mail ist falsch
        $_SESSION['error'] = "Wrong Password or E-Mail!";
        header("Location: site_login.php");
        exit();
    }
}

