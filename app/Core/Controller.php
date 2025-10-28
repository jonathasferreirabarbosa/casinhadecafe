<?php

abstract class Controller {

    /**
     * Carrega e retorna uma instância de um Model.
     *
     * @param string $modelName O nome da classe do Model (ex: 'Usuario')
     * @return object A instância do Model
     */
    protected function model($modelName) {
        $modelFile = ROOT_PATH . '/app/Models/' . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        }
        // Lança um erro se o arquivo do model não for encontrado.
        throw new Exception("Model '{$modelName}' não encontrado em '{$modelFile}'.");
    }

    /**
     * Renderiza uma View, passando dados para ela.
     *
     * @param string $viewName O caminho do arquivo da View (ex: 'public/home')
     * @param array $data Dados a serem extraídos e disponibilizados para a View
     */
    protected function view($viewName, $data = []) {
        $viewFile = ROOT_PATH . '/app/Views/' . $viewName . '.php';

        if (file_exists($viewFile)) {
            // Transforma as chaves do array $data em variáveis
            // Ex: $data['titulo'] se torna a variável $titulo dentro da view
            extract($data);

            // Inicia o buffer de saída para capturar o HTML da view
            ob_start();
            require $viewFile;
            // Limpa e retorna o conteúdo do buffer
            echo ob_get_clean();
            return;
        }
        // Lança um erro se o arquivo da view não for encontrado.
        throw new Exception("View '{$viewName}' não encontrada em '{$viewFile}'.");
    }
}
