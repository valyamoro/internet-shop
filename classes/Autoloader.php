<?php
class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            include_once __DIR__ . '/' . $class . '.php';
        });
    }
}