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

// Get all customers from the database
$users = $customerRepo->getAllCustomers();

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

// Count the number of administrators
$adminCount = 0;
foreach ($users as $user) {
    if ($user['Type'] == 1) {
        $adminCount++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include navigation -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">Manage Users</h1>
        
        <!-- Display success message -->
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
        <!-- Display error message -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Users table -->
        <div class="table-responsive mt-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['CustomerID']; ?></td>
                            <td><?php echo htmlspecialchars($user['FirstName']); ?></td>
                            <td><?php echo htmlspecialchars($user['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($user['Email']); ?></td>
                            <td>
                                <?php echo ($user['Type'] == 1) ? 'Administrator' : 'User'; ?>
                            </td>
                            <td>
                                <a href="user_edit.php?id=<?php echo $user['CustomerID']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                
                                <?php if ($_SESSION['user']['CustomerID'] != $user['CustomerID']) { ?>
                                    <?php if ($user['Type'] == 0) { ?>
                                        <a href="user_role_process.php?id=<?php echo $user['CustomerID']; ?>&action=promote" class="btn btn-sm btn-success">Make Admin</a>
                                    <?php } else if ($adminCount > 1) { ?>
                                        <a href="user_role_process.php?id=<?php echo $user['CustomerID']; ?>&action=demote" class="btn btn-sm btn-warning">Remove Admin</a>
                                    <?php } ?>
                                    
                                    <?php if (strpos($user['Email'], 'INACTIVE_') === 0) { ?>
                                        <a href="user_status_process.php?id=<?php echo $user['CustomerID']; ?>&action=reactivate" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to reactivate this user?')">Reactivate</a>
                                    <?php } else { ?>
                                        <a href="user_status_process.php?id=<?php echo $user['CustomerID']; ?>&action=deactivate" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">Deactivate</a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="text-muted">(Current user)</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>