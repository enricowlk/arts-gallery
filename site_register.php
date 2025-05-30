<?php
session_start(); 

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'customer.php'; 
require_once 'customerRepository.php'; 
require_once 'database.php'; 

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; 
} else {
    $error = '';
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
} else {
    $success = '';
}
unset($_SESSION['error'], $_SESSION['success']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> 

    <div class="container mt-3">
        <h1 class="text-center">Registration</h1>
        <?php if ($error){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div> 
        <?php } ?>
        <?php if ($success){ ?>
            <div class="alert alert-success"><?php echo $success; ?></div> 
        <?php } ?>
        <form method="POST" action="register_process.php">
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" pattern="^[A-Za-zäöüÄÖÜß]+$" required>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" pattern="^[A-Za-zäöüÄÖÜß]+$" required>
                </div>
            
            <div class="mt-2">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" pattern="^[A-Za-zäöüÄÖÜß ]+$" required>
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" pattern="^[A-Za-zäöüÄÖÜß ]+$" required>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="postal" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal" name="postal" pattern="^[0-9]+$">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                    <small class="text-muted">(Optional)</small>
                </div>
            </div>
            
            <div>
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
                <div class = "mt-3">
                <button type="submit" class="btn btn-secondary">Register</button> 
                </div>
            </div>
        </form>
        </div>
    </div>
    
    <?php include 'footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>