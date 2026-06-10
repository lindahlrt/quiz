<?php

// Connexion PDO — Singleton
class Database
{
    private static ?PDO $instance = null;


    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $env = parse_ini_file(RACINE . '/.env');
            $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $env['DB_USER'], $env['DB_PASS']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    // Empêcher l'instanciation directe
    private function __construct() {}
    private function __clone() {}
}
