<?php
session_start(); // Session starten für Daten aus der Session

// Datenbank und Repository einbinden
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php'; 

// Nur POST-Requests erlauben
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    // Login-Daten säubern, um unnötige Leerzeichen zu vermeiden
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // User aus DB holen
    $customerRepo = new CustomerRepository(new Database());
    $user = $customerRepo->getCustomerByEmail($db, $email);

    // Wenn Login erfolgreich, dann User-Daten in Session speichern && weiterleitung zur Homepage
    if ($user && password_verify($password, $user['Pass'])) { 
        $_SESSION['user'] = [
            'CustomerID' => $user['CustomerID'],
            'FirstName' => $user['FirstName'],
            'LastName' => $user['LastName'],
            'Email' => $user['UserName'],
            'Type' => (int)$user['Type'], 
            'is_admin' => ($user['Type'] == 1) // Admin-Check
        ];
        
        header("Location: ../../index.php");
        exit();
    } else { // Login fehlgeschlagen
        $_SESSION['error'] = "Wrong Password or E-Mail!";
        header("Location: ../views/site_login.php"); // Zurück zum Login
        exit();
    }
}