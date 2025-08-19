<?php
require_once __DIR__ . '/../config/database.php';

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

        // --- INÍCIO DA LÓGICA DE GERAÇÃO DE PDF (PLACEHOLDER) ---
        // Em um ambiente real, você usaria uma biblioteca como FPDF, TCPDF ou Dompdf aqui.
        // Exemplo básico de como seria a estrutura para gerar um PDF simples:

        // header('Content-Type: application/pdf');
        // header('Content-Disposition: attachment; filename="proposta_' . $proposal['id'] . '.pdf"');

        // require('path/to/fpdf.php'); // Inclua sua biblioteca PDF
        // $pdf = new FPDF();
        // $pdf->AddPage();
        // $pdf->SetFont('Arial','B',16);
        // $pdf->Cell(40,10,'Proposta Comercial #' . $proposal['id']);
        // ... adicione mais conteúdo da proposta ao PDF ...
        // $pdf->Output();

        // Por enquanto, vamos apenas exibir uma mensagem de sucesso e os dados da proposta.
        echo "<h1>Simulação de Geração de PDF para Proposta #" . $proposal['id'] . "</h1>";
        echo "<p>Esta página simula a geração de um PDF. Em uma implementação real, um arquivo PDF seria gerado e baixado aqui.</p>";
        echo "<h2>Detalhes da Proposta:</h2>";
        echo "<p><strong>Cliente:</strong> " . $proposal['client_name'] . "</p>";
        echo "<p><strong>Data:</strong> " . $proposal['proposal_date'] . "</p>
";
        echo "<p><strong>Status:</strong> " . ucfirst($proposal['status']) . "</p>";
        echo "<h3>Itens:</h3>";
        echo "<ul>";
        foreach ($proposal['items'] as $item) {
            echo "<li>" . $item['product_name'] . " (x" . $item['quantity'] . ") - R$ " . number_format($item['total_price'], 2, ',', '.') . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Valor Total:</strong> R$ " . number_format($proposal['total_value'], 2, ',', '.') . "</p>";

        // --- FIM DA LÓGICA DE GERAÇÃO DE PDF (PLACEHOLDER) ---

    } else {
        echo "<p>Proposta não encontrada.</p>";
    }
} else {
    echo "<p>Token de proposta não fornecido.</p>";
}
?>
