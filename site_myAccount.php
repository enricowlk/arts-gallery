<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: site_login.php");
    exit();
}

require_once 'CustomerRepository.php';
require_once 'database.php';

$customerRepo = new CustomerRepository(new Database());

$customerID = $_SESSION['user']['CustomerID'];
$customer = $customerRepo->getCustomerByID($customerID);

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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">My Account</h1>
        <?php if ($error){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <?php if ($success){ ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <form action="myAccount_process.php" method="POST">
            <div class="mb-3">
                <label for="firstName" class="form-label">Firstname:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $customer['FirstName']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Lastname:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $customer['LastName']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['Email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (optional):</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            </div>
            <button type="submit" class="btn btn-secondary">Update</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>