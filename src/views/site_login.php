<?php
session_start();

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; 
} else {
    $error = ''; 
}
unset($_SESSION['error']);

require_once 'global_exception_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-4">
        <h1 class="text-center">Login</h1>

        <?php if ($error){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <form action="login_process.php" method="POST">
            <div>
                <label for="username" class="form-label">E-Mail:</label>
                <input type="email" class="form-control" id="username" name="username" required>
            </div>
            <div class="mt-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-secondary mt-2">Login</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>