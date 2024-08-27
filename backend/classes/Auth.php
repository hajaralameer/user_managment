<?php

class Auth {
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    public static function getUserID() {
        return $_SESSION['user_id'];
    }

    public static function login($user_id) {
        $_SESSION['user_id'] = $user_id;
    }

    public static function logout() {
        session_destroy();
    }
}
?>
