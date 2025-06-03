<?php
session_start(); 

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php"); // If not, redirect to login
    exit();
}

// Include required files
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php'; 

// Instantiate Customer Repository
$customerRepo = new CustomerRepository(new Database());

// Get current user from session and load data from DB
$customerID = $_SESSION['user']['CustomerID']; 
$customer = $customerRepo->getCustomerByID($customerID); 

// Get error message from session
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
} else {
    $error = ''; 
}

// Get success message from session
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
} else {
    $success = ''; 
}

// Clear messages from session after reading
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">My Account</h1>
        
        <!-- Display error message if present -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        
        <!-- Display success message if present -->
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <!-- Form for account data + input validation with regex pattern -->
        <form action="../controllers/user_edit_process.php" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" 
                           pattern="^[A-Za-zäöüÄÖÜß]+$" value="<?php echo $customer->getFirstName(); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" 
                           pattern="^[A-Za-zäöüÄÖÜß]+$" value="<?php echo $customer->getLastName(); ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo $customer->getEmail(); ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" 
                           value="<?php echo $customer->getAddress(); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" 
                           pattern="^[A-Za-zäöüÄÖÜß ]+$" value="<?php echo $customer->getCity(); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" 
                           pattern="^[A-Za-zäöüÄÖÜß ]+$" value="<?php echo $customer->getCountry(); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="postal" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal" name="postal" 
                           pattern="^[0-9]+$" value="<?php echo $customer->getPostal(); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           pattern="^\+?[0-9 ()-]+$" value="<?php echo $customer->getPhone(); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">New Password:</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>
            
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            </div>
            
            <button type="submit" class="btn btn-secondary mb-3">Save Changes</button>
        </form>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
