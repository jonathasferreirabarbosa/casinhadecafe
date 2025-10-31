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
                        <th>Pagamento</th>
                        <th>Entrega</th>
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
                            <td>
                                <?php 
                                    $status = $pedido['status_pagamento'];
                                    $icon_class = 'button-secondary'; // Default to gray
                                    $title = 'Pagamento Expirado';
                                    $onclick = '';
                                    $href = '#';

                                    if ($status == 'pendente') {
                                        $icon_class = 'button-secondary';
                                        $title = 'Confirmar Pagamento';
                                        $href = '/admin/pedidos/confirmar_pagamento/' . $pedido['id'];
                                        $onclick = 'return confirm("Tem certeza que deseja confirmar o pagamento deste pedido?");';
                                    } elseif ($status == 'confirmado' || $status == 'pago_total') {
                                        $icon_class = 'button-success';
                                        $title = 'Pagamento Confirmado';
                                    }
                                ?>
                                <a href="<?php echo $href; ?>" class="button-icon <?php echo $icon_class; ?>" title="<?php echo $title; ?>" onclick="<?php echo $onclick; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                        <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                                    </svg>
                                </a>
                            </td>
                            <td>
                                <a href="/admin/pedidos/toggle_entrega/<?php echo $pedido['id']; ?>" class="button-icon <?php echo $pedido['entregue'] ? 'button-success' : 'button-secondary'; ?>" title="<?php echo $pedido['entregue'] ? 'Marcar como Não Entregue' : 'Marcar como Entregue'; ?>" onclick="return confirm('Tem certeza que deseja alterar o status de entrega deste pedido?');">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.74181 20.5545C4.94143 22 7.17414 22 11.6395 22H12.3607C16.8261 22 19.0589 22 20.2585 20.5545M3.74181 20.5545C2.54219 19.1091 2.95365 16.9146 3.77657 12.5257C4.36179 9.40452 4.65441 7.84393 5.7653 6.92196M3.74181 20.5545C3.74181 20.5545 3.74181 20.5545 3.74181 20.5545ZM20.2585 20.5545C21.4581 19.1091 21.0466 16.9146 20.2237 12.5257C19.6385 9.40452 19.3459 7.84393 18.235 6.92196M20.2585 20.5545C20.2585 20.5545 20.2585 20.5545 20.2585 20.5545ZM18.235 6.92196C17.1241 6 15.5363 6 12.3607 6H11.6395C8.46398 6 6.8762 6 5.7653 6.92196M18.235 6.92196C18.235 6.92196 18.235 6.92196 18.235 6.92196ZM5.7653 6.92196C5.7653 6.92196 5.7653 6.92196 5.7653 6.92196Z" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M10 14.3C10.5207 14.7686 10.8126 15.0314 11.3333 15.5L14 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 6V5C9 3.34315 10.3431 2 12 2C13.6569 2 15 3.34315 15 5V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                            </td>
                            <td class="actions">
                                <a href="/admin/pedidos/ver/<?php echo $pedido['id']; ?>" class="button-icon button-secondary" title="Ver Detalhes">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </a>
                                <a href="/admin/pedidos/editar/<?php echo $pedido['id']; ?>" class="button-icon button-secondary" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </a>
                                <a href="/admin/pedidos/deletar/<?php echo $pedido['id']; ?>" class="button-icon button-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este pedido? Esta ação não pode ser desfeita.');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                                    </svg>
                                </a>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
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

    .status-pendente { background-color: #DAA520; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-confirmado { background-color: #556B2F; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-pago_total { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 5px; }
    .status-expirado { background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 5px; }
</style>
