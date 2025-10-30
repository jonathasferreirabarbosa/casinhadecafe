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

        // Carrega o model de Pedido (a ser criado)
        $pedidoModel = $this->model('Pedido');
        $pedidos = $pedidoModel->getAllPedidosComDetalhes();

        foreach ($pedidos as &$pedido) {
            $pedido['status_pagamento_texto'] = $pedidoModel->getStatusText($pedido['status_pagamento']);
            $pedido['status_pedido_texto'] = $pedidoModel->getStatusText($pedido['status_pedido']);
        }

        $this->view('admin/pedidos/index', ['titulo' => 'Gerenciar Pedidos', 'pedidos' => $pedidos], 'admin');
    }

    /**
     * Exibe os detalhes de um pedido específico.
     * @param int $id O ID do pedido.
     */
    public function ver($id)
    {
        // Verifica se o usuário é admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error_message'] = 'Você não tem permissão para acessar esta página.';
            header('Location: /login');
            exit;
        }

        // Carrega o model de Pedido
        $pedidoModel = $this->model('Pedido');
        $pedido = $pedidoModel->getPedidoComItens($id);

        if ($pedido) {
            $pedido['status_pagamento_texto'] = $pedidoModel->getStatusText($pedido['status_pagamento']);
            $pedido['status_pedido_texto'] = $pedidoModel->getStatusText($pedido['status_pedido']);
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
        // Verifica se o usuário é admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error_message'] = 'Você não tem permissão para acessar esta página.';
            header('Location: /login');
            exit;
        }

        // Carrega os models necessários
        $usuarioModel = $this->model('Usuario');
        $fornadaModel = $this->model('Fornada');

        // Busca clientes e fornadas ativas
        $clientes = $usuarioModel->getAll(); // Assumindo que este método busca todos os usuários
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
            $itens_pedido = $_POST['itens_pedido'] ?? [];

            if (!$cliente_id || !$fornada_id || empty($itens_pedido)) {
                $_SESSION['error_message'] = 'Cliente, fornada e pelo menos um item são obrigatórios.';
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
}
