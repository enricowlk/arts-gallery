<?php
session_start(); 

// Required-Dateien einbinden
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../entitys/customer.php'; 
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php'; 

// Fehlermeldung aus Session holen
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; 
} else {
    $error = '';
}

// Erfolgsmeldung aus Session holen
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
} else {
    $success = '';
}

// Meldungen aus Session löschen nach dem Auslesen
unset($_SESSION['error'], $_SESSION['success']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?> 

    <div class="container mt-3">
        <h1 class="text-center">Registration</h1>
        
        <!-- Fehlermeldung anzeigen, falls vorhanden -->
        <?php if ($error){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div> 
        <?php } ?>
        
        <!-- Erfolgsmeldung anzeigen, falls vorhanden -->
        <?php if ($success){ ?>
            <div class="alert alert-success"><?php echo $success; ?></div> 
        <?php } ?>
        
        <!-- Registrierungsformular + Eingabevalidierung mit Regex pattern -->
        <form method="POST" action="../controllers/register_process.php">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" 
                           pattern="^[A-Za-zäöüÄÖÜß]+$" required>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" 
                           pattern="^[A-Za-zäöüÄÖÜß]+$" required>
                </div>
            </div>
            
            <div class="mt-2">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" 
                           pattern="^[A-Za-zäöüÄÖÜß ]+$" required>
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" 
                           pattern="^[A-Za-zäöüÄÖÜß ]+$" required>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="postal" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal" name="postal" 
                           pattern="^[0-9]+$">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone" pattern="^\+?[0-9 ]+$">
                    <small class="text-muted">(Optional)</small>
                </div>
            </div>
            
            <div>
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-secondary">Register</button> 
            </div>
        </form>
    </div>
    
    <?php include __DIR__ . '/components/footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>