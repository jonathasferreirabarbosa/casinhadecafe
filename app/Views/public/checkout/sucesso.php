<?php /* View para a página de sucesso do pedido */ ?>

<div class="container my-5 text-center">
    <h2><?php echo htmlspecialchars($titulo); ?> 🎉</h2>
    
    <p class="lead">Sua pré-reserva foi realizada com sucesso!</p>

    <div class="card my-4 mx-auto" style="max-width: 600px;">
        <div class="card-header">
            Resumo do Pedido #<?php echo htmlspecialchars($pedido['id']); ?>
        </div>
        <div class="card-body">
            <p><strong>Valor Total do Pedido:</strong> R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></p>
            <h5 class="mt-4">Pague o sinal de 50% para confirmar:</h5>
            <p class="display-4 text-success">R$ <?php echo number_format($pedido['valor_total'] / 2, 2, ',', '.'); ?></p>
            
            <h5 class="mt-4">Chave PIX para Pagamento:</h5>
            <div class="alert alert-info">
                <strong><?php echo htmlspecialchars($chavePix); ?></strong>
            </div>
            <p class="text-muted">Por favor, envie o comprovante para nosso WhatsApp para agilizar a confirmação.</p>
        </div>
        <div class="card-footer">
            <a href="/" class="btn btn-primary">Voltar para a Home</a>
            <a href="/conta/pedidos" class="btn btn-secondary">Ver Meus Pedidos</a>
        </div>
    </div>

</div>
