<?php
/**
 * Registration script for new users.
 * Validates user input, creates a new Customer object, and stores it in the database.
 * Uses session to pass success or error messages back to the registration page.
 */

session_start();

// Include required classes and configurations
require_once __DIR__ . '/../entitys/customer.php';
require_once __DIR__ . '/../repositories/customerRepository.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Handles POST request for user registration.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim all incoming POST data
    $data = array_map('trim', $_POST);
    $errors = [];

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address!';
    }

    // Validate password strength (min 8 chars, includes upper/lowercase, number, special character)
    if (strlen($data['password']) < 8 ||
        !preg_match('/[a-z]/', $data['password']) ||
        !preg_match('/[A-Z]/', $data['password']) ||
        !preg_match('/[0-9]/', $data['password']) ||
        !preg_match('/[\W_]/', $data['password'])) {
        $errors[] = 'Password must be at least 8 characters long and contain uppercase, lowercase, a number, and a special character.';
    }

    // If any validation errors occurred, redirect with error messages
    if ($errors) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: ../views/site_register.php');
        exit();
    }

    // Instantiate CustomerRepository with database connection
    $customerRepo = new CustomerRepository(new Database());

    try {
        // Check if email already exists in the system
        if ($customerRepo->emailExists($data['email'])) {
            $_SESSION['error'] = 'The email address is already registered!';
        } else {
            // Create a new Customer object
            $customer = new Customer(
                null,
                $data['firstName'],
                $data['lastName'],
                $data['address'],
                $data['city'],
                $data['country'],
                $data['postal'],
                $data['phone'],
                $data['email']
            );

            // Save the new customer and hashed password
            $customerRepo->addCustomer($customer, $data['password']);

            // Set success message
            $_SESSION['success'] = 'Registration successful! You can now log in.';
        }
    } catch (Exception $ex) {
        // Handle exceptions (e.g., database errors)
        $_SESSION['error'] = 'An error has occurred: ' . $ex->getMessage();
    }

    // Redirect back to registration page
    header('Location: ../views/site_register.php');
    exit();
}
