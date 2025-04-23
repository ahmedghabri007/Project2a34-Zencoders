<?php
require_once __DIR__ . '/../config.php';

class AuthController {
    // No database connection needed for hardcoded login
    public function __construct() {
        // No initialization required
    }

    public function login($username, $password) {
        // For demo purposes, using hardcoded credentials
        $validUsername = 'admin';
        $validPassword = 'admin123';

        if ($username === $validUsername && $password === $validPassword) {
            $_SESSION['admin'] = true;
            return true;
        }
        return false;
    }

    public function logout() {
        session_start();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        session_regenerate_id(true);
        header('Location: /project-2a34/view/login.php');
        exit();
    }

    public function isAdmin() {
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }
}
?>
