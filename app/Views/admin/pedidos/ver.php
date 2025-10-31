<?php /* View para exibir os detalhes de um pedido - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalhes do Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h3>
        <a href="/admin/pedidos" class="button-secondary">Voltar para Pedidos</a>
    </div>

    <div class="card-content quick-actions">
        <h4>Ações Rápidas</h4>
        <?php
        $is_payment_pending = ($pedido['status_pagamento'] == 'pendente');
        $payment_button_class = $is_payment_pending ? 'button-secondary' : 'button-success';
        $payment_button_href = $is_payment_pending ? "/admin/pedidos/confirmar_pagamento/{$pedido['id']}" : "javascript:void(0);";
        $payment_button_onclick = $is_payment_pending ? "return confirm('Tem certeza que deseja confirmar o pagamento deste pedido?');" : "";
        $payment_button_title = $is_payment_pending ? "Confirmar Pagamento" : "Pagamento Confirmado";
        ?>
        <a href="<?php echo $payment_button_href; ?>" class="button-icon <?php echo $payment_button_class; ?>" title="<?php echo $payment_button_title; ?>" onclick="<?php echo $payment_button_onclick; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
            </svg>
        </a>
        <a href="/admin/pedidos/toggle_entrega/<?php echo $pedido['id']; ?>" class="button-icon <?php echo $pedido['entregue'] ? 'button-success' : 'button-secondary'; ?>" title="<?php echo $pedido['entregue'] ? 'Marcar como Não Entregue' : 'Marcar como Entregue'; ?>" onclick="return confirm('Tem certeza que deseja alterar o status de entrega deste pedido?');">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.74181 20.5545C4.94143 22 7.17414 22 11.6395 22H12.3607C16.8261 22 19.0589 22 20.2585 20.5545M3.74181 20.5545C2.54219 19.1091 2.95365 16.9146 3.77657 12.5257C4.36179 9.40452 4.65441 7.84393 5.7653 6.92196M3.74181 20.5545C3.74181 20.5545 3.74181 20.5545 3.74181 20.5545ZM20.2585 20.5545C21.4581 19.1091 21.0466 16.9146 20.2237 12.5257C19.6385 9.40452 19.3459 7.84393 18.235 6.92196M20.2585 20.5545C20.2585 20.5545 20.2585 20.5545 20.2585 20.5545ZM18.235 6.92196C17.1241 6 15.5363 6 12.3607 6H11.6395C8.46398 6 6.8762 6 5.7653 6.92196M18.235 6.92196C18.235 6.92196 18.235 6.92196 18.235 6.92196ZM5.7653 6.92196C5.7653 6.92196 5.7653 6.92196 5.7653 6.92196Z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M10 14.3C10.5207 14.7686 10.8126 15.0314 11.3333 15.5L14 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 6V5C9 3.34315 10.3431 2 12 2C13.6569 2 15 3.34315 15 5V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </a>
        <a href="/admin/pedidos/editar/<?php echo $pedido['id']; ?>" class="button-icon button-secondary" title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
            </svg>
        </a>
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
            <p><strong>Status Entrega:</strong> <?php echo $pedido['entregue'] ? 'Entregue' : 'Não Entregue'; ?></p>
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
    .quick-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
    }
    .quick-actions h4 {
        margin: 0;
    }
    .button-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        text-decoration: none;
        color: white;
    }
    .button-icon svg {
        width: 16px;
        height: 16px;
    }
    .button-success { background-color: #28a745; }
    .button-danger { background-color: #dc3545; }
    .button-secondary { background-color: #6c757d; }

    .pedido-info, .pedido-itens { margin-bottom: 2rem; }
    .pedido-info h4, .pedido-itens h4 { font-family: 'Playfair Display', serif; color: #3E2723; margin-bottom: 1rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
    .pedido-info p { margin-bottom: 0.5rem; }
    .status-pendente { background-color: #DAA520; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-confirmado { background-color: #556B2F; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pago_total { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-expirado { background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-em_processamento { background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pronto_retirada { background-color: #007bff; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-entregue { background-color: #6c757d; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-cancelado { background-color: #343a40; color: white; padding: 3px 8px; border-radius: 5px; }
</style>
