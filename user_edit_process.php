<?php
session_start(); // Start the session

// Check if user is logged in and is an administrator
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); // Redirect to home page if not an admin
    exit();
}

require_once 'customerRepository.php'; // Include the CustomerRepository class
require_once 'database.php'; // Include the Database class

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: site_manage_users.php");
    exit();
}

// Create an instance of CustomerRepository
$customerRepo = new CustomerRepository(new Database());

// Retrieve and validate form data
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

// Validate customer ID
if ($customerID <= 0) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: site_manage_users.php");
    exit();
}

// Validate required fields
if (empty($firstName) || empty($lastName) || empty($email)) {
    $_SESSION['error'] = "First name, last name, and email are required.";
    header("Location: user_edit.php?id=" . $customerID);
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: user_edit.php?id=" . $customerID);
    exit();
}

// Check if email exists for other users
$this_db = new Database();
$this_db->connect();
$sql = 'SELECT CustomerID FROM customerlogon WHERE UserName = :email AND CustomerID != :customerID';
$stmt = $this_db->prepareStatement($sql);
$stmt->execute(['email' => $email, 'customerID' => $customerID]);
$existingUser = $stmt->fetch();
$this_db->close();

if ($existingUser) {
    $_SESSION['error'] = "This email is already in use by another user.";
    header("Location: user_edit.php?id=" . $customerID);
    exit();
}

// Validate passwords match if a new password is provided
if (!empty($password) && $password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: user_edit.php?id=" . $customerID);
    exit();
}

// Check if this is the last admin and trying to demote
if ($_SESSION['user']['CustomerID'] == $customerID && $userType == 0) {
    // Count admins
    $adminCount = $customerRepo->countAdministrators();
    
    if ($adminCount <= 1) {
        $_SESSION['error'] = "Cannot demote the last administrator account.";
        header("Location: user_edit.php?id=" . $customerID);
        exit();
    }
}

// All validation passed, update the user
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

// Update profile data
$success = $customerRepo->updateUserProfile($customerID, $userData, $password);

// Update user role if needed
$customerRepo->setUserRole($customerID, $userType);

// Update session data if the user is editing their own account
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

// Redirect back to the user edit page
header("Location: user_edit.php?id=" . $customerID);
exit();