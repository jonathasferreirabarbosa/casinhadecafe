<?php

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
     * Esta é uma implementação básica que será expandida.
     */
    public function dispatch() {
        // Por enquanto, apenas uma mensagem para indicar que o Router está no lugar.
        echo "<p>Router->dispatch() foi chamado. A lógica de roteamento será implementada aqui.</p>";

        // A lógica futura irá:
        // 1. Pegar a URI da requisição atual (ex: /produtos)
        // 2. Pegar o método da requisição (GET, POST)
        // 3. Procurar na lista $this->routes por uma rota correspondente.
        // 4. Se encontrar, instanciar o Controller e chamar o método.
        // 5. Se não encontrar, retornar um erro 404 (Não Encontrado).
    }
}
