<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Produto extends Model
{
    /**
     * Busca todos os produtos cadastrados no banco de dados.
     *
     * @return array Lista de produtos.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM Produtos ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encontra um produto pelo ID.
     *
     * @param int $id O ID do produto.
     * @return mixed Retorna os dados do produto se encontrado, ou false caso contrário.
     */
    public function find($id)
    {
        $sql = "SELECT * FROM Produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo produto no banco de dados.
     *
     * @param array $dados Dados do produto (nome, descricao, imagem_arquivo, tipo_unidade, disponivel_para_orcamento).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($dados)
    {
        $sql = "INSERT INTO Produtos (nome, descricao, imagem_arquivo, tipo_unidade, disponivel_para_orcamento)
                VALUES (:nome, :descricao, :imagem_arquivo, :tipo_unidade, :disponivel_para_orcamento)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':imagem_arquivo', $dados['imagem_arquivo']);
        $stmt->bindParam(':tipo_unidade', $dados['tipo_unidade']);
        $stmt->bindParam(':disponivel_para_orcamento', $dados['disponivel_para_orcamento'], PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    /**
     * Atualiza um produto existente no banco de dados.
     *
     * @param int $id O ID do produto a ser atualizado.
     * @param array $dados Novos dados do produto.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados)
    {
        $sql = "UPDATE Produtos SET nome = :nome, descricao = :descricao, imagem_arquivo = :imagem_arquivo,
                tipo_unidade = :tipo_unidade, disponivel_para_orcamento = :disponivel_para_orcamento
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':imagem_arquivo', $dados['imagem_arquivo']);
        $stmt->bindParam(':tipo_unidade', $dados['tipo_unidade']);
        $stmt->bindParam(':disponivel_para_orcamento', $dados['disponivel_para_orcamento'], PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deleta um produto do banco de dados.
     *
     * @param int $id O ID do produto a ser deletado.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM Produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
