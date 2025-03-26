<?php
session_start();
require_once 'customerRepository.php';
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $customerRepo = new CustomerRepository(new Database());
    $user = $customerRepo->getCustomerByEmail($db, $email);

    if ($user && password_verify($password, $user['Pass'])) {
        $_SESSION['user'] = [
            'CustomerID' => $user['CustomerID'],
            'FirstName' => $user['FirstName'],
            'LastName' => $user['LastName'],
            'Email' => $user['UserName'],
            'Type' => (int)$user['Type'], 
            'is_admin' => ($user['Type'] == 1) 
        ];
        
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Wrong Password or E-Mail!";
        header("Location: site_login.php");
        exit();
    }
}