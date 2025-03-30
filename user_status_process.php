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
if ($action !== 'deactivate') {
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

// Prevent self-deactivation
if ($_SESSION['user']['CustomerID'] == $customerID) {
    $_SESSION['error'] = "You cannot deactivate yourself.";
    header("Location: site_manage_users.php");
    exit();
}

// Check if deactivating the last admin
$this_db = new Database();
$this_db->connect();
$sql = 'SELECT Type FROM customerlogon WHERE CustomerID = :customerID';
$stmt = $this_db->prepareStatement($sql);
$stmt->execute(['customerID' => $customerID]);
$userType = (int)$stmt->fetchColumn();
$this_db->close();

if ($userType === 1) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot deactivate the last administrator.";
        header("Location: site_manage_users.php");
        exit();
    }
}

// Perform the deactivation
$success = $customerRepo->deactivateUser($customerID);

if ($success) {
    $_SESSION['success'] = "User deactivated successfully.";
} else {
    $_SESSION['error'] = "There was an error deactivating the user.";
}

// Redirect back to the manage users page
header("Location: site_manage_users.php");
exit();