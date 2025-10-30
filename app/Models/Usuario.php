<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Usuario extends Model
{
    /**
     * Busca todos os usuários cadastrados no banco de dados.
     *
     * @return array Lista de usuários.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM Usuarios ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encontra um usuário pelo ID.
     *
     * @param int $id O ID do usuário.
     * @return mixed Retorna os dados do usuário se encontrado, ou false caso contrário.
     */
    public function find($id)
    {
        $sql = "SELECT * FROM Usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo usuário no banco de dados.
     *
     * @param array $dados Dados do usuário (nome, email, telefone, senha_hash, tipo_usuario).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($dados)
    {
        $sql = "INSERT INTO Usuarios (nome, email, telefone, senha_hash, tipo_usuario)
                VALUES (:nome, :email, :telefone, :senha_hash, :tipo_usuario)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->bindParam(':telefone', $dados['telefone']);
        $stmt->bindParam(':senha_hash', $dados['senha_hash']);
        $stmt->bindParam(':tipo_usuario', $dados['tipo_usuario']);

        return $stmt->execute();
    }

    /**
     * Atualiza um usuário existente no banco de dados.
     *
     * @param int $id O ID do usuário a ser atualizado.
     * @param array $dados Novos dados do usuário (nome, email, telefone, tipo_usuario).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados)
    {
        $sql = "UPDATE Usuarios SET nome = :nome, email = :email, telefone = :telefone, tipo_usuario = :tipo_usuario
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->bindParam(':telefone', $dados['telefone']);
        $stmt->bindParam(':tipo_usuario', $dados['tipo_usuario']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Atualiza a senha de um usuário.
     *
     * @param int $id O ID do usuário.
     * @param string $senhaHash A nova senha hash.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function updatePassword($id, $senhaHash)
    {
        $sql = "UPDATE Usuarios SET senha_hash = :senha_hash WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':senha_hash', $senhaHash);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

        /**

         * Deleta um usuário do banco de dados.

         *

         * @param int $id O ID do usuário a ser deletado.

         * @return bool Retorna true em caso de sucesso, false em caso de falha.

         */

        public function delete($id)

        {

            $sql = "DELETE FROM Usuarios WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();

        }

    

        /**

         * Encontra um usuário pelo endereço de e-mail.

         *

         * @param string $email O endereço de e-mail do usuário.

         * @return mixed Retorna os dados do usuário se encontrado, ou false caso contrário.

         */

        public function findByEmail($email)

        {

            $sql = "SELECT * FROM Usuarios WHERE email = :email";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        }

    }

    