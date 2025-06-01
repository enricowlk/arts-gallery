<?php
session_start(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); 
    exit();
}

require_once 'customerRepository.php'; 
require_once 'database.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: site_manage_users.php");
    exit();
}

$customerRepo = new CustomerRepository(new Database());

$customerID = isset($_POST['customerID']) ? (int)$_POST['customerID'] : 0;
$firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
$lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$postal = isset($_POST['postal']) ? trim($_POST['postal']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';
$userType = isset($_POST['userType']) ? (int)$_POST['userType'] : 0;

if ($customerID <= 0) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: site_manage_users.php");
    exit();
}

if (empty($firstName) || empty($lastName) || empty($email)) {
    $_SESSION['error'] = "First name, last name, and email are required.";
    header("Location: site_user_edit.php?id=" . $customerID);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: site_user_edit.php?id=" . $customerID);
    exit();
}

if ($customerRepo->emailExistsForOtherUser($email, $customerID)) {
    $_SESSION['error'] = "This email is already in use by another user.";
    header("Location: site_user_edit.php?id=" . $customerID);
    exit();
}

$errors = [];
if (
    strlen($password) < 8 ||  
    !preg_match('/[a-z]/', $password) ||  
    !preg_match('/[A-Z]/', $password) ||  
    !preg_match('/[0-9]/', $password) ||  
    !preg_match('/[\W_]/', $password)     
) {
    $errors[] = 'Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.';
}

if (!empty($password) && $password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: site_user_edit.php?id=" . $customerID);
    exit();
}

if ($_SESSION['user']['CustomerID'] == $customerID && $userType == 0) {
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot demote the last administrator account.";
        header("Location: site_user_edit.php?id=" . $customerID);
        exit();
    }
}

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

$success = $customerRepo->updateUserProfile($customerID, $userData, $password);

$customerRepo->setUserRole($customerID, $userType);

if ($_SESSION['user']['CustomerID'] == $customerID) {
    $_SESSION['user']['FirstName'] = $firstName;
    $_SESSION['user']['LastName'] = $lastName;
    $_SESSION['user']['Email'] = $email;
    $_SESSION['user']['Type'] = $userType;
}

if ($success) {
    $_SESSION['success'] = "User updated successfully.";
} else {
    $_SESSION['error'] = "There was an error updating the user.";
}

header("Location: site_user_edit.php?id=" . $customerID);
exit();