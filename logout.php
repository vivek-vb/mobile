<?php
// Include the configuration to access the session_start() usually found there
require_once 'includes/config.php';

// 1. Unset all session variables
$_SESSION = array();

// 2. If you want to kill the session cookie as well
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session
session_destroy();

// 4. Redirect to the login page
header('Location: index.php');
exit();
?>