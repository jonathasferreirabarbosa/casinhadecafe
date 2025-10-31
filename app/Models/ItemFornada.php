<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ItemFornada extends Model
{
    /**
     * Busca todos os itens de uma fornada específica.
     *
     * @param int $fornadaId O ID da fornada.
     * @return array Lista de itens da fornada.
     */
    public function getByFornadaId($fornadaId)
    {
        $sql = "SELECT ifo.*, p.nome as produto_nome, p.tipo_unidade
                FROM Itens_Fornada ifo
                JOIN Produtos p ON ifo.produto_id = p.id
                WHERE ifo.fornada_id = :fornada_id
                ORDER BY p.nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':fornada_id', $fornadaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encontra um item de fornada pelo ID.
     *
     * @param int $id O ID do item da fornada.
     * @return mixed Retorna os dados do item se encontrado, ou false caso contrário.
     */
    public function find($id)
    {
        $sql = "SELECT * FROM Itens_Fornada WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica se um produto já existe em uma fornada.
     *
     * @param int $fornadaId O ID da fornada.
     * @param int $produtoId O ID do produto.
     * @return bool
     */
    public function exists($fornadaId, $produtoId)
    {
        $sql = "SELECT COUNT(*) FROM Itens_Fornada WHERE fornada_id = :fornada_id AND produto_id = :produto_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':fornada_id', $fornadaId, PDO::PARAM_INT);
        $stmt->bindParam(':produto_id', $produtoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Cria um novo item de fornada.
     *
     * @param array $dados Dados do item (fornada_id, produto_id, preco_unitario, estoque_inicial, estoque_atual).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($dados)
    {
        $sql = "INSERT INTO Itens_Fornada (fornada_id, produto_id, preco_unitario, estoque_inicial, estoque_atual)
                VALUES (:fornada_id, :produto_id, :preco_unitario, :estoque_inicial, :estoque_atual)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':fornada_id', $dados['fornada_id'], PDO::PARAM_INT);
        $stmt->bindParam(':produto_id', $dados['produto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':preco_unitario', $dados['preco_unitario']);
        $stmt->bindParam(':estoque_inicial', $dados['estoque_inicial'], PDO::PARAM_INT);
        $stmt->bindParam(':estoque_atual', $dados['estoque_atual'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Atualiza um item de fornada existente.
     *
     * @param int $id O ID do item da fornada a ser atualizado.
     * @param array $dados Novos dados do item (preco_unitario, estoque_inicial, estoque_atual).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados)
    {
        $sql = "UPDATE Itens_Fornada SET preco_unitario = :preco_unitario,
                estoque_inicial = :estoque_inicial, estoque_atual = :estoque_atual
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':preco_unitario', $dados['preco_unitario']);
        $stmt->bindParam(':estoque_inicial', $dados['estoque_inicial'], PDO::PARAM_INT);
        $stmt->bindParam(':estoque_atual', $dados['estoque_atual'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deleta um item de fornada do banco de dados.
     *
     * @param int $id O ID do item da fornada a ser deletado.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM Itens_Fornada WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Busca os detalhes de múltiplos itens de fornada a partir de uma lista de IDs.
     *
     * @param array $ids Lista de IDs dos itens de fornada.
     * @return array
     */
    public function getItensComDetalhes(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $inQuery = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT
                ifo.id AS item_fornada_id,
                p.nome AS produto_nome,
                p.tipo_unidade,
                ifo.preco_unitario,
                ifo.estoque_atual
            FROM Itens_Fornada AS ifo
            JOIN Produtos AS p ON ifo.produto_id = p.id
            WHERE ifo.id IN ($inQuery);
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Reduz o estoque de um item da fornada.
     *
     * @param int $id O ID do item da fornada.
     * @param int $quantidade A quantidade a ser reduzida.
     * @return bool
     */
    public function reduzirEstoque($id, $quantidade)
    {
        $sql = "UPDATE Itens_Fornada SET estoque_atual = estoque_atual - :quantidade WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Aumenta o estoque de um item da fornada.
     *
     * @param int $id O ID do item da fornada.
     * @param int $quantidade A quantidade a ser aumentada.
     * @return bool
     */
    public function aumentarEstoque($id, $quantidade)
    {
        $sql = "UPDATE Itens_Fornada SET estoque_atual = estoque_atual + :quantidade WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
