<?php
session_start(); // Startet die Session

require_once 'customer.php'; // Bindet die Customer-Klasse ein
require_once 'customerRepository.php'; // Bindet das CustomerRepository ein
require_once 'database.php'; // Bindet die Datenbankverbindung ein

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; // Speichert die Fehlermeldung aus der Session
} else {
    $error = ''; // Setzt die Fehlermeldung auf leer, falls keine vorhanden ist
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success']; // Speichert die Erfolgsmeldung aus der Session
} else {
    $success = ''; // Setzt die Erfolgsmeldung auf leer, falls keine vorhanden ist
}
unset($_SESSION['error'], $_SESSION['success']); // Löscht die Session-Variablen
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
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigation ein -->

    <div class="container">
        <h1>Registration</h1>
        <?php if ($error){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div> <!-- Zeigt Fehlermeldungen an -->
        <?php } ?>
        <?php if ($success){ ?>
            <div class="alert alert-success"><?php echo $success; ?></div> <!-- Zeigt Erfolgsmeldungen an -->
        <?php } ?>
        <form method="POST" action="register_process.php">
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">Firstname</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Lastname</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" required>
            </div>
            <div class="mb-3">
                <label for="postal" class="form-label">Postal</label>
                <input type="text" class="form-control" id="postal" name="postal" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone (optional)</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <button type="submit" class="btn btn-secondary">Register</button> <!-- Submit-Button -->
        </form>
    </div>
    
    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>