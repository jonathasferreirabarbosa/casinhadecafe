<?php /* View para listar os pedidos - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Gerenciamento de Pedidos</h3>
        <a href="/admin/pedidos/criar" class="button-primary">Criar Pedido</a>
    </div>
    <div class="card-content">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($pedidos)): ?>
            <p>Nenhum pedido encontrado.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Fornada</th>
                        <th>Data do Pedido</th>
                        <th>Valor Total</th>
                        <th>Status Pag.</th>
                        <th>Status Pedido</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fornada_titulo']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['data_pedido']))); ?></td>
                            <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                            <td><span class="status-<?php echo htmlspecialchars($pedido['status_pagamento']); ?>"><?php echo htmlspecialchars($pedido['status_pagamento_texto']); ?></span></td>
                            <td><span class="status-<?php echo htmlspecialchars($pedido['status_pedido']); ?>"><?php echo htmlspecialchars($pedido['status_pedido_texto']); ?></span></td>
                            <td class="actions">
                                <a href="/admin/pedidos/ver/<?php echo $pedido['id']; ?>" class="button-secondary">Ver Detalhes</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
    .status-pendente_50 { background-color: #DAA520; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-confirmado_50 { background-color: #556B2F; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pago_total { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-expirado { background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-em_processamento { background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pronto_retirada { background-color: #007bff; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-entregue { background-color: #6c757d; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-cancelado { background-color: #343a40; color: white; padding: 3_px 8px; border-radius: 5px; }
</style>
