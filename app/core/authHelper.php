<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

class AuthHelper
{
    public static function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin()
    {
        if (!self::isAuthenticated()) {
            
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

            $_SESSION['error'] = "Anda harus login untuk mengakses halaman ini.";
            header("Location: /login");
            exit;
        }
    }

    public static function redirectIfAuthenticated($redirectTo = '/')
    {
        if (self::isAuthenticated()) {
            header("Location: " . $redirectTo);
            exit;
        }
    }
}
