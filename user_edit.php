<?php
session_start(); // Start the session

// Check if user is logged in and is an administrator
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); // Redirect to home page if not an admin
    exit();
}

require_once 'customerRepository.php'; // Include the CustomerRepository class
require_once 'database.php'; // Include the Database class

// Create an instance of CustomerRepository
$customerRepo = new CustomerRepository(new Database());

// Check if ID parameter exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: site_manage_users.php");
    exit();
}

// Get the user ID from the query parameter
$customerID = (int)$_GET['id'];

// Get the user data
$customer = $customerRepo->getCustomerByID($customerID);

// Check if user exists
if (!$customer) {
    $_SESSION['error'] = "User not found.";
    header("Location: site_manage_users.php");
    exit();
}

// Get user type (admin status)
$this_db = new Database();
$this_db->connect();
$sql = 'SELECT Type FROM customerlogon WHERE CustomerID = :customerID';
$stmt = $this_db->prepareStatement($sql);
$stmt->execute(['customerID' => $customerID]);
$userType = (int)$stmt->fetchColumn();
$this_db->close();

// Check if success message exists in session
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
} else {
    $success = '';
}

// Check if error message exists in session
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $error = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include navigation -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Edit User</h1>
            <a href="site_manage_users.php" class="btn btn-secondary">Back to Users</a>
        </div>
        
        <!-- Display success message -->
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
        <!-- Display error message -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- User edit form -->
        <form action="user_edit_process.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="customerID" value="<?php echo $customerID; ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $customer->getFirstName(); ?>" required>
                    <div class="invalid-feedback">
                        First name is required.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $customer->getLastName(); ?>" required>
                    <div class="invalid-feedback">
                        Last name is required.
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer->getEmail(); ?>" required>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $customer->getAddress(); ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?php echo $customer->getCity(); ?>">
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" value="<?php echo $customer->getCountry(); ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="postal" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal" name="postal" value="<?php echo $customer->getPostal(); ?>">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $customer->getPhone(); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">New Password (leave blank to keep current):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            </div>
            
            <div class="mb-3">
                <label for="userType" class="form-label">User Role:</label>
                <select class="form-select" id="userType" name="userType">
                    <option value="0" <?php echo ($userType == 0) ? 'selected' : ''; ?>>Regular User</option>
                    <option value="1" <?php echo ($userType == 1) ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-secondary">Save Changes</button>
        </form>
    </div>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>
    
    <!-- Include Bootstrap JS and form validation script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation script
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        // Check password matching if a new password is entered
                        let password = document.getElementById('password').value;
                        let confirmPassword = document.getElementById('confirmPassword').value;
                        
                        if (password !== '' && password !== confirmPassword) {
                            event.preventDefault();
                            event.stopPropagation();
                            alert('Passwords do not match');
                            return;
                        }
                        
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>