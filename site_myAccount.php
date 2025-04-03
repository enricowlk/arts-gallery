<?php
session_start(); // Startet die Session

// Überprüft, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user'])) {
    header("Location: site_login.php"); // Weiterleitung zur Login-Seite, wenn nicht angemeldet
    exit();
}

require_once 'customerRepository.php'; // Bindet die CustomerRepository-Klasse ein
require_once 'database.php'; // Bindet die Database-Klasse ein

// Erstellt eine Instanz von CustomerRepository
$customerRepo = new CustomerRepository(new Database());

// Holt die Kundendaten aus der Session
$customerID = $_SESSION['user']['CustomerID']; // CustomerID aus der Session
$customer = $customerRepo->getCustomerByID($customerID); // Holt die Kundendaten anhand der ID

// Überprüft, ob eine Fehlermeldung in der Session gespeichert ist
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; // Holt die Fehlermeldung
} else {
    $error = ''; // Falls keine Fehlermeldung vorhanden ist
}

// Überprüft, ob eine Erfolgsmeldung in der Session gespeichert ist
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success']; // Holt die Erfolgsmeldung
} else {
    $success = ''; // Falls keine Erfolgsmeldung vorhanden ist
}

// Löscht die Fehler- und Erfolgsmeldungen aus der Session
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <!-- Bindet Bootstrap CSS ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bindet die benutzerdefinierte CSS-Datei ein -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation einbinden -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">My Account</h1>
        <!-- Fehlermeldung anzeigen -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <!-- Erfolgsmeldung anzeigen -->
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <!-- Formular zum Aktualisieren der Kundendaten -->
        <form action="myAccount_process.php" method="POST">
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" 
                           value="<?php echo $customer->getFirstName(); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" 
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
                    <input type="text" class="form-control" id="city" name="city" 
                           value="<?php echo $customer->getCity(); ?>">
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" 
                           value="<?php echo $customer->getCountry(); ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="postal" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal" name="postal" 
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
            <button type="submit" class="btn btn-secondary">Save Changes</button>
        </form>
    </div>

    <!-- Footer einbinden -->
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS einbinden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>