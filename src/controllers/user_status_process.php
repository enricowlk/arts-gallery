<?php
/**
 * Admin-only script to deactivate or reactivate users.
 * Validates request parameters, prevents deactivation of self or the last administrator,
 * and executes the corresponding action.
 */

session_start(); // Start the session

// Check if user is logged in and is an administrator
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php");
    exit();
}

// Include required classes
require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../repositories/customerServiceRepository.php';
require_once __DIR__ . '/../../config/database.php';

// Initialize repository with database connection
$customerRepo = new CustomerRepository(new Database());
// Initialize customer service repository
$customerServiceRepo = new CustomerServiceRepository(new Database());

// Validate input parameters
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Sanitize inputs
$customerID = (int)$_GET['id'];
$action = $_GET['action'];

// Allow only 'deactivate' or 'reactivate' actions
if ($action !== 'deactivate' && $action !== 'reactivate') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Check if the user exists
$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// Prevent deactivation of oneself
if ($_SESSION['user']['CustomerID'] == $customerID) {
    $_SESSION['error'] = "You cannot deactivate yourself.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

// For Check if the user is an administrator
$userType = $customerRepo->getUserRole($customerID);

// Prevent deactivation of the last administrator
if ($userType === 1) {
    $adminCount = $customerRepo->countAdministrators();

    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot deactivate the last administrator.";
        header("Location: ../views/site_manage_users.php");
        exit();
    }
}

// Execute deactivate or reactivate action
if ($action === 'deactivate') {
    $success = $customerServiceRepo->deactivateUser($customerID);

    if ($success) {
        $_SESSION['success'] = "User deactivated successfully.";
    } else {
        $_SESSION['error'] = "There was an error deactivating the user.";
    }
} elseif ($action === 'reactivate') {
    $success = $customerServiceRepo->reactivateUser($customerID);

    if ($success) {
        $_SESSION['success'] = "User reactivated successfully.";
    } else {
        $_SESSION['error'] = "There was an error reactivating the user.";
    }
}

// Redirect back to the Manage Users page
header("Location: ../views/site_manage_users.php");
exit();
