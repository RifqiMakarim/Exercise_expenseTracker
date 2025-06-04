<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Csrf
{
    private static $tokenName = 'csrf_token';
    
    public static function generateToken()
    {
        if (empty($_SESSION[self::$tokenName])) {
            $_SESSION[self::$tokenName] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::$tokenName];
    }
    
    public static function validateToken($token)
    {
        if (isset($_SESSION[self::$tokenName]) && hash_equals($_SESSION[self::$tokenName], $token)) {
            // unset($_SESSION[self::$tokenName]);
            return true;
        }
        return false;
    }
    
    public static function csrfField()
    {
        $token = self::generateToken();
        return "<input type=\"hidden\" name=\"" . self::$tokenName . "\" value=\"" . $token . "\">";
    }
}
