<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Orcamento; // Import the Orcamento model

class OrcamentoController extends Controller
{
    private $orcamentoModel;

    public function __construct()
    {
        // Load the Orcamento model
        $this->orcamentoModel = $this->model('Orcamento');
    }

    public function index()
    {
        $orcamentos = $this->orcamentoModel->all();
        $this->view('admin/orcamentos/index', ['orcamentos' => $orcamentos], 'admin');
    }

    public function create()
    {
        $this->view('admin/orcamentos/form', [], 'admin');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'solicitacao_id' => $_POST['solicitacao_id'],
                'admin_id' => $_POST['admin_id'], // This should ideally come from the logged-in admin session
                'valor_total' => $_POST['valor_total'],
                'valor_sinal' => $_POST['valor_sinal'],
                'link_hash' => uniqid('orc_'), // Generate a unique hash for the link
                'status' => 'pendente_cliente', // Default status from new schema
                'data_validade' => $_POST['data_validade'],
                'observacoes_admin' => $_POST['observacoes_admin'] ?? null,
            ];

            if ($this->orcamentoModel->create($data)) {
                $this->redirect('/admin/orcamentos');
            } else {
                // Handle error
                // For now, just redirect back to create form
                $this->redirect('/admin/orcamentos/create');
            }
        }
    }

    public function show($id)
    {
        $orcamento = $this->orcamentoModel->find($id);
        if (!$orcamento) {
            // Handle not found
            $this->redirect('/admin/orcamentos');
        }
        $this->view('admin/orcamentos/show', ['orcamento' => $orcamento], 'admin');
    }

    public function edit($id)
    {
        $orcamento = $this->orcamentoModel->find($id);
        if (!$orcamento) {
            // Handle not found
            $this->redirect('/admin/orcamentos');
        }
        $this->view('admin/orcamentos/form', ['orcamento' => $orcamento], 'admin');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'solicitacao_id' => $_POST['solicitacao_id'],
                'admin_id' => $_POST['admin_id'], // This should ideally come from the logged-in admin session
                'valor_total' => $_POST['valor_total'],
                'valor_sinal' => $_POST['valor_sinal'],
                'status' => $_POST['status'],
                'data_validade' => $_POST['data_validade'],
                'observacoes_admin' => $_POST['observacoes_admin'] ?? null,
            ];

            if ($this->orcamentoModel->update($id, $data)) {
                $this->redirect('/admin/orcamentos');
            }
        } else {
            // Handle error
            // For now, just redirect back to edit form
            $this->redirect('/admin/orcamentos/edit/' . $id);
        }
    }

    public function delete($id)
    {
        if ($this->orcamentoModel->delete($id)) {
            $this->redirect('/admin/orcamentos');
        } else {
            // Handle error
            $this->redirect('/admin/orcamentos');
        }
    }
}
