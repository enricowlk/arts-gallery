<?php
/**
 * Admin-only script to promote or demote users.
 * Validates request parameters, ensures security rules (e.g., cannot demote last admin),
 * and updates user roles accordingly.
 */

session_start(); // Start session

// Check if the current user is logged in and is an administrator
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php");
    exit();
}

// Include required classes
require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../repositories/customerServiceRepository.php';
require_once __DIR__ . '/../../config/database.php';

// Initialize the CustomerRepository with database connection
$customerRepo = new CustomerRepository(new Database());
// Initialize the CustomerServiceRepository with database connection
$customerServiceRepo = new CustomerServiceRepository(new Database());

// Validate request parameters
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Sanitize and extract parameters
$customerID = (int)$_GET['id'];
$action = $_GET['action'];

// Allow only 'promote' and 'demote' actions
if ($action !== 'promote' && $action !== 'demote') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Check if the target user exists
$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../views/site_manage_users.php");
    exit();
}


// Prevent an administrator from demoting themselves
if ($_SESSION['user']['CustomerID'] == $customerID && $action === 'demote') {
    $_SESSION['error'] = "You cannot demote yourself.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Get current role of the target user
$currentRole = $customerRepo->getUserRole($customerID);

// Prevent demotion if user is the last administrator
if ($action === 'demote' && $currentRole === 1) {
    $adminCount = $customerRepo->countAdministrators();

    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot demote the last administrator.";
        header("Location: ../views/site_manage_users.php");
        exit();
    }
}

// Perform the action
if ($action === 'promote') {
    $customerServiceRepo->setUserRole($customerID, 1);
    $_SESSION['success'] = "User promoted to administrator successfully.";
} else {
    $customerServiceRepo->setUserRole($customerID, 0);
    $_SESSION['success'] = "Administrator demoted to regular user successfully.";
}

// Redirect back to manage users page
header("Location: ../views/site_manage_users.php");
exit();
