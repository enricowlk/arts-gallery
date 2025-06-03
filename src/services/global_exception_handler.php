<?php
/**
 * Central class for handling all unexpected errors and exceptions.
 * Registers custom handlers for exceptions, PHP errors, and fatal shutdown errors.
 */
class GlobalExceptionHandler {

    /**
     * Registers all custom error and exception handlers.
     *
     * This method sets up handlers for:
     * - Uncaught exceptions
     * - PHP runtime errors
     * - Fatal shutdown errors
     *
     * @return void
     */
    public static function register() {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Handles uncaught exceptions.
     *
     * @param Throwable $exception The uncaught exception
     * @return void
     */
    public static function handleException(Throwable $exception) {
        self::redirectToErrorPage($exception->getMessage());
    }

    /**
     * Handles PHP runtime errors.
     *
     * @param int $errno The level of the error raised
     * @param string $errstr The error message
     * @param string $errfile The filename that the error was raised in
     * @param int $errline The line number the error was raised at
     * @return bool Whether the error was handled
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (error_reporting() === 0) return false;

        if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
            self::redirectToErrorPage($errstr);
        }

        return true;
    }

    /**
     * Handles fatal shutdown errors (e.g., out of memory, syntax errors).
     *
     * @return void
     */
    public static function handleShutdown() {
        $error = error_get_last();

        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::redirectToErrorPage($error['message']);
        }
    }

    /**
     * Redirects the user to a generic error page with a message.
     *
     * If headers have not been sent, performs a server-side redirect.
     * If headers have already been sent, uses a JavaScript-based redirect as fallback.
     *
     * @param string $message Error message to display on the error page
     * @return void
     */
    private static function redirectToErrorPage($message) {
        $encodedMessage = urlencode($message);
        $errorPage = '../views/components/error.php';

        if (!headers_sent()) {
            header("HTTP/1.1 500 Internal Server Error");
            header("Location: $errorPage?message=$encodedMessage");
        } else {
            echo "<script>window.location.href = '$errorPage?message=$encodedMessage';</script>";
        }

        exit();
    }
}

// Automatically register the error handlers when the file is included
GlobalExceptionHandler::register();
