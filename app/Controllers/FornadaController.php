<?php

namespace App\Controllers;

use App\Core\Controller;

class FornadaController extends Controller
{
    /**
     * Lista as fornadas ativas.
     */
    public function index()
    {
        // Carrega o model de Fornada
        $fornadaModel = $this->model('Fornada');
        $fornadas = $fornadaModel->getFornadasAtivas();

        $this->view('public/fornadas/index', ['titulo' => 'Fornadas Abertas', 'fornadas' => $fornadas]);
    }

    /**
     * Exibe os detalhes de uma fornada específica e seus produtos.
     * @param int $id O ID da fornada.
     */
    public function show($id)
    {
        $fornadaModel = $this->model('Fornada');
        $fornada = $fornadaModel->getFornadaComItens($id);

        if (!$fornada) {
            // Redireciona ou mostra uma página de erro
            $_SESSION['error_message'] = 'Fornada não encontrada.';
            header('Location: /fornadas');
            exit;
        }

        $this->view('public/fornadas/show', ['titulo' => $fornada['titulo'], 'fornada' => $fornada]);
    }
}
