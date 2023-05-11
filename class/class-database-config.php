<?php
class DatabaseConfig
{
    public static $host = 'localhost';
    public static $dbName = 'pdoKlev';
    public static $username = 'root';
    public static $password = '';
    public static $charset = 'utf8mb4';
    public static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
}
