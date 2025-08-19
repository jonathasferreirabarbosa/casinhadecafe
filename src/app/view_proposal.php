<?php
require_once __DIR__ . '/../config/database.php';

$proposal = null;
$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT p.*, c.name as client_name, c.email as client_email FROM proposals p JOIN clients c ON p.client_id = c.id WHERE p.unique_url_token = ?");
    $stmt->execute([$token]);
    $proposal = $stmt->fetch();

    if ($proposal) {
        // Buscar itens da proposta
        $items_stmt = $pdo->prepare("SELECT pi.*, pr.name as product_name FROM proposal_items pi JOIN products pr ON pi.product_id = pr.id WHERE pi.proposal_id = ?");
        $items_stmt->execute([$proposal['id']]);
        $proposal['items'] = $items_stmt->fetchAll();

        // Calcular total da proposta
        $total_proposal_value = 0;
        foreach ($proposal['items'] as $item) {
            $total_proposal_value += $item['total_price'];
        }
        $proposal['total_value'] = $total_proposal_value;

    } else {
        $message = "Proposta não encontrada.";
    }
} else {
    $message = "Token de proposta não fornecido.";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposta Comercial - Casinha de Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDF8F5;
            color: #4A2E2A;
            background-image: url('https://www.transparenttextures.com/patterns/subtle-white-feathers.png');
        }
        .playfair {
            font-family: 'Playfair Display', serif;
        }
        .proposal-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .proposal-header {
            background-color: #D9A6A4; /* brand-pink-500 */
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .proposal-header h1 {
             font-size: 2.5rem;
        }
        .proposal-body {
            padding: 2rem;
        }
        .proposal-footer {
            padding: 2rem;
            text-align: center;
            background-color: #F7F7F7;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .status-pendente {
            background-color: #FEF3C7; /* yellow-100 */
            color: #92400E; /* yellow-800 */
        }
        .status-aceita {
            background-color: #D1FAE5; /* green-100 */
            color: #065F46; /* green-800 */
        }
        .status-rejeitada {
            background-color: #FEE2E2; /* red-100 */
            color: #991B1B; /* red-800 */
        }
    </style>
</head>
<body>
    <div class="proposal-container">
        <?php if ($message && !$proposal): ?>
            <div class="proposal-body">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= $message ?></span>
                </div>
            </div>
        <?php elseif ($proposal): ?>
            <div class="proposal-header">
                <h1 class="playfair">Casinha de Café</h1>
                <p class="text-lg">Proposta Comercial</p>
            </div>

            <div class="proposal-body">
                <?php if (isset($_GET['message'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= htmlspecialchars($_GET['message']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div>
                        <h2 class="text-xl font-bold mb-2 playfair">Para:</h2>
                        <p><?= $proposal['client_name'] ?></p>
                        <p><?= $proposal['client_email'] ?></p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-bold mb-2 playfair">Proposta #<?= $proposal['id'] ?></h2>
                        <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($proposal['proposal_date'])) ?></p>
                        <p><strong>Status:</strong> <span class="status-badge status-<?= $proposal['status'] ?>"><?= ucfirst($proposal['status']) ?></span></p>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold mb-4 playfair">Itens da Proposta</h2>
                <table class="min-w-full bg-white mb-6">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 border-b text-left font-semibold">Produto</th>
                            <th class="py-3 px-4 border-b text-center font-semibold">Quantidade</th>
                            <th class="py-3 px-4 border-b text-right font-semibold">Preço Unitário</th>
                            <th class="py-3 px-4 border-b text-right font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proposal['items'] as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b"><?= $item['product_name'] ?></td>
                                <td class="py-3 px-4 border-b text-center"><?= $item['quantity'] ?></td>
                                <td class="py-3 px-4 border-b text-right">R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                                <td class="py-3 px-4 border-b text-right">R$ <?= number_format($item['total_price'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="3" class="py-3 px-4 text-right font-bold">Valor Total da Proposta:</th>
                            <th class="py-3 px-4 text-right font-bold">R$ <?= number_format($proposal['total_value'], 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>

                <?php if ($proposal['status'] == 'pendente'): ?>
                    <div class="text-center mb-6">
                        <p class="mb-4">Para aceitar esta proposta, por favor, clique no botão abaixo.</p>
                        <a href="/accept_proposal.php?token=<?= $proposal['unique_url_token'] ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline text-lg" onclick="return confirm('Tem certeza que deseja aceitar esta proposta?');">Aceitar Proposta</a>
                    </div>
                <?php elseif ($proposal['status'] == 'aceita'): ?>
                     <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="font-semibold text-green-800">Proposta aceita em <?= date('d/m/Y à\s H:i', strtotime($proposal['client_acceptance_date'])) ?>.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="proposal-footer">
                <p class="text-sm text-gray-600">Se tiver alguma dúvida, entre em contato conosco.</p>
                <p class="text-sm text-gray-500 mt-1">Casinha de Café - contato@casinhadecafe.com</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
