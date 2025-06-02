<?php
session_start();

// Prüfen ob User eingeloggt ist
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../../config/database.php';

$customerRepo = new CustomerRepository(new Database());

// Trimmen der POST-Daten
$customerID = isset($_POST['customerID']) ? (int)$_POST['customerID'] : $_SESSION['user']['CustomerID'];
$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$email = trim($_POST['email']);
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$country = trim($_POST['country'] ?? '');
$postal = trim($_POST['postal'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$userType = isset($_POST['userType']) ? (int)$_POST['userType'] : 0;

// Validierung
if (empty($firstName) || empty($lastName) || empty($email)) {
    $_SESSION['error'] = "First name, last name, and email are required.";
    redirectBack($customerID);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    redirectBack($customerID);
}

// Email-Prüfung nur wenn sich die Email geändert hat
$currentCustomer = $customerRepo->getCustomerByID($customerID);
if (strtolower($email) !== strtolower($currentCustomer->getEmail()) && 
    $customerRepo->emailExistsForOtherUser($email, $customerID)) {
    $_SESSION['error'] = "This email is already in use by another user.";
    redirectBack($customerID);
}

// Passwortvalidierung nur wenn Passwort geändert wird
if (!empty($password)) {
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        redirectBack($customerID);
    }
    
    if (strlen($password) < 8 || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W_]/', $password)) {
        $_SESSION['error'] = "Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.";
        redirectBack($customerID);
    }
}

// Admin-spezifische Prüfungen (nur für user_edit)
if (isset($_POST['userType'])) {
    // Prüfen ob Admin sich selbst degradieren will
    if ($_SESSION['user']['CustomerID'] == $customerID && $userType == 0) {
        $adminCount = $customerRepo->countAdministrators();
        
        if ($adminCount <= 1) {
            $_SESSION['error'] = "Cannot demote the last administrator account.";
            redirectBack($customerID);
        }
    }
}

// Daten vorbereiten
$userData = [
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $email,
    'address' => $address,
    'city' => $city,
    'country' => $country,
    'postal' => $postal,
    'phone' => $phone
];

// Update durchführen
$success = $customerRepo->updateUserProfile($customerID, $userData, $password);

// Falls es sich um user_edit handelt, Rolle aktualisieren
if (isset($_POST['userType'])) {
    $customerRepo->setUserRole($customerID, $userType);
}

// Session-Daten aktualisieren wenn der eingeloggte User sein eigenes Profil bearbeitet
if ($_SESSION['user']['CustomerID'] == $customerID) {
    $_SESSION['user']['FirstName'] = $firstName;
    $_SESSION['user']['LastName'] = $lastName;
    $_SESSION['user']['Email'] = $email;
    if (isset($_POST['userType'])) {
        $_SESSION['user']['Type'] = $userType;
    }
}

if ($success) {
    $_SESSION['success'] = "Changes saved successfully.";
} else {
    $_SESSION['error'] = "There was an error updating the profile.";
}

redirectBack($customerID);

function redirectBack($customerID) {
    // Entscheiden wohin zurückgeleitet wird basierend auf der Quelle
    if (isset($_POST['userType'])) {
        // Kommt von user_edit
        header("Location: ../views/site_user_edit.php?id=" . $customerID);
    } else {
        // Kommt von myAccount
        header("Location: ../views/site_myaccount.php?id=" . $customerID);
    }
    exit();
}
?>