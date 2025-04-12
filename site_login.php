<?php
session_start(); // Startet die Session

// Überprüft, ob eine Fehlermeldung in der Session gespeichert ist
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; // Holt die Fehlermeldung
} else {
    $error = ''; // Falls keine Fehlermeldung vorhanden ist
}
unset($_SESSION['error']); // Löscht die Fehlermeldung aus der Session

require_once 'logging.php';
require_once 'global_exception_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Überschrift -->
        <h1 class="text-center">Login</h1>

        <!-- Fehlermeldung anzeigen -->
        <?php if ($error){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Login-Formular -->
        <form action="login_process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">E-Mail:</label>
                <input type="email" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-secondary">Login</button>
        </form>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>