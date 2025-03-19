<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

require_once 'CustomerRepository.php';
require_once 'database.php';

$customerRepo = new CustomerRepository(new Database());


$customerID = $_SESSION['user']['CustomerID'];
$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirmPassword = trim($_POST['confirmPassword']);


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address!';
} elseif ($password !== $confirmPassword) {
    $_SESSION['error'] = 'The passwords do not match!';
} else {
    $customerRepo->updateCustomer($customerID, $firstName, $lastName, $email, $password);
    $_SESSION['success'] = 'Your changes have been saved!';
}

header("Location: site_myaccount.php");
exit();