<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Carrinho;

class CheckoutController extends Controller
{
    private $carrinhoModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->carrinhoModel = new Carrinho();
    }

    /**
     * Exibe a página de checkout.
     */
    public function index()
    {
        // Redireciona para o login se não estiver logado
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Você precisa fazer login para finalizar o pedido.';
            header('Location: /login');
            exit;
        }

        $carrinhoDetalhado = $this->carrinhoModel->getDetalhesCarrinho();

        if (empty($carrinhoDetalhado)) {
            $_SESSION['error_message'] = 'Seu carrinho está vazio.';
            header('Location: /carrinho');
            exit;
        }

        $valorTotal = array_reduce($carrinhoDetalhado, function ($soma, $item) {
            return $soma + $item['subtotal'];
        }, 0);

        $this->view('public/checkout/index', [
            'titulo' => 'Finalizar Pré-Reserva',
            'carrinho' => $carrinhoDetalhado,
            'valorTotal' => $valorTotal
        ]);
    }

    /**
     * Processa o pedido, salva no banco e redireciona para a página de sucesso.
     */
    public function processar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $carrinho = $this->carrinhoModel->getDetalhesCarrinho();

        if (empty($carrinho)) {
            header('Location: /carrinho');
            exit;
        }

        try {
            $pedidoModel = $this->model('Pedido');
            $pedidoId = $pedidoModel->processarPedido($_SESSION['user_id'], $carrinho);

            // Limpar o carrinho e redirecionar
            $this->carrinhoModel->limparCarrinho();
            $_SESSION['ultimo_pedido_id'] = $pedidoId;
            header('Location: /checkout/sucesso');
            exit;

        } catch (\Exception $e) {
            error_log($e->getMessage()); // Log do erro
            $_SESSION['error_message'] = 'Ocorreu um erro ao processar seu pedido. Por favor, tente novamente.';
            header('Location: /carrinho');
            exit;
        }
    }

    /**
     * Exibe a página de sucesso do pedido.
     */
    public function sucesso()
    {
        if (!isset($_SESSION['ultimo_pedido_id'])) {
            header('Location: /');
            exit;
        }

        $pedidoModel = $this->model('Pedido');
        $pedido = $pedidoModel->getPedidoComItens($_SESSION['ultimo_pedido_id']);
        unset($_SESSION['ultimo_pedido_id']);

        if (!$pedido) {
            header('Location: /');
            exit;
        }

        // Chave PIX - pode vir de uma configuração do banco de dados no futuro
        $chavePix = 'seu-email-ou-chave-pix@dominio.com';

        $this->view('public/checkout/sucesso', [
            'titulo' => 'Pedido Recebido!',
            'pedido' => $pedido,
            'chavePix' => $chavePix
        ]);
    }
}
