<?php
session_start(); // Start the session

// Check if user is logged in and is an administrator
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); // Redirect to home page if not an admin
    exit();
}

require_once 'customerRepository.php'; // Include the CustomerRepository class
require_once 'database.php'; // Include the Database class

// Create an instance of CustomerRepository
$customerRepo = new CustomerRepository(new Database());

// Check if required parameters exist
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: site_manage_users.php");
    exit();
}

// Get the user ID and action from the query parameters
$customerID = (int)$_GET['id'];
$action = $_GET['action'];

// Validate the action
if ($action !== 'promote' && $action !== 'demote') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: site_manage_users.php");
    exit();
}

// Check if the user exists
$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: site_manage_users.php");
    exit();
}

// Get current role
$currentRole = $customerRepo->getUserRole($customerID);

// Prevent self-demotion
if ($_SESSION['user']['CustomerID'] == $customerID && $action === 'demote') {
    $_SESSION['error'] = "You cannot demote yourself.";
    header("Location: site_manage_users.php");
    exit();
}

// Check if last admin is being demoted
if ($action === 'demote' && $currentRole === 1) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot demote the last administrator.";
        header("Location: site_manage_users.php");
        exit();
    }
}

// Perform the action
if ($action === 'promote') {
    // Promote user to admin
    $customerRepo->setUserRole($customerID, 1);
    $_SESSION['success'] = "User promoted to administrator successfully.";
} else {
    // Demote user to regular
    $customerRepo->setUserRole($customerID, 0);
    $_SESSION['success'] = "Administrator demoted to regular user successfully.";
}

// Redirect back to the manage users page
header("Location: site_manage_users.php");
exit();