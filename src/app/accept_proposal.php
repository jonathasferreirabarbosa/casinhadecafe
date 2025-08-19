<?php
require_once __DIR__ . '/../config/database.php';

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $pdo->prepare("UPDATE proposals SET status = 'aceita', client_acceptance_date = NOW() WHERE unique_url_token = ? AND status = 'pendente'");
        $stmt->execute([$token]);

        if ($stmt->rowCount() > 0) {
            $message = "Proposta aceita com sucesso!";
        } else {
            $message = "Proposta não encontrada ou já foi aceita/rejeitada.";
        }
    } catch (PDOException $e) {
        $message = "Erro ao aceitar proposta: " . $e->getMessage();
    }
} else {
    $message = "Token de proposta não fornecido.";
}

// Redireciona de volta para a visualização da proposta com uma mensagem
header("Location: /view_proposal.php?token={$token}&message=" . urlencode($message));
exit;
?>
