<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
?>
<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Bem-vindo ao Painel de Administração!</h2>
    <p class="text-gray-600">Use o menu lateral para navegar entre as diferentes seções de gerenciamento.</p>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
            <h3 class="font-bold text-lg text-blue-700">Clientes</h3>
            <p class="text-gray-600">Gerencie seus clientes.</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg shadow-sm">
            <h3 class="font-bold text-lg text-green-700">Produtos</h3>
            <p class="text-gray-600">Gerencie seus produtos.</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg shadow-sm">
            <h3 class="font-bold text-lg text-purple-700">Propostas</h3>
            <p class="text-gray-600">Gerencie suas propostas.</p>
        </div>
    </div>
</div>