<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        require_once __DIR__ . '/../../config/database.php';

        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em um ambiente de produção, você não deve expor detalhes do erro.
            // Logue o erro e mostre uma mensagem genérica.
            error_log($e->getMessage());
            die('Erro ao conectar ao banco de dados. Verifique as configurações e tente novamente.');
        }
    }

    /**
     * Pega a instância única da conexão com o banco de dados (Singleton Pattern).
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Retorna o objeto PDO da conexão.
     */
    public function getConnection() {
        return $this->pdo;
    }

    // Previne que a instância seja clonada.
    private function __clone() { }

    // Previne a desserialização da instância.
    public function __wakeup() { }
}
