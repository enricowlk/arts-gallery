<?php
// Session-Cookie löschen, weil https://chatgpt.com/c/683d5c5e-14ac-8013-8fdf-dabb08b4609f
setcookie(session_name(), '', time() - 3600, '/');

// Aktive Session zerstören
session_destroy();

// Zur Startseite weiterleiten
header("Location: ../../index.php");
exit();