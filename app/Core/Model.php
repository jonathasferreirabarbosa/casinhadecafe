<?php

namespace App\Core;

abstract class Model {
    protected $pdo;

    public function __construct() {
        // Obtém a instância da conexão PDO e a armazena na propriedade da classe.
        $this->pdo = Database::getInstance()->getConnection();
    }

    // No futuro, podemos adicionar métodos comuns a todos os models aqui,
    // como find(), all(), save(), delete(), etc.
}
