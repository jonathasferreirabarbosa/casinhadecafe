<?php

namespace App\Controllers;

class HomeController extends \App\Core\Controller {

    /**
     * Exibe a página inicial do site.
     */
    public function index() {
        // Dados a serem passados para a view
        $data = [
            'titulo' => 'Página Inicial',
            'descricao' => 'Bem-vindo à Casinha de Café!'
        ];

        // Renderiza a view 'public/home' e passa os dados para ela
        $this->view('public/home', $data);
    }
}
