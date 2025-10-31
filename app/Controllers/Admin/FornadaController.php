<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Fornada;
use App\Models\ItemFornada;
use App\Models\Produto;

class FornadaController extends Controller
{
    private $fornadaModel;
    private $itemFornadaModel;
    private $produtoModel;

    public function __construct()
    {
        // $this->middleware('admin'); // Futuramente, adicionar middleware de autenticação de admin
        $this->fornadaModel = new Fornada();
        $this->itemFornadaModel = new ItemFornada();
        $this->produtoModel = new Produto();
    }

    public function index()
    {
        $fornadas = $this->fornadaModel->getAll();
        $this->view('admin/fornadas/index', ['fornadas' => $fornadas], 'admin');
    }

    public function create()
    {
        $this->view('admin/fornadas/form', [], 'admin');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'titulo' => $_POST['titulo'] ?? '',
                'descricao_adicional' => $_POST['descricao_adicional'] ?? '',
                'data_inicio_pedidos' => $_POST['data_inicio_pedidos'] ?? '',
                'data_fim_pedidos' => $_POST['data_fim_pedidos'] ?? '',
                'data_entrega' => $_POST['data_entrega'] ?? '',
                'status' => $_POST['status'] ?? 'planejada' // Default status
            ];

            // Validação básica
            if (empty($dados['titulo']) || empty($dados['data_inicio_pedidos']) || empty($dados['data_fim_pedidos'])) {
                $_SESSION['error_message'] = 'Por favor, preencha todos os campos obrigatórios.';
                $this->redirect('/admin/fornadas/criar');
                return;
            }

            if ($this->fornadaModel->create($dados)) {
                $_SESSION['success_message'] = 'Fornada criada com sucesso!';
                $this->redirect('/admin/fornadas');
            } else {
                $_SESSION['error_message'] = 'Erro ao criar fornada.';
                $this->redirect('/admin/fornadas/criar');
            }
        }
    }

    public function edit($id)
    {
        $fornada = $this->fornadaModel->find($id);
        if (!$fornada) {
            $_SESSION['error_message'] = 'Fornada não encontrada.';
            $this->redirect('/admin/fornadas');
            return;
        }
        $this->view('admin/fornadas/form', ['fornada' => $fornada], 'admin');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'titulo' => $_POST['titulo'] ?? '',
                'descricao_adicional' => $_POST['descricao_adicional'] ?? '',
                'data_inicio_pedidos' => $_POST['data_inicio_pedidos'] ?? '',
                'data_fim_pedidos' => $_POST['data_fim_pedidos'] ?? '',
                'data_entrega' => $_POST['data_entrega'] ?? '',
                'status' => $_POST['status'] ?? 'planejada'
            ];

            // Validação básica
            if (empty($dados['titulo']) || empty($dados['data_inicio_pedidos']) || empty($dados['data_fim_pedidos'])) {
                $_SESSION['error_message'] = 'Por favor, preencha todos os campos obrigatórios.';
                $this->redirect('/admin/fornadas/editar/' . $id);
                return;
            }

            if ($this->fornadaModel->update($id, $dados)) {
                $_SESSION['success_message'] = 'Fornada atualizada com sucesso!';
                $this->redirect('/admin/fornadas');
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar fornada.';
                $this->redirect('/admin/fornadas/editar/' . $id);
            }
        }
    }

    public function delete($id)
    {
        if ($this->fornadaModel->delete($id)) {
            $_SESSION['success_message'] = 'Fornada deletada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar fornada.';
        }
        $this->redirect('/admin/fornadas');
    }

    public function manageItems($fornadaId)
    {
        $fornada = $this->fornadaModel->find($fornadaId);
        if (!$fornada) {
            $_SESSION['error_message'] = 'Fornada não encontrada.';
            $this->redirect('/admin/fornadas');
            return;
        }

        $fornadaItems = $this->itemFornadaModel->getByFornadaId($fornadaId);
        $produtosDisponiveis = $this->produtoModel->getAll(); // Todos os produtos para seleção

        $this->view('admin/fornadas/manage_items', [
            'fornada' => $fornada,
            'fornadaItems' => $fornadaItems,
            'produtosDisponiveis' => $produtosDisponiveis
        ], 'admin');
    }

    public function storeItem($fornadaId)
    {
        $dados = [
            'fornada_id' => $fornadaId,
            'produto_id' => $_POST['produto_id'] ?? '',
            'preco_unitario' => $_POST['preco_unitario'] ?? '',
            'estoque_inicial' => $_POST['estoque_inicial'] ?? '',
            'estoque_atual' => $_POST['estoque_inicial'] ?? '' // Estoque atual começa igual ao inicial
        ];

        // Validação básica
        if (empty($dados['produto_id']) || empty($dados['preco_unitario']) || empty($dados['estoque_inicial'])) {
            $_SESSION['error_message'] = 'Por favor, preencha todos os campos obrigatórios para o item da fornada.';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
            return;
        }

        // Verifica se o item já existe na fornada
        if ($this->itemFornadaModel->exists($fornadaId, $dados['produto_id'])) {
            $_SESSION['error_message'] = 'Este produto já foi adicionado a esta fornada.';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
            return;
        }

        if ($this->itemFornadaModel->create($dados)) {
            $_SESSION['success_message'] = 'Item da fornada adicionado com sucesso!';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
        } else {
            $_SESSION['error_message'] = 'Erro ao adicionar item da fornada.';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
        }
    }

    public function updateItem($fornadaId, $itemId)
    {
        $dados = [
            'preco_unitario' => $_POST['preco_unitario'] ?? '',
            'estoque_inicial' => $_POST['estoque_inicial'] ?? '',
            'estoque_atual' => $_POST['estoque_atual'] ?? ''
        ];

        // Validação básica
        if (empty($dados['preco_unitario']) || empty($dados['estoque_inicial'])) {
            $_SESSION['error_message'] = 'Por favor, preencha todos os campos obrigatórios para o item da fornada.';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
            return;
        }

        if ($this->itemFornadaModel->update($itemId, $dados)) {
            $_SESSION['success_message'] = 'Item da fornada atualizado com sucesso!';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar item da fornada.';
            $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
        }
    }

    public function deleteItem($fornadaId, $itemId)
    {
        if ($this->itemFornadaModel->delete($itemId)) {
            $_SESSION['success_message'] = 'Item da fornada deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar item da fornada.';
        }
        $this->redirect('/admin/fornadas/' . $fornadaId . '/itens');
    }

    /**
     * Retorna os itens de uma fornada em formato JSON.
     * Usado para chamadas AJAX no formulário de criação de pedidos.
     *
     * @param int $fornadaId
     */
    public function getItensByFornadaId($fornadaId)
    {
        header('Content-Type: application/json');
        $itens = $this->itemFornadaModel->getByFornadaId($fornadaId);
        echo json_encode($itens);
        exit;
    }
}
