<?php
namespace core\app;

/**
 * 
 * 
 */
class Session {
    public static function start() {
        session_start();
    }

    public static function createAuth(array $values) {
        $_SESSION["auth"] = $values;
    }

    public static function destroy() {
        if(!isset($_SESSION["auth"])) return;
        session_destroy();
        unset($_SESSION);
    }

    public static function isAuth() {
        return isset($_SESSION["auth"]);
    }

    public static function getAuthInfo() {
        if(self::isAuth())
            return $_SESSION["auth"];
        return false;
    }
}