<?php
// Alle Session-Variablen löschen
$_SESSION = [];

// Session zerstören
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Weiterleitung zur Startseite
header("Location: index.php"); 
exit();
?>