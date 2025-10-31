<?php /* View para exibir detalhes de um orçamento - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalhes do Orçamento #<?php echo htmlspecialchars($orcamento['id']); ?></h3>
        <a href="/admin/orcamentos" class="button-secondary">Voltar para Orçamentos</a>
    </div>

    <div class="card-content">
        <div class="orcamento-info">
            <p><strong>ID da Solicitação:</strong> <?php echo htmlspecialchars($orcamento['solicitacao_id']); ?></p>
            <p><strong>ID do Administrador:</strong> <?php echo htmlspecialchars($orcamento['admin_id']); ?></p>
            <p><strong>Valor Total:</strong> R$ <?php echo number_format($orcamento['valor_total'], 2, ',', '.'); ?></p>
            <p><strong>Valor do Sinal:</strong> R$ <?php echo number_format($orcamento['valor_sinal'], 2, ',', '.'); ?></p>
            <p><strong>Link Hash:</strong> <?php echo htmlspecialchars($orcamento['link_hash']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($orcamento['status']); ?></p>
            <p><strong>Data de Criação:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($orcamento['data_criacao']))); ?></p>
            <p><strong>Data de Validade:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($orcamento['data_validade']))); ?></p>
            <p><strong>Observações do Administrador:</strong> <?php echo nl2br(htmlspecialchars($orcamento['observacoes_admin'] ?? 'N/A')); ?></p>
        </div>

        <div class="quick-actions" style="margin-top: 20px;">
            <a href="/admin/orcamentos/edit/<?php echo $orcamento['id']; ?>" class="button-icon button-secondary" title="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                </svg>
            </a>
            <form action="/admin/orcamentos/delete/<?php echo $orcamento['id']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?');">
                <button type="submit" class="button-icon button-danger" title="Excluir">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.5a.5.5 0 0 0 0 1h.5V15a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3.5h.5a.5.5 0 0 0 0-1H11ZM4.5 3h7V15h-7V3Z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>