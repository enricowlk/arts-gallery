<?php
// Zentrale Klasse zur Behandlung aller unerwarteten Fehler und Exceptions
class GlobalExceptionHandler {
    
    // Registriert alle Handler beim Aufruf
    public static function register() {
        set_exception_handler([self::class, 'handleException']);  // Für Exceptions
        set_error_handler([self::class, 'handleError']);         // Für PHP-Fehler
        register_shutdown_function([self::class, 'handleShutdown']); // Für Fatal Errors
    }

    // Behandlung von ungefangenen Exceptions
    public static function handleException(Throwable $exception) {
        self::redirectToErrorPage($exception->getMessage());  // Weiterleitung mit Fehlermeldung
    }

    // Behandlung von PHP-Fehlern
    public static function handleError($errno, $errstr, $errfile, $errline) {
        // Ignoriere @-unterdrückte Fehler
        if (error_reporting() === 0) return false;

        // Nur schwere Fehler weiterleiten
        if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
            self::redirectToErrorPage($errstr);
        }

        return true;  // Andere Fehler werden normal behandelt
    }

    // Behandlung von fatalen Shutdown-Fehlern
    public static function handleShutdown() {
        $error = error_get_last();  // Holt letzten Fehler
        // Prüft auf fatale Fehler
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::redirectToErrorPage($error['message']);
        }
    }

    // Private Hilfsmethode für die Weiterleitung
    private static function redirectToErrorPage($message) {
        $encodedMessage = urlencode($message);  // URL-kodierte Fehlermeldung
        $errorPage = '../views/components/error.php'; // Pfad zur Fehlerseite

        // Header-basierte Weiterleitung wenn möglich
        if (!headers_sent()) {
            header("HTTP/1.1 500 Internal Server Error");  // HTTP Statuscode
            header("Location: $errorPage?message=$encodedMessage");
        } else {
            // JavaScript-Fallback wenn Header schon gesendet
            echo "<script>window.location.href = '$errorPage?message=$encodedMessage';</script>";
        }

        exit();  // Skriptausführung beenden
    }
}

// Automatische Registrierung beim Einbinden der Datei
GlobalExceptionHandler::register();
?>