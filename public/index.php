<?php

// --- Ponto de Entrada da Aplicação (Front Controller) ---

// Define uma constante para o diretório raiz do projeto para facilitar a inclusão de arquivos.
// __DIR__ é o diretório do arquivo atual (public), então subimos um nível.
define('ROOT_PATH', dirname(__DIR__));

// Carrega o arquivo de configuração do banco de dados.
require_once ROOT_PATH . '/config/database.php';

// Carrega a classe principal de conexão com o banco de dados.
require_once ROOT_PATH . '/app/Core/Database.php';

<?php

// --- Ponto de Entrada da Aplicação (Front Controller) ---

session_start();

// Define uma constante para o diretório raiz do projeto.
define('ROOT_PATH', dirname(__DIR__));

// Carrega arquivos essenciais.
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/Core/Router.php';
require_once ROOT_PATH . '/app/Controllers/HomeController.php'; // Carrega o controller da página inicial

// Cria uma instância do roteador.
$router = new Router();

// --- Definição de Rotas ---

// Rota para a página inicial.
$router->get('/', 'HomeController@index');

// Rota para a página de teste do banco de dados.
$router->get('/test-db', 'HomeController@testDb');

// Outras rotas (ex: /login, /produtos, /admin/dashboard) serão adicionadas aqui.


// --- Despacha a Requisição ---
// O roteador encontra a rota correspondente à URL e executa o método do controller.
$router->dispatch();

