<?php
/**
 * Handles user logout functionality
 * 
 * This controller terminates user sessions and performs cleanup.
 * Implements the logout portion of Use Case 22 - Login as user.
 */

/**
 * Invalidate session cookie because of: https://chatgpt.com/s/t_685447f7062481919e5f82455020c539
 * Sets expiration to past time to ensure browser deletes it
 */
setcookie(session_name(), '', time() - 3600, '/');

/**
 * Destroy active session
 */
session_destroy();

/**
 * Redirect to homepage after logout
 * Prevents any further code execution
 */
header("Location: ../../index.php");
exit();
?>