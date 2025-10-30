<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Pedido extends Model
{
    /**
     * Busca todos os pedidos com detalhes do cliente e da fornada.
     *
     * @return array Lista de pedidos com detalhes.
     */
    public function getAllPedidosComDetalhes()
    {
        $sql = "
            SELECT
                p.id,
                u.nome AS cliente_nome,
                f.titulo AS fornada_titulo,
                p.data_pedido,
                p.valor_total,
                p.status_pagamento,
                p.status_pedido
            FROM Pedidos AS p
            JOIN Usuarios AS u ON p.cliente_id = u.id
            JOIN Fornadas AS f ON p.fornada_id = f.id
            ORDER BY p.data_pedido DESC;
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um pedido específico com todos os seus itens e detalhes do cliente.
     *
     * @param int $id O ID do pedido.
     * @return array|false Os detalhes do pedido ou false se não encontrado.
     */
    public function getPedidoComItens($id)
    {
        // Primeiro, busca os detalhes do pedido e do cliente
        $sql_pedido = "
            SELECT
                p.id, p.data_pedido, p.valor_total, p.status_pagamento, p.status_pedido,
                u.nome AS cliente_nome, u.email AS cliente_email, u.telefone AS cliente_telefone,
                f.titulo AS fornada_titulo
            FROM Pedidos AS p
            JOIN Usuarios AS u ON p.cliente_id = u.id
            JOIN Fornadas AS f ON p.fornada_id = f.id
            WHERE p.id = :id;
        ";
        $stmt_pedido = $this->pdo->prepare($sql_pedido);
        $stmt_pedido->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_pedido->execute();
        $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            return false;
        }

        // Em seguida, busca os itens associados a esse pedido
        $sql_itens = "
            SELECT
                ip.quantidade,
                ip.preco_unitario_no_momento AS preco_unitario,
                pr.nome AS produto_nome
            FROM Itens_Pedido AS ip
            JOIN Itens_Fornada AS ifo ON ip.item_fornada_id = ifo.id
            JOIN Produtos AS pr ON ifo.produto_id = pr.id
            WHERE ip.pedido_id = :id;
        ";
        $stmt_itens = $this->pdo->prepare($sql_itens);
        $stmt_itens->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_itens->execute();
        $pedido['itens'] = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

        return $pedido;
    }

    /**
     * Cria um novo pedido no banco de dados.
     *
     * @param array $dados
     * @return string O ID do pedido recém-criado.
     */
    public function createPedido(array $dados)
    {
        // Adicionar data de expiração da reserva se configurado
        // Esta lógica pode ser expandida com base nas configurações do sistema
        $sql = "INSERT INTO Pedidos (cliente_id, fornada_id, valor_total, status_pagamento, status_pedido)
                VALUES (:cliente_id, :fornada_id, :valor_total, 'pendente_50', 'em_processamento')";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cliente_id', $dados['cliente_id'], PDO::PARAM_INT);
        $stmt->bindParam(':fornada_id', $dados['fornada_id'], PDO::PARAM_INT);
        $stmt->bindParam(':valor_total', $dados['valor_total']);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * Adiciona os itens de um pedido na tabela Itens_Pedido.
     *
     * @param array $dados
     * @return bool
     */
    public function createItensPedido(array $dados)
    {
        $sql = "INSERT INTO Itens_Pedido (pedido_id, item_fornada_id, quantidade, preco_unitario_no_momento)
                VALUES (:pedido_id, :item_fornada_id, :quantidade, :preco_unitario_no_momento)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pedido_id', $dados['pedido_id'], PDO::PARAM_INT);
        $stmt->bindParam(':item_fornada_id', $dados['item_fornada_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantidade', $dados['quantidade'], PDO::PARAM_INT);
        $stmt->bindParam(':preco_unitario_no_momento', $dados['preco_unitario_no_momento']);

        return $stmt->execute();
    }

    /**
     * Processa um novo pedido, incluindo a criação do pedido, dos itens e a atualização do estoque.
     * Tudo dentro de uma transação.
     *
     * @param int $clienteId
     * @param array $carrinho
     * @return int O ID do novo pedido.
     * @throws \Exception
     */
    public function processarPedido(int $clienteId, array $carrinho)
    {
        $this->pdo->beginTransaction();

        try {
            $valorTotal = array_reduce($carrinho, fn($s, $i) => $s + $i['subtotal'], 0);
            $itemFornadaModel = new ItemFornada(); // Instancia o model diretamente
            $fornadaId = $itemFornadaModel->find($carrinho[0]['item_fornada_id'])['fornada_id'];

            $pedidoId = $this->createPedido([
                'cliente_id' => $clienteId,
                'fornada_id' => $fornadaId,
                'valor_total' => $valorTotal,
            ]);

            foreach ($carrinho as $item) {
                $this->createItensPedido([
                    'pedido_id' => $pedidoId,
                    'item_fornada_id' => $item['item_fornada_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario_no_momento' => $item['preco_unitario'],
                ]);
                $itemFornadaModel->reduzirEstoque($item['item_fornada_id'], $item['quantidade']);
            }

            $this->pdo->commit();

            return $pedidoId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Mapeia os status internos para textos mais amigáveis.
     *
     * @param string $status O status do banco de dados.
     * @return string O texto amigável para o status.
     */
    public function getStatusText($status)
    {
        $map = [
            // Status de Pagamento
            'pendente_50' => 'Aguardando Pagamento',
            'confirmado_50' => 'Pagamento Confirmado',
            'pago_total' => 'Pago Integralmente',
            'expirado' => 'Expirado',
            // Status do Pedido
            'em_processamento' => 'Em Processamento',
            'pronto_retirada' => 'Pronto para Retirada',
            'entregue' => 'Entregue',
            'cancelado' => 'Cancelado'
        ];

        return $map[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
