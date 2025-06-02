<?php
// Session starten - notwendig für Session-Variablen
session_start();

// Überprüfen ob User eingeloggt ist und Admin-Rechte hat (Type=1)
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php");
    exit();
}

// Einbinden benötigter Dateien
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php'; 

// Überprüfen ob eine gültige ID als GET-Parameter übergeben wurde
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: site_manage_users.php");
    exit();
}

// Kunden-ID aus GET-Parameter holen und in Integer umwandeln
$customerID = (int)$_GET['id'];
// CustomerRepository instanziieren mit Datenbankverbindung
$customerRepo = new CustomerRepository(new Database());
// Kunden-Datensatz anhand der ID holen
$customer = $customerRepo->getCustomerByID($customerID);
// Benutzerrolle des Kunden holen
$userType = $customerRepo->getUserRole($customerID);

// Überprüfen ob Customer existiert
if (!$customer) {
    $_SESSION['error'] = "User not found.";
    header("Location: site_manage_users.php");
    exit();
}

// Erfolgsmeldungen aus Session holen und aus Session löschen
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
} else {
    $success = '';
}

// Fehlermeldungen aus Session holen und aus Session löschen
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
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Edit User</h1>
            <a href="site_manage_users.php" class="btn btn-secondary">Back to Users</a>
        </div>
        
        <!-- Erfolgsmeldung anzeigen falls vorhanden -->
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
        <!-- Fehlermeldung anzeigen falls vorhanden -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Formular zum Bearbeiten des Benutzers + Eingabevalidierung durch Regex pattern  -->
        <form action="../controllers/user_edit_process.php" method="POST">
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
                           value="<?php echo $customer->getAddress(); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" pattern="^[A-Za-zäöüÄÖÜß ]+$"
                           value="<?php echo $customer->getCity(); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" pattern="^[A-Za-zäöüÄÖÜß ]+$"
                           value="<?php echo $customer->getCountry(); ?>" required>
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
                    <input type="text" class="form-control" id="phone" name="phone" pattern="^\+?[0-9 ]+$"
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
            
            <!-- Dropdown für Benutzerrolle -->
            <div class="mb-3">
                <label for="userType" class="form-label">User Role:</label>
                <select class="form-select" id="userType" name="userType" required>
                    <option value="0" <?php echo ($userType == 0) ? 'selected' : ''; ?>>Regular User</option>
                    <option value="1" <?php echo ($userType == 1) ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary mb-3">Save Changes</button>
        </form>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>