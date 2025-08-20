<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'items' => []];

if (isset($_GET['proposal_id'])) {
    $proposal_id = $_GET['proposal_id'];

    try {
        $stmt = $pdo->prepare("SELECT pi.*, p.name as product_name, p.price as product_price FROM proposal_items pi JOIN products p ON pi.product_id = p.id WHERE pi.proposal_id = ?");
        $stmt->execute([$proposal_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response['success'] = true;
        $response['items'] = $items;

    } catch (PDOException $e) {
        $response['message'] = 'Erro ao buscar itens da proposta: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'ID da proposta não fornecido.';
}

echo json_encode($response);
?>