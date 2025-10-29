<?php

// --- Ponto de Entrada da Aplicação (Front Controller) ---

session_start();

// Define uma constante para o diretório raiz do projeto.
define('ROOT_PATH', dirname(__DIR__));

// Carrega o autoloader do Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// Carrega o arquivo de configuração que não é uma classe
require_once ROOT_PATH . '/config/database.php';

// Cria uma instância do roteador.
$router = new \App\Core\Router();

// --- Definição de Rotas ---

// Rota para a página inicial.
$router->get('/', '\App\Controllers\HomeController@index');

// Outras rotas (ex: /login, /produtos, /admin/dashboard) serão adicionadas aqui.


// --- Despacha a Requisição ---
// O roteador encontra a rota correspondente à URL e executa o método do controller.
$router->dispatch();
