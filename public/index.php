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

// Rota de Produtos (Admin)
$router->get('/admin/produtos', '\App\Controllers\Admin\ProdutoController@index');
$router->get('/admin/produtos/criar', '\App\Controllers\Admin\ProdutoController@create');
$router->post('/admin/produtos/salvar', '\App\Controllers\Admin\ProdutoController@store');
$router->get('/admin/produtos/editar/{id}', '\App\Controllers\Admin\ProdutoController@edit');
$router->post('/admin/produtos/atualizar/{id}', '\App\Controllers\Admin\ProdutoController@update');
$router->get('/admin/produtos/deletar/{id}', '\App\Controllers\Admin\ProdutoController@delete');

// Rotas de Fornadas (Admin)
$router->get('/admin/fornadas', '\App\Controllers\Admin\FornadaController@index');
$router->get('/admin/fornadas/criar', '\App\Controllers\Admin\FornadaController@create');
$router->post('/admin/fornadas/salvar', '\App\Controllers\Admin\FornadaController@store');
$router->get('/admin/fornadas/editar/{id}', '\App\Controllers\Admin\FornadaController@edit');
$router->post('/admin/fornadas/atualizar/{id}', '\App\Controllers\Admin\FornadaController@update');
$router->get('/admin/fornadas/deletar/{id}', '\App\Controllers\Admin\FornadaController@delete');
$router->get('/admin/fornadas/itens/{fornadaId}', '\App\Controllers\Admin\FornadaController@getItensByFornadaId');

// Rotas de Itens de Fornadas (Admin)
$router->get('/admin/fornadas/{fornadaId}/itens', '\App\Controllers\Admin\FornadaController@manageItems');
$router->post('/admin/fornadas/{fornadaId}/itens/salvar', '\App\Controllers\Admin\FornadaController@storeItem');
$router->add('PUT', '/admin/fornadas/{fornadaId}/itens/atualizar/{itemId}', '\App\Controllers\Admin\FornadaController@updateItem');
$router->get('/admin/fornadas/{fornadaId}/itens/deletar/{itemId}', '\App\Controllers\Admin\FornadaController@deleteItem');

// Rotas de Usuários (Admin)
$router->get('/admin/usuarios', '\App\Controllers\Admin\UsuarioController@index');
$router->get('/admin/usuarios/criar', '\App\Controllers\Admin\UsuarioController@create');
$router->post('/admin/usuarios/salvar', '\App\Controllers\Admin\UsuarioController@store');
$router->get('/admin/usuarios/editar/{id}', '\App\Controllers\Admin\UsuarioController@edit');
$router->post('/admin/usuarios/atualizar/{id}', '\App\Controllers\Admin\UsuarioController@update');
$router->get('/admin/usuarios/deletar/{id}', '\App\Controllers\Admin\UsuarioController@delete');
$router->get('/admin/usuarios/alterar-senha/{id}', '\App\Controllers\Admin\UsuarioController@changePassword');
$router->post('/admin/usuarios/alterar-senha/{id}', '\App\Controllers\Admin\UsuarioController@changePassword');

// Rotas de Pedidos (Admin)
$router->get('/admin/pedidos', '\App\Controllers\Admin\PedidoController@index');
$router->get('/admin/pedidos/ver/{id}', '\App\Controllers\Admin\PedidoController@ver');
$router->get('/admin/pedidos/criar', '\App\Controllers\Admin\PedidoController@create');
$router->post('/admin/pedidos/salvar', '\App\Controllers\Admin\PedidoController@store');
$router->get('/admin/pedidos/editar/{id}', '\App\Controllers\Admin\PedidoController@edit');
$router->post('/admin/pedidos/atualizar/{id}', '\App\Controllers\Admin\PedidoController@update');
$router->get('/admin/pedidos/deletar/{id}', '\App\Controllers\Admin\PedidoController@delete');
$router->get('/admin/pedidos/confirmar_pagamento/{id}', '\App\Controllers\Admin\PedidoController@confirmarPagamento');
$router->get('/admin/pedidos/toggle_entrega/{id}', '\App\Controllers\Admin\PedidoController@toggleEntrega');

// Rotas de Orçamentos (Admin)
$router->get('/admin/orcamentos', '\App\Controllers\Admin\OrcamentoController@index');
$router->get('/admin/orcamentos/create', '\App\Controllers\Admin\OrcamentoController@create');
$router->post('/admin/orcamentos/store', '\App\Controllers\Admin\OrcamentoController@store');
$router->get('/admin/orcamentos/show/{id}', '\App\Controllers\Admin\OrcamentoController@show');
$router->get('/admin/orcamentos/edit/{id}', '\App\Controllers\Admin\OrcamentoController@edit');
$router->post('/admin/orcamentos/update/{id}', '\App\Controllers\Admin\OrcamentoController@update');
$router->post('/admin/orcamentos/delete/{id}', '\App\Controllers\Admin\OrcamentoController@delete');

// Rotas Públicas de Fornadas
$router->get('/fornadas', '\App\Controllers\FornadaController@index');
$router->get('/fornadas/ver/{id}', '\App\Controllers\FornadaController@show');

// Rotas do Carrinho de Compras
$router->post('/carrinho/adicionar', '\App\Controllers\CarrinhoController@adicionar');
$router->get('/carrinho', '\App\Controllers\CarrinhoController@index');
$router->get('/carrinho/remover/{id}', '\App\Controllers\CarrinhoController@remover');
$router->post('/carrinho/atualizar', '\App\Controllers\CarrinhoController@atualizar');

// Rotas de Checkout
$router->get('/checkout', '\App\Controllers\CheckoutController@index');
$router->post('/checkout/processar', '\App\Controllers\CheckoutController@processar');
$router->get('/checkout/sucesso', '\App\Controllers\CheckoutController@sucesso');

// Rota da Conta do Cliente
$router->get('/conta', '\App\Controllers\ContaController@index');


// --- Despacha a Requisição ---
// O roteador encontra a rota correspondente à URL e executa o método do controller.
$router->dispatch();
