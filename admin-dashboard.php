<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Casinha de Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDF8F5;
            color: #4A2E2A;
        }
        h1 {
            font-family: 'Playfair Display', serif;
        }
         .brand-pink-500 {
            background-color: #D9A6A4;
        }
        .brand-pink-600 {
            background-color: #C78C8A;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-md">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Painel de Gestão</h1>
                <a href="logout.php" class="px-4 py-2 font-semibold text-white brand-pink-500 rounded-md hover:brand-pink-600">
                    Sair
                </a>
            </div>
        </header>

        <main class="flex-grow container mx-auto p-6">
            <h2 class="text-3xl font-semibold mb-6">Bem-vinda, Adriana!</h2>
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <p>Este é o seu painel de controlo. Futuramente, aqui poderá gerir os seus produtos, ver as suas encomendas e muito mais.</p>
            </div>
        </main>
    </div>

</body>
</html>
