<?php
/**
 * Error display page for the Art Gallery application.
 * 
 * Starts the session and retrieves an error message from the GET parameters.
 * If no message is provided, a default message is shown.
 */

session_start();

/**
 * Retrieve error message from GET parameters if available.
 *
 * @var string $errorMessage The error message to be displayed on the page.
 */
if (isset($_GET['message'])) {
    $errorMessage = $_GET['message'];
} else {
    $errorMessage = 'An unknown error occurred.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error - Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-container {
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <!-- Error display container -->
    <div class="container">
        <div class="error-container">
            <!-- Warning icon (Bootstrap icon SVG) -->
            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            </div>
            <!-- Error heading and message -->
            <h1 class="mb-4">Oops! Something went wrong.</h1>
            <p class="lead text-muted mb-4"><?php echo $errorMessage; ?></p>

            <!-- Navigation buttons -->
            <div class="d-flex gap-3">
                <a href="/arts-gallery/index.php" class="btn btn-secondary">Go to Homepage</a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary">Go Back</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
