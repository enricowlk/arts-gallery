<?php
session_start(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); 
    exit();
}

require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php';

$customerRepo = new CustomerRepository(new Database());

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

$customerID = (int)$_GET['id'];
$action = $_GET['action'];

if ($action !== 'deactivate' && $action !== 'reactivate') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../views/site_manage_users.php");
    exit();
}
if ($_SESSION['user']['CustomerID'] == $customerID) {
    $_SESSION['error'] = "You cannot deactivate yourself.";
    header("Location: ../views/site_manage_users.php");
    exit();
}

$userType = $customerRepo->getUserRole($customerID);

if ($userType === 1) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot deactivate the last administrator.";
        header("Location: ../views/site_manage_users.php");
        exit();
    }
}

if ($action === 'deactivate') {
    $success = $customerRepo->deactivateUser($customerID);
    
    if ($success) {
        $_SESSION['success'] = "User deactivated successfully.";
    } else {
        $_SESSION['error'] = "There was an error deactivating the user.";
    }
} else if ($action === 'reactivate') {
    $success = $customerRepo->reactivateUser($customerID);
    
    if ($success) {
        $_SESSION['success'] = "User reactivated successfully.";
    } else {
        $_SESSION['error'] = "There was an error reactivating the user.";
    }
}

header("Location: ../views/site_manage_users.php");
exit();