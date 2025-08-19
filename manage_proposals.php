<?php
session_start();
require_once 'db_connect.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

// Função para gerar um token único
function generateUniqueToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Lógica para adicionar/editar proposta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $client_id = $_POST['client_id'];
            $proposal_date = $_POST['proposal_date'];
            $product_ids = $_POST['product_id'];
            $quantities = $_POST['quantity'];

            if ($_POST['action'] === 'add') {
                $unique_url_token = generateUniqueToken();
                $stmt = $pdo->prepare("INSERT INTO proposals (client_id, proposal_date, unique_url_token) VALUES (?, ?, ?)");
                if ($stmt->execute([$client_id, $proposal_date, $unique_url_token])) {
                    $proposal_id = $pdo->lastInsertId();

                    // Inserir itens da proposta
                    foreach ($product_ids as $index => $product_id) {
                        $quantity = $quantities[$index];
                        // Buscar preço atual do produto
                        $product_stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                        $product_stmt->execute([$product_id]);
                        $product_data = $product_stmt->fetch();
                        $unit_price = $product_data['price'];
                        $total_price = $unit_price * $quantity;

                        $item_stmt = $pdo->prepare("INSERT INTO proposal_items (proposal_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
                        $item_stmt->execute([$proposal_id, $product_id, $quantity, $unit_price, $total_price]);
                    }

                    $message = "Proposta adicionada com sucesso! URL Única: <a href=\"view_proposal.php?token={$unique_url_token}\" target="_blank" class="text-blue-600 hover:underline">view_proposal.php?token={$unique_url_token}</a>";
                } else {
                    $message = "Erro ao adicionar proposta.";
                }
            } else { // edit
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE proposals SET client_id = ?, proposal_date = ? WHERE id = ?");
                if ($stmt->execute([$client_id, $proposal_date, $id])) {
                    // Deletar itens antigos e inserir novos
                    $pdo->prepare("DELETE FROM proposal_items WHERE proposal_id = ?")->execute([$id]);
                    foreach ($product_ids as $index => $product_id) {
                        $quantity = $quantities[$index];
                        $product_stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                        $product_stmt->execute([$product_id]);
                        $product_data = $product_stmt->fetch();
                        $unit_price = $product_data['price'];
                        $total_price = $unit_price * $quantity;

                        $item_stmt = $pdo->prepare("INSERT INTO proposal_items (proposal_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
                        $item_stmt->execute([$id, $product_id, $quantity, $unit_price, $total_price]);
                    }
                    $message = "Proposta atualizada com sucesso!";
                } else {
                    $message = "Erro ao atualizar proposta.";
                }
            }
        }
    }
}

// Lógica para deletar proposta
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Deletar itens da proposta primeiro
    $pdo->prepare("DELETE FROM proposal_items WHERE proposal_id = ?")->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM proposals WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "Proposta deletada com sucesso!";
    } else {
        $message = "Erro ao deletar proposta.";
    }
}

// Lógica para buscar propostas
$proposals = $pdo->query("SELECT p.*, c.name as client_name FROM proposals p JOIN clients c ON p.client_id = c.id")->fetchAll();

