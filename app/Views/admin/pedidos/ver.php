<?php /* View para exibir os detalhes de um pedido - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalhes do Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h3>
        <a href="/admin/pedidos" class="button-secondary">Voltar para Pedidos</a>
    </div>
    <div class="card-content">
        
        <div class="pedido-info">
            <h4>Informações do Cliente</h4>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['cliente_nome']); ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($pedido['cliente_email']); ?></p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($pedido['cliente_telefone']); ?></p>
        </div>

        <div class="pedido-info">
            <h4>Informações do Pedido</h4>
            <p><strong>Fornada:</strong> <?php echo htmlspecialchars($pedido['fornada_titulo']); ?></p>
            <p><strong>Data do Pedido:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['data_pedido']))); ?></p>
            <p><strong>Valor Total:</strong> R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></p>
            <p><strong>Status Pagamento:</strong> <span class="status-<?php echo htmlspecialchars($pedido['status_pagamento']); ?>"><?php echo htmlspecialchars($pedido['status_pagamento_texto']); ?></span></p>
            <p><strong>Status Pedido:</strong> <span class="status-<?php echo htmlspecialchars($pedido['status_pedido']); ?>"><?php echo htmlspecialchars($pedido['status_pedido_texto']); ?></span></p>
        </div>

        <div class="pedido-itens">
            <h4>Itens do Pedido</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido['itens'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                            <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($item['quantidade'] * $item['preco_unitario'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    .pedido-info, .pedido-itens { margin-bottom: 2rem; }
    .pedido-info h4, .pedido-itens h4 { font-family: 'Playfair Display', serif; color: #3E2723; margin-bottom: 1rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
    .pedido-info p { margin-bottom: 0.5rem; }
    .status-pendente_50 { background-color: #DAA520; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-confirmado_50 { background-color: #556B2F; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pago_total { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-expirado { background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-em_processamento { background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pronto_retirada { background-color: #007bff; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-entregue { background-color: #6c757d; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-cancelado { background-color: #343a40; color: white; padding: 3px 8px; border-radius: 5px; }
</style>
