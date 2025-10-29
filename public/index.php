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

// Rotas de Autenticação
$router->get('/register', '\App\Controllers\AuthController@register');
$router->post('/register', '\App\Controllers\AuthController@store');

// Rotas de Login/Logout
$router->get('/login', '\App\Controllers\AuthController@login');
$router->post('/login', '\App\Controllers\AuthController@authenticate');
$router->get('/logout', '\App\Controllers\AuthController@logout');

// Rota do Dashboard (protegida)
$router->get('/dashboard', '\App\Controllers\DashboardController@index');


// --- Despacha a Requisição ---
// O roteador encontra a rota correspondente à URL e executa o método do controller.
$router->dispatch();
