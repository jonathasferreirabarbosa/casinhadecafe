<?php /* View para listar orçamentos - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Orçamentos</h3>
        <a href="/admin/orcamentos/create" class="button-primary">Novo Orçamento</a>
    </div>
    <div class="card-content">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitação ID</th>
                    <th>Admin ID</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Data Criação</th>
                    <th>Data Validade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orcamentos)): ?>
                    <?php foreach ($orcamentos as $orcamento): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($orcamento['id']); ?></td>
                            <td><?php echo htmlspecialchars($orcamento['solicitacao_id']); ?></td>
                            <td><?php echo htmlspecialchars($orcamento['admin_id']); ?></td>
                            <td>R$ <?php echo number_format($orcamento['valor_total'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($orcamento['status']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($orcamento['data_criacao']))); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($orcamento['data_validade']))); ?></td>
                            <td>
                                <a href="/admin/orcamentos/show/<?php echo $orcamento['id']; ?>" class="button-icon button-secondary" title="Ver">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </a>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum orçamento encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>