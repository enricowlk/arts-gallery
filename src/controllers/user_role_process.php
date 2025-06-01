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
    header("Location: site_manage_users.php");
    exit();
}

$customerID = (int)$_GET['id'];
$action = $_GET['action'];

if ($action !== 'promote' && $action !== 'demote') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: site_manage_users.php");
    exit();
}

$user = $customerRepo->getCustomerByID($customerID);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: site_manage_users.php");
    exit();
}

$currentRole = $customerRepo->getUserRole($customerID);

if ($_SESSION['user']['CustomerID'] == $customerID && $action === 'demote') {
    $_SESSION['error'] = "You cannot demote yourself.";
    header("Location: site_manage_users.php");
    exit();
}

if ($action === 'demote' && $currentRole === 1) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot demote the last administrator.";
        header("Location: site_manage_users.php");
        exit();
    }
}

if ($action === 'promote') {
    $customerRepo->setUserRole($customerID, 1);
    $_SESSION['success'] = "User promoted to administrator successfully.";
} else {
    $customerRepo->setUserRole($customerID, 0);
    $_SESSION['success'] = "Administrator demoted to regular user successfully.";
}

header("Location: site_manage_users.php");
exit();