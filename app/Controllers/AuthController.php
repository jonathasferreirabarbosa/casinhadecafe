<?php

namespace App\Controllers;

class AuthController extends \App\Core\Controller {

    /**
     * Exibe o formulário de cadastro.
     */
    public function register() {
        $this->view('auth/register', ['titulo' => 'Cadastro']);
    }

    /**
     * Processa os dados do formulário de cadastro.
     */
    public function store() {
        // 1. Obter os dados do POST
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $senha = $_POST['senha'] ?? null;
        $confirmar_senha = $_POST['confirmar_senha'] ?? null;

        // 2. Validação básica
        if (empty($email) || empty($telefone) || empty($senha)) {
            // Idealmente, redirecionar de volta com uma mensagem de erro
            die('E-mail, Telefone e Senha são obrigatórios.');
        }

        if ($senha !== $confirmar_senha) {
            die('As senhas não conferem.');
        }

        // 3. Verificar se o usuário já existe
        $usuarioModel = $this->model('Usuario');
        $usuarioExistente = $usuarioModel->findByEmail($email);

        if ($usuarioExistente) {
            die('Este e-mail já está cadastrado.');
        }

        // 4. Criptografar a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // 5. Montar os dados para o modelo
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'senha_hash' => $senha_hash
        ];

        // 6. Chamar o modelo para criar o usuário
        if ($usuarioModel->create($dados)) {
            // Redirecionar para a página de login com uma mensagem de sucesso
            // (Criaremos a página de login em seguida)
            header('Location: /login?status=success');
            exit;
        } else {
            die('Ocorreu um erro ao criar o usuário. Tente novamente.');
        }
    }
}
