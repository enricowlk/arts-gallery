<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php");
    exit();
}

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'customerRepository.php';
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: site_manage_users.php");
    exit();
}

$customerID = (int)$_GET['id'];
$customerRepo = new CustomerRepository(new Database());
$customer = $customerRepo->getCustomerByID($customerID);
$userType = $customerRepo->getUserRole($customerID);

if (!$customer) {
    $_SESSION['error'] = "User not found.";
    header("Location: site_manage_users.php");
    exit();
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
} else {
    $success = '';
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Edit User</h1>
            <a href="site_manage_users.php" class="btn btn-secondary">Back to Users</a>
        </div>
        
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <form action="user_edit_process.php" method="POST">
            <input type="hidden" name="customerID" value="<?php echo $customerID; ?>">
            
            <div class="row mb-3">
            <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" pattern="^[A-Za-zäöüÄÖÜß]+$"
                           value="<?php echo $customer->getFirstName(); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" pattern="^[A-Za-zäöüÄÖÜß]+$"
                           value="<?php echo $customer->getLastName(); ?>" required>
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
                           value="<?php echo $customer->getAddress(); ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" pattern="^[A-Za-zäöüÄÖÜß ]+$"
                           value="<?php echo $customer->getCity(); ?>">
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" pattern="^[A-Za-zäöüÄÖÜß ]+$"
                           value="<?php echo $customer->getCountry(); ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="postal" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal" name="postal" pattern="^[0-9]+$"
                           value="<?php echo $customer->getPostal(); ?>">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="<?php echo $customer->getPhone(); ?>">
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
            
            <div class="mb-3">
                <label for="userType" class="form-label">User Role:</label>
                <select class="form-select" id="userType" name="userType" required>
                    <option value="0" <?php echo ($userType == 0) ? 'selected' : ''; ?>>Regular User</option>
                    <option value="1" <?php echo ($userType == 1) ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-secondary">Save Changes</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>