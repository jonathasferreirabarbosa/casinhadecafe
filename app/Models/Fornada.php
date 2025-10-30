<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Fornada extends Model
{
    /**
     * Busca todas as fornadas cadastradas no banco de dados.
     *
     * @return array Lista de fornadas.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM Fornadas ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encontra uma fornada pelo ID.
     *
     * @param int $id O ID da fornada.
     * @return mixed Retorna os dados da fornada se encontrada, ou false caso contrário.
     */
    public function find($id)
    {
        $sql = "SELECT * FROM Fornadas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cria uma nova fornada no banco de dados.
     *
     * @param array $dados Dados da fornada (titulo, descricao_adicional, data_inicio_pedidos (DATE), data_fim_pedidos (DATE), data_entrega (DATE), status).
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($dados)
    {
        $sql = "INSERT INTO Fornadas (titulo, descricao_adicional, data_inicio_pedidos, data_fim_pedidos, status)
                VALUES (:titulo, :descricao_adicional, :data_inicio_pedidos, :data_fim_pedidos, :status)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':titulo', $dados['titulo']);
        $stmt->bindParam(':descricao_adicional', $dados['descricao_adicional']);
        $stmt->bindParam(':data_inicio_pedidos', $dados['data_inicio_pedidos']);
        $stmt->bindParam(':data_fim_pedidos', $dados['data_fim_pedidos']);
        $stmt->bindParam(':status', $dados['status']);

        return $stmt->execute();
    }

    /**
     * Atualiza uma fornada existente no banco de dados.
     *
     * @param int $id O ID da fornada a ser atualizada.
     * @param array $dados Novos dados da fornada.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados)
    {
        $sql = "UPDATE Fornadas SET titulo = :titulo, descricao_adicional = :descricao_adicional,
                data_inicio_pedidos = :data_inicio_pedidos, data_fim_pedidos = :data_fim_pedidos, status = :status
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':titulo', $dados['titulo']);
        $stmt->bindParam(':descricao_adicional', $dados['descricao_adicional']);
        $stmt->bindParam(':data_inicio_pedidos', $dados['data_inicio_pedidos']);
        $stmt->bindParam(':data_fim_pedidos', $dados['data_fim_pedidos']);
        $stmt->bindParam(':status', $dados['status']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deleta uma fornada do banco de dados.
     *
     * @param int $id O ID da fornada a ser deletada.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM Fornadas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Busca todas as fornadas que estão ativas.
     *
     * @return array
     */
    public function getFornadasAtivas()
    {
        $sql = "SELECT * FROM Fornadas WHERE status = 'ativa' AND NOW() BETWEEN data_inicio_pedidos AND data_fim_pedidos ORDER BY data_inicio_pedidos DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma fornada específica com todos os seus itens.
     *
     * @param int $id O ID da fornada.
     * @return array|false
     */
    public function getFornadaComItens($id)
    {
        $sql = "SELECT * FROM Fornadas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fornada = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fornada) {
            $sql_itens = "
                SELECT
                    ifo.id AS item_fornada_id,
                    p.nome AS produto_nome,
                    p.tipo_unidade,
                    ifo.preco_unitario,
                    ifo.estoque_atual
                FROM Itens_Fornada AS ifo
                JOIN Produtos AS p ON ifo.produto_id = p.id
                WHERE ifo.fornada_id = :fornada_id
            ";
            $stmt_itens = $this->pdo->prepare($sql_itens);
            $stmt_itens->bindParam(':fornada_id', $id, PDO::PARAM_INT);
            $stmt_itens->execute();
            $fornada['itens'] = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);
        }

        return $fornada;
    }

    /**
     * Busca todas as fornadas que possuem pelo menos um item com estoque > 0.
     *
     * @return array
     */
    public function getFornadasDisponiveisParaVenda()
    {
        $sql = "
            SELECT DISTINCT f.*
            FROM Fornadas AS f
            JOIN Itens_Fornada AS ifo ON f.id = ifo.fornada_id
            WHERE ifo.estoque_atual > 0
            ORDER BY f.id DESC;
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
