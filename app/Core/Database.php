<?php

namespace App\Core;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        require_once __DIR__ . '/../../config/database.php';

        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            // Em um ambiente de produção, é uma boa prática logar o erro
            // em vez de exibi-lo diretamente.
            error_log('PDO Connection Error: ' . $e->getMessage());
            // Exibe uma mensagem de erro genérica para o usuário.
            die('Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    private function __clone() { }

    public function __wakeup() { }
}
