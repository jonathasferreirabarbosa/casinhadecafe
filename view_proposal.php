<?php
require_once 'db_connect.php';

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
    <title>Visualizar Proposta</title>
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
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
            <?php if ($message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= $message ?></span>
                </div>
            <?php elseif ($proposal): ?>
                <h1 class="text-3xl font-bold mb-6 text-center">Proposta Comercial #<?= $proposal['id'] ?></h1>
                
                <div class="mb-4">
                    <p><strong class="font-semibold">Cliente:</strong> <?= $proposal['client_name'] ?></p>
                    <p><strong class="font-semibold">Email:</strong> <?= $proposal['client_email'] ?></p>
                    <p><strong class="font-semibold">Data da Proposta:</strong> <?= date('d/m/Y', strtotime($proposal['proposal_date'])) ?></p>
                    <p><strong class="font-semibold">Status:</strong> <span class="font-bold <?= ($proposal['status'] == 'accepted') ? 'text-green-600' : (($proposal['status'] == 'rejected') ? 'text-red-600' : 'text-yellow-600') ?>"><?= ucfirst($proposal['status']) ?></span></p>
                    <?php if ($proposal['status'] == 'accepted'): ?>
                        <p><strong class="font-semibold">Data de Aceitação:</strong> <?= date('d/m/Y H:i', strtotime($proposal['client_acceptance_date'])) ?></p>
                    <?php endif; ?>
                </div>

                <h2 class="text-2xl font-semibold mb-4">Itens da Proposta</h2>
                <table class="min-w-full bg-white mb-6">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Produto</th>
                            <th class="py-2 px-4 border-b text-center">Quantidade</th>
                            <th class="py-2 px-4 border-b text-right">Preço Unitário</th>
                            <th class="py-2 px-4 border-b text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proposal['items'] as $item): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?= $item['product_name'] ?></td>
                                <td class="py-2 px-4 border-b text-center"><?= $item['quantity'] ?></td>
                                <td class="py-2 px-4 border-b text-right">R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                                <td class="py-2 px-4 border-b text-right">R$ <?= number_format($item['total_price'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="py-2 px-4 text-right">Valor Total da Proposta:</th>
                            <th class="py-2 px-4 text-right">R$ <?= number_format($proposal['total_value'], 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>

                <div class="flex justify-center space-x-4">
                    <a href="generate_pdf.php?token=<?= $proposal['unique_url_token'] ?>" target="_blank" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Baixar PDF</a>
                    <?php if ($proposal['status'] == 'pending'): ?>
                        <a href="accept_proposal.php?token=<?= $proposal['unique_url_token'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Tem certeza que deseja aceitar esta proposta?');">Aceitar Proposta</a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>
    </div>
</body>
</html>
