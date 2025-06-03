<?php
/**
 * Script for updating a user's profile (admin or self-service).
 * Validates inputs, handles role changes, prevents critical errors like demoting the last admin,
 * and updates session data if needed.
 */

session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

// Include dependencies
require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../../config/database.php';

$customerRepo = new CustomerRepository(new Database());

// Extract and trim POST data
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

// Basic input validation
if (empty($firstName) || empty($lastName) || empty($email)) {
    $_SESSION['error'] = "First name, last name, and email are required.";
    redirectBack($customerID);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    redirectBack($customerID);
}

// Check if email is being changed and already exists
$currentCustomer = $customerRepo->getCustomerByID($customerID);
if (strtolower($email) !== strtolower($currentCustomer->getEmail()) &&
    $customerRepo->emailExistsForOtherUser($email, $customerID)) {
    $_SESSION['error'] = "This email is already in use by another user.";
    redirectBack($customerID);
}

// Validate password if being changed
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

// Admin-specific checks (when editing another user)
if (isset($_POST['userType'])) {
    // Prevent self-demotion if last admin
    if ($_SESSION['user']['CustomerID'] == $customerID && $userType === 0) {
        $adminCount = $customerRepo->countAdministrators();

        if ($adminCount <= 1) {
            $_SESSION['error'] = "Cannot demote the last administrator account.";
            redirectBack($customerID);
        }
    }
}

// Prepare data for update
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

// Perform update
$success = $customerRepo->updateUserProfile($customerID, $userData, $password);

// Update user role if admin edited another user
if (isset($_POST['userType'])) {
    $customerRepo->setUserRole($customerID, $userType);
}

// Update session if the logged-in user edited their own profile
if ($_SESSION['user']['CustomerID'] === $customerID) {
    $_SESSION['user']['FirstName'] = $firstName;
    $_SESSION['user']['LastName'] = $lastName;
    $_SESSION['user']['Email'] = $email;
    if (isset($_POST['userType'])) {
        $_SESSION['user']['Type'] = $userType;
    }
}

// Provide feedback
if ($success) {
    $_SESSION['success'] = "Changes saved successfully.";
} else {
    $_SESSION['error'] = "There was an error updating the profile.";
}

redirectBack($customerID);

/**
 * Redirects user back to the appropriate page based on context.
 *
 * @param int $customerID The ID of the customer being edited.
 * @return void
 */
function redirectBack(int $customerID): void {
    if (isset($_POST['userType'])) {
        // Admin editing another user
        header("Location: ../views/site_user_edit.php?id=" . $customerID);
    } else {
        // Self-service profile update
        header("Location: ../views/site_myaccount.php?id=" . $customerID);
    }
    exit();
}
?>
