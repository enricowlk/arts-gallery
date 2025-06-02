<?php
session_start();

// Überprüfen ob User eingeloggt ist und Admin-Rechte hat (Type = 1)
if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] != 1) {
    header("Location: index.php"); // Wenn nicht, zur Startseite umleiten
    exit();
}

// Required-Dateien einbinden
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../repositories/customerRepository.php'; 
require_once __DIR__ . '/../../config/database.php'; 

// Customer Repository instanziieren
$customerRepo = new CustomerRepository(new Database());

// Alle User aus der Datenbank holen
$users = $customerRepo->getAllCustomers();

// Erfolgsmeldung aus Session holen und löschen
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
} else {
    $success = '';
}

// Fehlermeldung aus Session holen und löschen
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $error = '';
}

// Anzahl der Admins zählen
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">Manage Users</h1>
        
        <!-- Erfolgsmeldung anzeigen, falls vorhanden -->
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
        <!-- Fehlermeldung anzeigen, falls vorhanden -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Responsive Tabelle für User-Darstellung -->
        <div class="table-responsive">
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
                            <!-- User-Daten anzeigen -->
                            <td><?php echo $user['CustomerID']; ?></td>
                            <td><?php echo $user['FirstName']; ?></td>
                            <td><?php echo $user['LastName']; ?></td>
                            <td><?php echo $user['Email']; ?></td>
                            <td>
                            <?php 
                                // Rolle anzeigen (Admin oder normaler User)
                                if ($user['Type'] == 1) {
                                    echo 'Administrator';
                                } else {
                                    echo 'User';
                                }
                            ?>
                            </td>
                            <td>
                                <!-- Edit-Button für jeden User -->
                                <a href="site_user_edit.php?id=<?php echo $user['CustomerID']; ?>" class="btn btn-primary">Edit</a>
                                
                                <?php if ($_SESSION['user']['CustomerID'] != $user['CustomerID']) { ?>
                                    <!-- Admin-Rechte vergeben/entziehen -->
                                    <?php if ($user['Type'] == 0) { ?>
                                        <a href="../controllers/user_role_process.php?id=<?php echo $user['CustomerID']; ?>&action=promote" class="btn btn-success">Make Admin</a>
                                    <?php } else if ($adminCount > 1) { ?>
                                        <!-- Nur entziehen wenn mind. ein anderer Admin existiert -->
                                        <a href="../controllers/user_role_process.php?id=<?php echo $user['CustomerID']; ?>&action=demote" class="btn btn-warning">Remove Admin</a>
                                    <?php } ?>
                                    
                                    <!-- User (de)aktivieren -->
                                    <?php if (strpos($user['Email'], 'INACTIVE_') === 0) { ?>
                                        <a href="../controllers/user_status_process.php?id=<?php echo $user['CustomerID']; ?>&action=reactivate" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to reactivate this user?')">Reactivate</a>
                                    <?php } else { ?>
                                        <a href="../controllers/user_status_process.php?id=<?php echo $user['CustomerID']; ?>&action=deactivate" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">Deactivate</a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <!-- Keine Aktionen für aktuellen User -->
                                    <span class="text-muted">(Current user)</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>