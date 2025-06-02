<?php
session_start(); // Session starten

// Prüfen ob User eingeloggt und Admin ist (Type = 1)
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); // Wenn nicht Admin, dann zur Startseite
    exit();
}

// Benötigte Dateien einbinden
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php';

// Repository initialisieren
$customerRepo = new CustomerRepository(new Database());

// Prüfen ob ID und Action Parameter vorhanden und gültig sind
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Parameter sichern/speichern
$customerID = (int)$_GET['id'];
$action = $_GET['action'];

// Nur promote/demote Aktionen erlauben
if ($action !== 'promote' && $action !== 'demote') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// User existenz prüfen
$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Aktuelle Rolle des Users holen
$currentRole = $customerRepo->getUserRole($customerID);

// Verhindern dass sich Admin selbst degradiert
if ($_SESSION['user']['CustomerID'] == $customerID && $action === 'demote') {
    $_SESSION['error'] = "You cannot demote yourself.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Verhindern dass letzter Admin degradiert wird
if ($action === 'demote' && $currentRole === 1) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot demote the last administrator.";
        header("Location: ../views/site_manage_users.php");
        exit();
    }
}

// Aktion durchführen (promote/demote)
if ($action === 'promote') {
    $customerRepo->setUserRole($customerID, 1); // Zu Admin befördern
    $_SESSION['success'] = "User promoted to administrator successfully.";
} else {
    $customerRepo->setUserRole($customerID, 0); // Zu normalem User degradieren
    $_SESSION['success'] = "Administrator demoted to regular user successfully.";
}

// Zurück zur Manage Users Seite
header("Location: ../views/site_manage_users.php");
exit();