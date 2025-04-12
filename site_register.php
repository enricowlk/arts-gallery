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
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" pattern="^[A-Za-zäöüÄÖÜß]+$">
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" pattern="^[A-Za-zäöüÄÖÜß]+$">
                </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" pattern="^[A-Za-zäöüÄÖÜß ]+$">
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" pattern="^[A-Za-zäöüÄÖÜß ]+$">
                </div>
            </div>
            
            <div class="row mb-3">
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
            
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
                <div class = "mt-3">
                <button type="submit" class="btn btn-secondary">Register</button> <!-- Submit-Button -->
                </div>
            </div>
        </form>
        </div>
    </div>
    
    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>