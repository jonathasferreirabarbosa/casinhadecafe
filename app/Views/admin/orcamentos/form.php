<?php /* View para formulário de orçamento (criar/editar) - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($orcamento['id']) ? 'Editar Orçamento #' . htmlspecialchars($orcamento['id']) : 'Novo Orçamento'; ?></h3>
        <a href="/admin/orcamentos" class="button-secondary">Voltar para Orçamentos</a>
    </div>
    <div class="card-content">
        <form action="/admin/orcamentos/<?php echo isset($orcamento['id']) ? 'update/' . htmlspecialchars($orcamento['id']) : 'store'; ?>" method="POST">
            <div class="form-group">
                <label for="solicitacao_id">ID da Solicitação:</label>
                <input type="number" id="solicitacao_id" name="solicitacao_id" value="<?php echo htmlspecialchars($orcamento['solicitacao_id'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="admin_id">ID do Administrador:</label>
                <input type="number" id="admin_id" name="admin_id" value="<?php echo htmlspecialchars($orcamento['admin_id'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="valor_total">Valor Total:</label>
                <input type="text" id="valor_total" name="valor_total" value="<?php echo htmlspecialchars($orcamento['valor_total'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="valor_sinal">Valor do Sinal:</label>
                <input type="text" id="valor_sinal" name="valor_sinal" value="<?php echo htmlspecialchars($orcamento['valor_sinal'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="pendente_cliente" <?php echo (isset($orcamento['status']) && $orcamento['status'] == 'pendente_cliente') ? 'selected' : ''; ?>>Pendente Cliente</option>
                    <option value="aprovado" <?php echo (isset($orcamento['status']) && $orcamento['status'] == 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                    <option value="recusado_cliente" <?php echo (isset($orcamento['status']) && $orcamento['status'] == 'recusado_cliente') ? 'selected' : ''; ?>>Recusado Cliente</option>
                    <option value="cancelado_admin" <?php echo (isset($orcamento['status']) && $orcamento['status'] == 'cancelado_admin') ? 'selected' : ''; ?>>Cancelado Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data_validade">Data de Validade:</label>
                <input type="date" id="data_validade" name="data_validade" value="<?php echo htmlspecialchars(isset($orcamento['data_validade']) ? date('Y-m-d', strtotime($orcamento['data_validade'])) : ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="observacoes_admin">Observações do Administrador:</label>
                <textarea id="observacoes_admin" name="observacoes_admin"><?php echo htmlspecialchars($orcamento['observacoes_admin'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="button-primary"><?php echo isset($orcamento['id']) ? 'Atualizar Orçamento' : 'Criar Orçamento'; ?></button>
        </form>
    </div>
</div>