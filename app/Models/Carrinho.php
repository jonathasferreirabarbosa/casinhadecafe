<?php

namespace App\Models;

use App\Core\Model;

class Carrinho extends Model
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public function getConteudo()
    {
        return $_SESSION['carrinho'];
    }

    public function adicionarItem($item_fornada_id, $quantidade)
    {
        $itemModel = new ItemFornada();
        $item = $itemModel->find($item_fornada_id);

        if ($item && $item['estoque_atual'] >= $quantidade) {
            if (isset($_SESSION['carrinho'][$item_fornada_id])) {
                $_SESSION['carrinho'][$item_fornada_id] += $quantidade;
            } else {
                $_SESSION['carrinho'][$item_fornada_id] = $quantidade;
            }
            return true;
        } else {
            return false;
        }
    }

    public function removerItem($item_fornada_id)
    {
        if (isset($_SESSION['carrinho'][$item_fornada_id])) {
            unset($_SESSION['carrinho'][$item_fornada_id]);
        }
    }

    public function atualizarItem($item_fornada_id, $quantidade)
    {
        $itemModel = new ItemFornada();
        $item = $itemModel->find($item_fornada_id);

        if ($item && $item['estoque_atual'] >= $quantidade) {
            $_SESSION['carrinho'][$item_fornada_id] = $quantidade;
            return true;
        } else {
            return false;
        }
    }

    public function getDetalhesCarrinho()
    {
        $carrinho = $this->getConteudo();
        if (empty($carrinho)) {
            return [];
        }

        $itemFornadaModel = new ItemFornada();
        $ids = array_keys($carrinho);
        $itens = $itemFornadaModel->getItensComDetalhes($ids);

        $carrinhoDetalhado = [];
        foreach ($itens as $item) {
            $quantidade = $carrinho[$item['item_fornada_id']];
            $carrinhoDetalhado[] = [
                'item_fornada_id' => $item['item_fornada_id'],
                'produto_nome' => $item['produto_nome'],
                'tipo_unidade' => $item['tipo_unidade'],
                'preco_unitario' => $item['preco_unitario'],
                'estoque_atual' => $item['estoque_atual'],
                'quantidade' => $quantidade,
                'subtotal' => $quantidade * $item['preco_unitario']
            ];
        }

        return $carrinhoDetalhado;
    }

    public function limparCarrinho()
    {
        $_SESSION['carrinho'] = [];
    }
}
