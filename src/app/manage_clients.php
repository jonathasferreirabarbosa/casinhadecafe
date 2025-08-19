<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
    exit;
}

$message = '';

// Lógica para adicionar/editar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $email, $phone, $address])) {
                    $message = "Cliente adicionado com sucesso!";
                } else {
                    $message = "Erro ao adicionar cliente.";
                }
            } else { // edit
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE clients SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
                if ($stmt->execute([$name, $email, $phone, $address, $id])) {
                    $message = "Cliente atualizado com sucesso!";
                } else {
                    $message = "Erro ao atualizar cliente.";
                }
            }
        }
    }
}

// Lógica para deletar cliente
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "Cliente deletado com sucesso!";
    } else {
        $message = "Erro ao deletar cliente.";
    }
}

// Lógica para buscar clientes
$clients = $pdo->query("SELECT * FROM clients")->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Clientes</title>
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
                <h1 class="text-2xl font-bold">Gerenciar Clientes</h1>
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
                <h2 class="text-2xl font-semibold mb-4">Adicionar/Editar Cliente</h2>
                <form action="/manage_clients.php" method="POST">
                    <input type="hidden" name="id" id="client_id">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
                        <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Telefone:</label>
                        <input type="text" name="phone" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Endereço:</label>
                        <textarea name="address" id="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <button type="submit" name="action" value="add" id="submit_button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Adicionar Cliente</button>
                </form>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Lista de Clientes</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Nome</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Telefone</th>
                            <th class="py-2 px-4 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td class="py-2 px-4 border-b text-center"><?= $client['id'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $client['name'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $client['email'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $client['phone'] ?></td>
                                <td class="py-2 px-4 border-b text-center">
                                    <button onclick="editClient(<?= htmlspecialchars(json_encode($client)) ?>)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Editar</button>
                                    <a href="/manage_clients.php?delete=<?= $client['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs" onclick="return confirm('Tem certeza que deseja deletar este cliente?');">Deletar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function editClient(client) {
            document.getElementById('client_id').value = client.id;
            document.getElementById('name').value = client.name;
            document.getElementById('email').value = client.email;
            document.getElementById('phone').value = client.phone;
            document.getElementById('address').value = client.address;
            document.getElementById('submit_button').value = 'edit';
            document.getElementById('submit_button').innerText = 'Atualizar Cliente';
        }
    </script>
</body>
</html>