// Buscar clientes e produtos para os formulários
$clients = $pdo->query("SELECT id, name FROM clients")->fetchAll();
$products = $pdo->query("SELECT id, name, price FROM products")->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Propostas</title>
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
                <h1 class="text-2xl font-bold">Gerenciar Propostas</h1>
                <nav>
                    <a href="admin-dashboard.php" class="text-blue-600 hover:underline">Voltar ao Dashboard</a>
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
                <h2 class="text-2xl font-semibold mb-4">Adicionar/Editar Proposta</h2>
                <form action="manage_proposals.php" method="POST">
                    <input type="hidden" name="id" id="proposal_id">
                    <div class="mb-4">
                        <label for="client_id" class="block text-gray-700 text-sm font-bold mb-2">Cliente:</label>
                        <select name="client_id" id="client_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>"><?= $client['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="proposal_date" class="block text-gray-700 text-sm font-bold mb-2">Data da Proposta:</label>
                        <input type="date" name="proposal_date" id="proposal_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div id="product_items_container">
                        <h3 class="text-xl font-semibold mb-2">Itens da Proposta</h3>
                        <div class="product-item mb-4 p-4 border rounded">
                            <div class="mb-2">
                                <label for="product_id_0" class="block text-gray-700 text-sm font-bold mb-2">Produto:</label>
                                <select name="product_id[]" id="product_id_0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>"><?= $product['name'] ?> (R$ <?= number_format($product['price'], 2, ",", ".") ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="quantity_0" class="block text-gray-700 text-sm font-bold mb-2">Quantidade:</label>
                                <input type="number" name="quantity[]" id="quantity_0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="1" value="1" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add_product_item" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Adicionar Outro Produto</button>

                    <button type="submit" name="action" value="add" id="submit_button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Adicionar Proposta</button>
                </form>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Lista de Propostas</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Cliente</th>
                            <th class="py-2 px-4 border-b">Data</th>
                            <th class="py-2 px-4 border-b">Status</th>
                            <th class="py-2 px-4 border-b">URL Única</th>
                            <th class="py-2 px-4 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proposals as $proposal): ?>
                            <tr>
                                <td class="py-2 px-4 border-b text-center"><?= $proposal['id'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $proposal['client_name'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $proposal['proposal_date'] ?></td>
                                <td class="py-2 px-4 border-b text-center"><?= ucfirst($proposal['status']) ?></td>
                                <td class="py-2 px-4 border-b">
                                    <a href="view_proposal.php?token=<?= $proposal['unique_url_token'] ?>" target="_blank" class="text-blue-600 hover:underline">Ver Proposta</a>
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    <button onclick="editProposal(<?= htmlspecialchars(json_encode($proposal)) ?>)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Editar</button>
                                    <a href="manage_proposals.php?delete=<?= $proposal['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs" onclick="return confirm('Tem certeza que deseja deletar esta proposta?');">Deletar</a>
                                    <a href="generate_pdf.php?token=<?= $proposal['unique_url_token'] ?>" target="_blank" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Gerar PDF</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const products = <?= json_encode($products) ?>;
        let productItemCount = 1;

        document.getElementById('add_product_item').addEventListener('click', function() {
            const container = document.getElementById('product_items_container');
            const newItem = document.createElement('div');
            newItem.classList.add('product-item', 'mb-4', 'p-4', 'border', 'rounded');

            let productOptions = '';
            products.forEach(product => {
                productOptions += `<option value="${product.id}" data-price="${product.price}">${product.name} (R$ ${parseFloat(product.price).toFixed(2).replace('.', ',')})</option>`;
            });

            newItem.innerHTML = `
                <div class="mb-2">
                    <label for="product_id_${productItemCount}" class="block text-gray-700 text-sm font-bold mb-2">Produto:</label>
                    <select name="product_id[]" id="product_id_${productItemCount}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        ${productOptions}
                    </select>
                </div>
                <div class="mb-2">
                    <label for="quantity_${productItemCount}" class="block text-gray-700 text-sm font-bold mb-2">Quantidade:</label>
                    <input type="number" name="quantity[]" id="quantity_${productItemCount}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="1" value="1" required>
                </div>
                <button type="button" onclick="this.closest('.product-item').remove()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Remover</button>
            `;
            container.appendChild(newItem);
            productItemCount++;
        });

        function editProposal(proposal) {
            document.getElementById('proposal_id').value = proposal.id;
            document.getElementById('client_id').value = proposal.client_id;
            document.getElementById('proposal_date').value = proposal.proposal_date;
            document.getElementById('submit_button').value = 'edit';
            document.getElementById('submit_button').innerText = 'Atualizar Proposta';

            // Limpar itens de produto existentes para edição
            const container = document.getElementById('product_items_container');
            container.innerHTML = '<h3 class="text-xl font-semibold mb-2">Itens da Proposta</h3>';
            productItemCount = 0;

            // Você precisaria de uma requisição AJAX para buscar os itens da proposta para preencher aqui
            // Por simplicidade, para a edição, o usuário terá que readicionar os itens manualmente ou você pode implementar a busca via AJAX.
            // Exemplo de como seria a estrutura se você buscasse os itens:
            /*
            fetch(`get_proposal_items.php?proposal_id=${proposal.id}`)
                .then(response => response.json())
                .then(items => {
                    items.forEach(item => {
                        // Adicionar item ao formulário
                    });
                });
            */
        }
    </script>
</body>
</html>