<?php
session_start();
// $_SESSION['logout_message'] = "You have been logged out successfully.";
// session_write_close(); // Asigură-te că mesajul este scris în sesiune înainte de a continua
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: ../login.php");
exit();
?>
