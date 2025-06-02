<?php
session_start(); // Session starten

// Prüfen ob User eingeloggt und Admin ist (Type = 1)
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); // Wenn nicht Admin, zur Startseite
    exit();
}

// Dateien einbinden
require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../../config/database.php';

// Repository initialisieren
$customerRepo = new CustomerRepository(new Database());

// Parameter-Validierung: ID muss numerisch sein und Action muss gesetzt sein
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Parameter sichern bzw. speichern
$customerID = (int)$_GET['id'];
$action = $_GET['action'];

// Nur deactivate/reactivate Aktionen erlauben
if ($action !== 'deactivate' && $action !== 'reactivate') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Existenz des Users prüfen
$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Verhindern dass Admin sich selbst deaktiviert
if ($_SESSION['user']['CustomerID'] == $customerID) {
    $_SESSION['error'] = "You cannot deactivate yourself.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Aktuelle Rolle des Users holen
$userType = $customerRepo->getUserRole($customerID);

// Verhindern dass letzter Admin deaktiviert wird
if ($userType === 1) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot deactivate the last administrator.";
        header("Location: ../views/site_manage_users.php");
        exit();
    }
}

// Deaktivierungsaktion durchführen
if ($action === 'deactivate') {
    $success = $customerRepo->deactivateUser($customerID);
    
    if ($success) {
        $_SESSION['success'] = "User deactivated successfully.";
    } else {
        $_SESSION['error'] = "There was an error deactivating the user.";
    }
} 
// Reaktivierungsaktion durchführen
else if ($action === 'reactivate') {
    $success = $customerRepo->reactivateUser($customerID);
    
    if ($success) {
        $_SESSION['success'] = "User reactivated successfully.";
    } else {
        $_SESSION['error'] = "There was an error reactivating the user.";
    }
}

// Zurück zur ManageUsers Seite
header("Location: ../views/site_manage_users.php");
exit();