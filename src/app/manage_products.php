<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$message = '';

// Lógica para adicionar/editar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $description, $price, $stock])) {
                    $message = "Produto adicionado com sucesso!";
                } else {
                    $message = "Erro ao adicionar produto.";
                }
            } else { // edit
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
                if ($stmt->execute([$name, $description, $price, $stock, $id])) {
                    $message = "Produto atualizado com sucesso!";
                } else {
                    $message = "Erro ao atualizar produto.";
                }
            }
        }
    }
}

// Lógica para deletar produto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "Produto deletado com sucesso!";
    } else {
        $message = "Erro ao deletar produto.";
    }
}

// Lógica para buscar produtos
$products = $pdo->query("SELECT * FROM products")->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
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
<body>
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-md">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Gerenciar Produtos</h1>
                <nav>
                    <a href="/admin-dashboard.php" class="text-blue-600 hover:underline">Voltar ao Dashboard</a>
                </nav>
            </div>
        </header>

        <main class="flex-grow container mx-auto p-6">
            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= $message ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white p-8 rounded-lg shadow-lg mb-6">
                <h2 class="text-2xl font-semibold mb-4">Adicionar/Editar Produto</h2>
                                <form action="/manage_products.php" method="POST">
                    <input type="hidden" name="id" id="product_id">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
                        <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição:</label>
                        <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Preço:</label>
                        <input type="number" step="0.01" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Estoque:</label>
                        <input type="number" name="stock" id="stock" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <button type="submit" name="action" value="add" id="submit_button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Adicionar Produto</button>
                </form>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Lista de Produtos</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Nome</th>
                            <th class="py-2 px-4 border-b">Preço</th>
                            <th class="py-2 px-4 border-b">Estoque</th>
                            <th class="py-2 px-4 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="py-2 px-4 border-b text-center"><?= $product['id'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $product['name'] ?></td>
                                <td class="py-2 px-4 border-b text-right">R$ <?= number_format($product['price'], 2, ',', '.') ?></td>
                                <td class="py-2 px-4 border-b text-center"><?= $product['stock'] ?></td>
                                <td class="py-2 px-4 border-b text-center">
                                    <button onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Editar</button>
                                    <a href="/manage_products.php?delete=<?= $product['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs" onclick="return confirm('Tem certeza que deseja deletar este produto?');">Deletar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function editProduct(product) {
            document.getElementById('product_id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('price').value = product.price;
            document.getElementById('stock').value = product.stock;
            document.getElementById('submit_button').value = 'edit';
            document.getElementById('submit_button').innerText = 'Atualizar Produto';
        }
    </script>
</body>
</html>
