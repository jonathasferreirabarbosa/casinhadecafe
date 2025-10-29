<?php

namespace App\Core;

class Router {
    protected $routes = [];

    /**
     * Adiciona uma nova rota ao roteador.
     * 
     * @param string $method O método HTTP (GET, POST, etc.)
     * @param string $uri A URI da rota (ex: /produtos)
     * @param string $controller O nome do Controller e método (ex: ProdutoController@index)
     */
    public function add($method, $uri, $controller) {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Atalho para adicionar uma rota GET.
     */
    public function get($uri, $controller) {
        $this->add('GET', $uri, $controller);
    }

    /**
     * Atalho para adicionar uma rota POST.
     */
    public function post($uri, $controller) {
        $this->add('POST', $uri, $controller);
    }

    /**
     * Despacha a requisição para o controller e método correspondente à URI.
     */
    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove a base path da URL, se aplicável (para rodar em subdiretório)
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = $uri ?: '/';

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                // Rota encontrada, vamos processar o controller
                $controllerParts = explode('@', $route['controller']);
                $controllerName = $controllerParts[0];
                $methodName = $controllerParts[1];

                // Com o autoloader PSR-4, podemos instanciar a classe diretamente.
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName();
                        return;
                    }
                }
                
                // Se a classe ou método não existir, cai para o 404.
                break; 
            }
        }

        // Nenhuma rota encontrada
        $this->sendNotFound();
    }

    protected function sendNotFound() {
        http_response_code(404);
        echo "<h1>404 - Página Não Encontrada</h1>";
        // O ideal é ter uma view para a página 404
        // $this->view('errors/404');
        exit;
    }
}
