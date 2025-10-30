<?php

namespace App\Core;

abstract class Controller {

    /**
     * Carrega e retorna uma instância de um Model.
     *
     * @param string $modelName O nome da classe do Model (ex: 'Usuario')
     * @return object A instância do Model
     */
    protected function model($modelName) {
        $modelClass = '\\App\\Models\\' . $modelName;
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        // Lança um erro se a classe do model não for encontrada.
        throw new \Exception("Model '{$modelName}' não encontrado.");
    }

    /**
     * Renderiza uma View, passando dados para ela.
     *
     * @param string $viewName O caminho do arquivo da View (ex: 'public/home')
     * @param array $data Dados a serem extraídos e disponibilizados para a View
     */
    protected function view($viewName, $data = [], $layout = null) {
        $viewFile = ROOT_PATH . '/app/Views/' . $viewName . '.php';

        if (file_exists($viewFile)) {
            extract($data);

            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            if ($layout) {
                $layoutFile = ROOT_PATH . '/app/Views/layout/' . $layout . '.php';
                if (file_exists($layoutFile)) {
                    require $layoutFile;
                } else {
                    throw new \Exception("Layout '{$layout}' não encontrado em '{$layoutFile}'.");
                }
            } else {
                echo $content;
            }
            return;
        }
        
        throw new \Exception("View '{$viewName}' não encontrada em '{$viewFile}'.");
    }

    /**
     * Redireciona para uma URL específica.
     *
     * @param string $url A URL para a qual redirecionar.
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}
