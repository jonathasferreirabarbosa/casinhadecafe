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
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';

        // Armazena o input para repopular o formulário em caso de erro
        $_SESSION['old_input'] = $_POST;

        // 2. Validação
        if (empty($email) || empty($telefone) || empty($senha) || empty($confirmar_senha)) {
            $_SESSION['error_message'] = 'Todos os campos, exceto o nome, são obrigatórios.';
            header('Location: /register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Por favor, insira um e-mail válido.';
            header('Location: /register');
            exit;
        }

        if ($senha !== $confirmar_senha) {
            $_SESSION['error_message'] = 'As senhas não conferem.';
            header('Location: /register');
            exit;
        }

        // 3. Verificar se o usuário já existe
        $usuarioModel = $this->model('Usuario');
        $usuarioExistente = $usuarioModel->findByEmail($email);

        if ($usuarioExistente) {
            $_SESSION['error_message'] = 'Este e-mail já está cadastrado.';
            header('Location: /register');
            exit;
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
            // Limpa o input antigo da sessão em caso de sucesso
            unset($_SESSION['old_input']);
            
            // Podemos criar uma mensagem de sucesso para a página de login
            $_SESSION['success_message'] = 'Cadastro realizado com sucesso! Faça o login.';
            header('Location: /login');
            exit;
        } else {
            $_SESSION['error_message'] = 'Ocorreu um erro ao criar o usuário. Tente novamente.';
            header('Location: /register');
            exit;
        }
    }

    /**
     * Exibe o formulário de login.
     */
    public function login()
    {
        $this->view('auth/login', ['titulo' => 'Login']);
    }

    /**
     * Processa a tentativa de login.
     */
    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        // Validação básica
        if (empty($email) || empty($senha)) {
            $_SESSION['error_message'] = 'E-mail e senha são obrigatórios.';
            header('Location: /login');
            exit;
        }

        // Encontra o usuário pelo e-mail
        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->findByEmail($email);

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            // Autenticação bem-sucedida
            // Limpa dados antigos da sessão e armazena o ID do usuário
            unset($_SESSION['error_message']);
            unset($_SESSION['old_input']);
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_type'] = $usuario['tipo_usuario'];

            // Redireciona para o dashboard
            header('Location: /dashboard');
            exit;
        } else {
            // Falha na autenticação
            $_SESSION['error_message'] = 'Credenciais inválidas. Tente novamente.';
            $_SESSION['old_input'] = $_POST;
            header('Location: /login');
            exit;
        }
    }

    /**
     * Faz o logout do usuário.
     */
    public function logout()
    {
        // Limpa todas as variáveis de sessão
        session_unset();
        // Destrói a sessão
        session_destroy();

        // Redireciona para a página de login
        header('Location: /login');
        exit;
    }
}