<?php /* View para o formulário de criação/edição de fornadas - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($fornada['id']) ? 'Editar Fornada' : 'Adicionar Nova Fornada'; ?></h3>
        <a href="/admin/fornadas" class="button-secondary">Voltar para Fornadas</a>
    </div>
    <div class="card-content">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($fornada['id'])): ?>
            <div class="alert alert-info">
                Após salvar as alterações, você poderá gerenciar os itens desta fornada na página de listagem de fornadas.
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Após criar a fornada, você poderá adicionar os itens (produtos) a ela na página de listagem de fornadas.
            </div>
        <?php endif; ?>

        <form action="<?php echo isset($fornada['id']) ? '/admin/fornadas/atualizar/' . $fornada['id'] : '/admin/fornadas/salvar'; ?>" method="POST">
            <div class="form-group">
                <label for="titulo">Título da Fornada:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($fornada['titulo'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao_adicional">Descrição Adicional:</label>
                <textarea id="descricao_adicional" name="descricao_adicional" rows="4"><?php echo htmlspecialchars($fornada['descricao_adicional'] ?? ''); ?></textarea>
            </div>

            <div class="date-group-container">
                <div class="form-group">
                    <label for="data_inicio_pedidos">Data de Início dos Pedidos:</label>
                    <input type="date" id="data_inicio_pedidos" name="data_inicio_pedidos" value="<?php echo isset($fornada['data_inicio_pedidos']) ? date('Y-m-d', strtotime($fornada['data_inicio_pedidos'])) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_fim_pedidos">Data de Fim dos Pedidos:</label>
                    <input type="date" id="data_fim_pedidos" name="data_fim_pedidos" value="<?php echo isset($fornada['data_fim_pedidos']) ? date('Y-m-d', strtotime($fornada['data_fim_pedidos'])) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_entrega">Data de Entrega:</label>
                    <input type="date" id="data_entrega" name="data_entrega" value="<?php echo isset($fornada['data_entrega']) ? date('Y-m-d', strtotime($fornada['data_entrega'])) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="planejada" <?php echo (isset($fornada['status']) && $fornada['status'] == 'planejada') ? 'selected' : ''; ?>>Planejada</option>
                    <option value="ativa" <?php echo (isset($fornada['status']) && $fornada['status'] == 'ativa') ? 'selected' : ''; ?>>Ativa</option>
                    <option value="concluida" <?php echo (isset($fornada['status']) && $fornada['status'] == 'concluida') ? 'selected' : ''; ?>>Concluída</option>
                    <option value="cancelada" <?php echo (isset($fornada['status']) && $fornada['status'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>

            <button type="submit" class="button-primary"><?php echo isset($fornada['id']) ? 'Salvar Alterações' : 'Adicionar Fornada'; ?></button>
        </form>
    </div>
</div>
