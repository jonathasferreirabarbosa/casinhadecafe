<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class PedidoController extends Controller
{
    /**
     * Lista todos os pedidos.
     */
    public function index()
    {
        // Verifica se o usuário é admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error_message'] = 'Você não tem permissão para acessar esta página.';
            header('Location: /login');
            exit;
        }

        $pedidoModel = $this->model('Pedido');
        $pedidos = $pedidoModel->getAllPedidosComDetalhes();

        foreach ($pedidos as &$pedido) {
            $pedido['status_pagamento_texto'] = $pedidoModel->getStatusText($pedido['status_pagamento']);
        }

        $this->view('admin/pedidos/index', ['titulo' => 'Gerenciar Pedidos', 'pedidos' => $pedidos], 'admin');
    }

    /**
     * Exibe os detalhes de um pedido específico.
     * @param int $id O ID do pedido.
     */
    public function ver($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error_message'] = 'Você não tem permissão para acessar esta página.';
            header('Location: /login');
            exit;
        }

        $pedidoModel = $this->model('Pedido');
        $pedido = $pedidoModel->getPedidoComItens($id);

        if ($pedido) {
            $pedido['status_pagamento_texto'] = $pedidoModel->getStatusText($pedido['status_pagamento']);
        }

        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido não encontrado.';
            header('Location: /admin/pedidos');
            exit;
        }

        $this->view('admin/pedidos/ver', ['titulo' => 'Detalhes do Pedido', 'pedido' => $pedido], 'admin');
    }

    /**
     * Exibe o formulário para criar um novo pedido.
     */
    public function create()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error_message'] = 'Você não tem permissão para acessar esta página.';
            header('Location: /login');
            exit;
        }

        $usuarioModel = $this->model('Usuario');
        $fornadaModel = $this->model('Fornada');

        $clientes = $usuarioModel->getAll();
        $fornadas = $fornadaModel->getFornadasDisponiveisParaVenda();

        $this->view('admin/pedidos/form', [
            'titulo' => 'Criar Novo Pedido',
            'clientes' => $clientes,
            'fornadas' => $fornadas
        ], 'admin');
    }

    /**
     * Salva um novo pedido vindo do formulário do admin.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_id = $_POST['cliente_id'] ?? null;
            $fornada_id = $_POST['fornada_id'] ?? null;
            $itens_pedido = array_filter($_POST['itens_pedido'] ?? [], function($item) {
                return !empty($item['item_id']);
            });

            if (!$cliente_id || !$fornada_id || empty($itens_pedido)) {
                $_SESSION['error_message'] = 'Cliente, fornada e pelo menos um item são obrigatórios.';
                header('Location: /admin/pedidos/criar');
                exit;
            }

            $itemIds = array_column($itens_pedido, 'item_id');
            if (count($itemIds) !== count(array_unique($itemIds))) {
                $_SESSION['error_message'] = 'Não é permitido adicionar o mesmo item mais de uma vez no pedido.';
                header('Location: /admin/pedidos/criar');
                exit;
            }

            $carrinho = [];
            $itemFornadaModel = $this->model('ItemFornada');
            foreach ($itens_pedido as $itemData) {
                $itemFornada = $itemFornadaModel->find($itemData['item_id']);
                if ($itemFornada) {
                    if ($itemData['quantidade'] > $itemFornada['estoque_atual']) {
                        $_SESSION['error_message'] = "Estoque insuficiente para o item {$itemFornada['nome']}.";
                        header('Location: /admin/pedidos/criar');
                        exit;
                    }
                    $carrinho[] = [
                        'item_fornada_id' => $itemData['item_id'],
                        'quantidade' => $itemData['quantidade'],
                        'preco_unitario' => $itemFornada['preco_unitario'],
                        'subtotal' => $itemData['quantidade'] * $itemFornada['preco_unitario'],
                    ];
                }
            }

            try {
                $pedidoModel = $this->model('Pedido');
                $pedidoModel->processarPedido($cliente_id, $carrinho);
                $_SESSION['success_message'] = 'Pedido criado com sucesso!';
                header('Location: /admin/pedidos');
                exit;
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $_SESSION['error_message'] = 'Erro ao criar o pedido: ' . $e->getMessage();
                header('Location: /admin/pedidos/criar');
                exit;
            }
        }
    }

    /**
     * Exibe o formulário para editar um pedido existente.
     * @param int $id O ID do pedido.
     */
    public function edit($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error_message'] = 'Você não tem permissão para acessar esta página.';
            header('Location: /login');
            exit;
        }

        $pedidoModel = $this->model('Pedido');
        $pedido = $pedidoModel->getPedidoComItens($id);

        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido não encontrado.';
            header('Location: /admin/pedidos');
            exit;
        }

        $usuarioModel = $this->model('Usuario');
        $fornadaModel = $this->model('Fornada');
        $itemFornadaModel = $this->model('ItemFornada');

        $clientes = $usuarioModel->getAll();
        $fornadas = $fornadaModel->getFornadasDisponiveisParaVenda();
        $itensFornada = $itemFornadaModel->getByFornadaId($pedido['fornada_id']);

        // Calcula o estoque disponível para edição
        foreach ($itensFornada as &$itemFornada) {
            $quantidadeNoPedidoAtual = 0;
            foreach ($pedido['itens'] as $pedidoItem) {
                if ($pedidoItem['item_fornada_id'] == $itemFornada['id']) {
                    $quantidadeNoPedidoAtual = $pedidoItem['quantidade'];
                    break;
                }
            }
            $itemFornada['estoque_disponivel_para_edicao'] = $itemFornada['estoque_atual'] + $quantidadeNoPedidoAtual;
        }

        $this->view('admin/pedidos/edit', [
            'titulo' => 'Editar Pedido',
            'pedido' => $pedido,
            'clientes' => $clientes,
            'fornadas' => $fornadas,
            'itensFornada' => $itensFornada
        ], 'admin');
    }

    /**
     * Atualiza um pedido existente no banco de dados.
     * @param int $id O ID do pedido.
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedidoModel = $this->model('Pedido');
            if ($pedidoModel->updatePedidoCompleto($id, $_POST)) {
                $_SESSION['success_message'] = 'Pedido atualizado com sucesso!';
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar o pedido.';
            }
            header('Location: /admin/pedidos');
            exit;
        }
    }

    /**
     * Confirma o pagamento de um pedido.
     * @param int $id O ID do pedido.
     */
    public function confirmarPagamento($id)
    {
        $pedidoModel = $this->model('Pedido');
        if ($pedidoModel->updateStatus($id, 'confirmado')) {
            $_SESSION['success_message'] = 'Pagamento do pedido confirmado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao confirmar o pagamento do pedido.';
        }
        header('Location: /admin/pedidos');
        exit;
    }

    /**
     * Alterna o status de entrega de um pedido.
     * @param int $id O ID do pedido.
     */
    public function toggleEntrega($id)
    {
        $pedidoModel = $this->model('Pedido');
        $pedido = $pedidoModel->getPedidoComItens($id);

        if ($pedido) {
            $novoStatus = !$pedido['entregue'];
            if ($pedidoModel->updateStatusEntrega($id, $novoStatus)) {
                $_SESSION['success_message'] = 'Status de entrega atualizado com sucesso!';
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar o status de entrega.';
            }
        } else {
            $_SESSION['error_message'] = 'Pedido não encontrado.';
        }

        header('Location: /admin/pedidos');
        exit;
    }

    /**
     * Deleta um pedido do banco de dados.
     * @param int $id O ID do pedido.
     */
    public function delete($id)
    {
        $pedidoModel = $this->model('Pedido');
        if ($pedidoModel->delete($id)) {
            $_SESSION['success_message'] = 'Pedido deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar o pedido.';
        }
        header('Location: /admin/pedidos');
        exit;
    }
}
