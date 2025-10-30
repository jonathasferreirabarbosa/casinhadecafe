<?php

namespace App\Controllers;

class DashboardController extends \App\Core\Controller
{
    public function __construct()
    {
        // Garante que a sessão está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Se o usuário não estiver logado, redireciona para a página de login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Exibe a página principal do dashboard.
     */
    public function index()
    {
        $data = [
            'titulo' => 'Dashboard',
            'nome_usuario' => $_SESSION['user_name'] ?? 'Usuário'
        ];

        $this->view('admin/dashboard', $data, 'admin');
    }
}
