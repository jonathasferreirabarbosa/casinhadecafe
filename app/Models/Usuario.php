<?php

namespace App\Models;

class Usuario extends \App\Core\Model {
    
    protected $tabela = 'Usuarios';

    /**
     * Encontra um usuário pelo e-mail.
     *
     * @param string $email O e-mail do usuário.
     * @return mixed Retorna os dados do usuário se encontrado, ou false caso contrário.
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->tabela} WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Cria um novo usuário no banco de dados.
     *
     * @param array $dados Dados do usuário (nome, email, telefone, senha_hash).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($dados) {
        // Opcional: Adicionar validação de dados aqui antes de inserir.

        $sql = "INSERT INTO {$this->tabela} (nome, email, telefone, senha_hash, tipo_usuario)
                VALUES (:nome, :email, :telefone, :senha_hash, :tipo_usuario)";
        
        $stmt = $this->pdo->prepare($sql);

        // Define o tipo de usuário padrão como 'cliente' se não for especificado.
        $tipo_usuario = $dados['tipo_usuario'] ?? 'cliente';

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->bindParam(':telefone', $dados['telefone']);
        $stmt->bindParam(':senha_hash', $dados['senha_hash']);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);

        return $stmt->execute();
    }

    // Você pode adicionar outros métodos aqui, como:
    // - update() para atualizar os dados de um usuário.
    // - findById() para buscar um usuário pelo ID.
    // - delete() para remover um usuário.
}
