<?php
/**
 * Handles user authentication and login process
 * 
 * This controller processes POST requests for user login functionality.
 * Implements parts of Use Case 22 - Login as user.
 */

// Start session for storing authentication data
session_start();

// Include required dependencies
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php'; 

/**
 * Process login request
 * Only handles POST requests for security
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    // Sanitize login credentials by trimming whitespace
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    /**
     * Attempt user authentication
     * - Retrieves user from database by email
     * - Verifies password against stored hash
     */
    $customerRepo = new CustomerRepository(new Database());
    $user = $customerRepo->getCustomerByEmail($db, $email);

    // Verify credentials and create user session if valid
    if ($user && password_verify($password, $user['Pass'])) { 
        $_SESSION['user'] = [
            'CustomerID' => $user['CustomerID'],
            'FirstName' => $user['FirstName'],
            'LastName' => $user['LastName'],
            'Email' => $user['UserName'],
            'Type' => (int)$user['Type'], 
            'is_admin' => ($user['Type'] == 1) // Admin status flag
        ];
        
        // Redirect to homepage after successful login
        header("Location: ../../index.php");
        exit();
    } else { 
        // Failed login handling
        $_SESSION['error'] = "Wrong Password or E-Mail!";
        header("Location: ../views/site_login.php"); // Redirect back to login
        exit();
    }
}
?>