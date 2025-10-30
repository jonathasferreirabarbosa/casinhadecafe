<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Carrinho;

class CarrinhoController extends Controller
{
    private $carrinhoModel;

    public function __construct()
    {
        $this->carrinhoModel = new Carrinho();
    }

    /**
     * Adiciona um item ao carrinho.
     */
    public function adicionar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_fornada_id = $_POST['item_fornada_id'] ?? null;
            $quantidade = (int)($_POST['quantidade'] ?? 1);

            if ($item_fornada_id && $quantidade > 0) {
                if ($this->carrinhoModel->adicionarItem($item_fornada_id, $quantidade)) {
                    $_SESSION['success_message'] = 'Item adicionado ao carrinho!';
                } else {
                    $_SESSION['error_message'] = 'Estoque insuficiente ou item inválido.';
                }
            } else {
                $_SESSION['error_message'] = 'Dados inválidos para adicionar ao carrinho.';
            }

            // Redireciona para a página da fornada
            $itemModel = $this->model('ItemFornada');
            $item = $itemModel->find($item_fornada_id);
            $fornada_id = $item['fornada_id'] ?? 0;
            header('Location: /fornadas/ver/' . $fornada_id);
            exit;
        }
    }

    /**
     * Exibe o conteúdo do carrinho.
     */
    public function index()
    {
        $carrinhoDetalhado = $this->carrinhoModel->getDetalhesCarrinho();
        $valorTotal = array_reduce($carrinhoDetalhado, function ($soma, $item) {
            return $soma + $item['subtotal'];
        }, 0);

        $this->view('public/carrinho/index', [
            'titulo' => 'Meu Carrinho',
            'carrinho' => $carrinhoDetalhado,
            'valorTotal' => $valorTotal
        ]);
    }

    /**
     * Remove um item do carrinho.
     */
    public function remover($item_fornada_id)
    {
        $this->carrinhoModel->removerItem($item_fornada_id);
        $_SESSION['success_message'] = 'Item removido do carrinho.';
        header('Location: /carrinho');
        exit;
    }

    /**
     * Atualiza a quantidade de um item no carrinho.
     */
    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_fornada_id = $_POST['item_fornada_id'] ?? null;
            $quantidade = (int)($_POST['quantidade'] ?? 1);

            if ($item_fornada_id && $quantidade > 0) {
                if ($this->carrinhoModel->atualizarItem($item_fornada_id, $quantidade)) {
                    $_SESSION['success_message'] = 'Quantidade atualizada.';
                } else {
                    $_SESSION['error_message'] = 'Estoque insuficiente.';
                }
            } else {
                $_SESSION['error_message'] = 'Dados inválidos.';
            }
        }
        header('Location: /carrinho');
        exit;
    }
}