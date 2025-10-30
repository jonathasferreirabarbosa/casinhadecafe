<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct()
    {
        // $this->middleware('admin'); // Futuramente, adicionar middleware de autenticação de admin
        $this->usuarioModel = new Usuario();
    }

    public function index()
    {
        $usuarios = $this->usuarioModel->getAll();
        $this->view('admin/usuarios/index', ['usuarios' => $usuarios], 'admin');
    }

    public function create()
    {
        $this->view('admin/usuarios/form', [], 'admin');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'senha' => $_POST['senha'] ?? '',
                'tipo_usuario' => $_POST['tipo_usuario'] ?? 'cliente'
            ];

            // Validação básica
            if (empty($dados['email']) || empty($dados['telefone']) || empty($dados['senha'])) {
                $_SESSION['error_message'] = 'Por favor, preencha todos os campos obrigatórios (E-mail, Telefone, Senha).';
                $this->redirect('/admin/usuarios/criar');
                return;
            }

            $dados['senha_hash'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

            if ($this->usuarioModel->create($dados)) {
                $_SESSION['success_message'] = 'Usuário criado com sucesso!';
                $this->redirect('/admin/usuarios');
            } else {
                $_SESSION['error_message'] = 'Erro ao criar usuário.';
                $this->redirect('/admin/usuarios/criar');
            }
        }
    }

    public function edit($id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            $_SESSION['error_message'] = 'Usuário não encontrado.';
            $this->redirect('/admin/usuarios');
            return;
        }
        $this->view('admin/usuarios/form', ['usuario' => $usuario], 'admin');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'tipo_usuario' => $_POST['tipo_usuario'] ?? 'cliente'
            ];

            // Validação básica
            if (empty($dados['email']) || empty($dados['telefone'])) {
                $_SESSION['error_message'] = 'Por favor, preencha todos os campos obrigatórios (E-mail, Telefone).';
                $this->redirect('/admin/usuarios/editar/' . $id);
                return;
            }

            if ($this->usuarioModel->update($id, $dados)) {
                $_SESSION['success_message'] = 'Usuário atualizado com sucesso!';
                $this->redirect('/admin/usuarios');
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar usuário.';
                $this->redirect('/admin/usuarios/editar/' . $id);
            }
        }
    }

    public function delete($id)
    {
        if ($this->usuarioModel->delete($id)) {
            $_SESSION['success_message'] = 'Usuário deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar usuário.';
        }
        $this->redirect('/admin/usuarios');
    }

    public function changePassword($id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            $_SESSION['error_message'] = 'Usuário não encontrado.';
            $this->redirect('/admin/usuarios');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';

            if (empty($novaSenha) || empty($confirmarSenha)) {
                $_SESSION['error_message'] = 'Por favor, preencha ambos os campos de senha.';
                $this->redirect('/admin/usuarios/alterar-senha/' . $id);
                return;
            }

            if ($novaSenha !== $confirmarSenha) {
                $_SESSION['error_message'] = 'As senhas não coincidem.';
                $this->redirect('/admin/usuarios/alterar-senha/' . $id);
                return;
            }

            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            if ($this->usuarioModel->updatePassword($id, $senhaHash)) {
                $_SESSION['success_message'] = 'Senha atualizada com sucesso!';
                $this->redirect('/admin/usuarios');
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar senha.';
                $this->redirect('/admin/usuarios/alterar-senha/' . $id);
            }
        }

        $this->view('admin/usuarios/change_password', ['usuario' => $usuario], 'admin');
    }
}
