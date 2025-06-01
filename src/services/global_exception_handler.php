<?php

class GlobalExceptionHandler {
    public static function register() {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(Throwable $exception) {
        self::redirectToErrorPage($exception->getMessage());
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (error_reporting() === 0) return false;

        if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
            self::redirectToErrorPage($errstr);
        }

        return true;
    }

    public static function handleShutdown() {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::redirectToErrorPage($error['message']);
        }
    }

    private static function redirectToErrorPage($message) {
        // URL-encode die Fehlermeldung für GET-Parameter
        $encodedMessage = urlencode($message);

        // Relativer Pfad zur error.php
        $errorPage = '/arts-gallery/error.php';

        // Header nur setzen wenn noch nicht gesendet
        if (!headers_sent()) {
            header("HTTP/1.1 500 Internal Server Error");
            header("Location: $errorPage?message=$encodedMessage");
        } else {
            // Fallback wenn Header schon gesendet wurden
            echo "<script>window.location.href = '$errorPage?message=$encodedMessage';</script>";
        }

        exit();
    }
}

// Handler automatisch registrieren
GlobalExceptionHandler::register();
?>
