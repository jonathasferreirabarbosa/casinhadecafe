<?php /* View para gerenciar os itens de uma fornada específica */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Gerenciar Itens da Fornada: <?php echo htmlspecialchars($fornada['titulo']); ?></h3>
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

        <h4>Adicionar Novo Item à Fornada</h4>
        <?php if (empty($produtosDisponiveis)): ?>
            <div class="alert alert-info">
                Nenhum produto cadastrado. Por favor, <a href="/admin/produtos" class="button-primary">adicione produtos</a> antes de gerenciar os itens da fornada.
            </div>
        <?php else: ?>
        <form action="/admin/fornadas/<?php echo $fornada['id']; ?>/itens/salvar" method="POST" class="form-inline">
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select id="produto_id" name="produto_id" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtosDisponiveis as $produto): ?>
                        <option value="<?php echo $produto['id']; ?>"><?php echo htmlspecialchars($produto['nome'] . ' (' . $produto['tipo_unidade'] . ')'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="preco_unitario">Preço Unitário:</label>
                <input type="number" id="preco_unitario" name="preco_unitario" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="estoque_inicial">Estoque Inicial:</label>
                <input type="number" id="estoque_inicial" name="estoque_inicial" min="0" required>
            </div>
            <button type="submit" class="button-primary">Adicionar Item</button>
        </form>
        <?php endif; ?>

        <hr>

        <h4>Itens Atuais da Fornada</h4>
        <?php if (empty($fornadaItems)): ?>
            <p>Nenhum item adicionado a esta fornada ainda.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Estoque Inicial</th>
                        <th>Estoque Atual</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fornadaItems as $item): ?>
                        <tr>
                            <td><span class="form-control-display"><?php echo htmlspecialchars($item['produto_nome'] . ' (' . $item['tipo_unidade'] . ')'); ?></span></td>
                            <td id="preco_unitario_cell_<?php echo $item['id']; ?>">
                                <span class="display-mode form-control-display">R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></span>
                                <input type="number" id="preco_unitario_edit_<?php echo $item['id']; ?>" name="preco_unitario" value="<?php echo htmlspecialchars($item['preco_unitario']); ?>" step="0.01" min="0" class="edit-mode form-control-inline" style="display:none;">
                            </td>
                            <td id="estoque_inicial_cell_<?php echo $item['id']; ?>">
                                <span class="display-mode form-control-display"><?php echo htmlspecialchars($item['estoque_inicial']); ?></span>
                                <input type="number" id="estoque_inicial_edit_<?php echo $item['id']; ?>" name="estoque_inicial" value="<?php echo htmlspecialchars($item['estoque_inicial']); ?>" min="0" class="edit-mode form-control-inline" style="display:none;">
                            </td>
                            <td id="estoque_atual_cell_<?php echo $item['id']; ?>">
                                <span class="display-mode form-control-display"><?php echo htmlspecialchars($item['estoque_atual']); ?></span>
                                <input type="number" id="estoque_atual_edit_<?php echo $item['id']; ?>" name="estoque_atual" value="<?php echo htmlspecialchars($item['estoque_atual']); ?>" min="0" class="edit-mode form-control-inline" style="display:none;">
                            </td>
                            <td class="actions">
                                <div id="display_actions_<?php echo $item['id']; ?>">
                                    <a href="#" class="button-secondary" onclick="event.preventDefault(); toggleEditMode(<?php echo $item['id']; ?>)">Editar</a>
                                    <a href="/admin/fornadas/<?php echo $fornada['id']; ?>/itens/deletar/<?php echo $item['id']; ?>" class="button-danger" onclick="return confirm('Tem certeza que deseja remover este item da fornada?');">Remover</a>
                                </div>
                                <div id="edit_actions_<?php echo $item['id']; ?>" style="display:none;">
                                    <form action="/admin/fornadas/<?php echo $fornada['id']; ?>/itens/atualizar/<?php echo $item['id']; ?>" method="POST" style="display:inline-block;" onsubmit="return prepareItemUpdate(<?php echo $item['id']; ?>);">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="produto_id" value="<?php echo $item['produto_id']; ?>">
                                        <input type="hidden" name="preco_unitario" id="hidden_preco_unitario_<?php echo $item['id']; ?>">
                                        <input type="hidden" name="estoque_inicial" id="hidden_estoque_inicial_<?php echo $item['id']; ?>">
                                        <input type="hidden" name="estoque_atual" id="hidden_estoque_atual_<?php echo $item['id']; ?>">
                                        <button type="submit" class="button-primary">Salvar</button>
                                        <a href="#" class="button-secondary" onclick="event.preventDefault(); toggleEditMode(<?php echo $item['id']; ?>, true)">Cancelar</a>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
    .form-inline {
        display: flex;
        gap: 15px;
        align-items: flex-end;
        margin-bottom: 20px;
    }
    .form-inline .form-group {
        margin-bottom: 0;
    }
    .form-inline button {
        white-space: nowrap;
    }

    #edit_actions_ form {
        display: flex; /* Use flexbox for alignment */
        gap: 5px; /* Small gap between buttons */
        align-items: center;
    }
</style>

<script>
function prepareItemUpdate(itemId) {
    const precoUnitarioEdit = document.querySelector(`#preco_unitario_cell_${itemId} .edit-mode`);
    const estoqueInicialEdit = document.querySelector(`#estoque_inicial_cell_${itemId} .edit-mode`);
    const estoqueAtualEdit = document.querySelector(`#estoque_atual_cell_${itemId} .edit-mode`);

    const hiddenPrecoUnitario = document.getElementById(`hidden_preco_unitario_${itemId}`);
    const hiddenEstoqueInicial = document.getElementById(`hidden_estoque_inicial_${itemId}`);
    const hiddenEstoqueAtual = document.getElementById(`hidden_estoque_atual_${itemId}`);

    hiddenPrecoUnitario.value = precoUnitarioEdit.value;
    hiddenEstoqueInicial.value = estoqueInicialEdit.value;
    hiddenEstoqueAtual.value = estoqueAtualEdit.value;

    return true; // Allow form submission
}

function toggleEditMode(itemId, cancel = false) {
    const precoUnitarioDisplay = document.querySelector(`#preco_unitario_cell_${itemId} .display-mode`);
    const estoqueInicialDisplay = document.querySelector(`#estoque_inicial_cell_${itemId} .display-mode`);
    const estoqueAtualDisplay = document.querySelector(`#estoque_atual_cell_${itemId} .display-mode`);

    const precoUnitarioEdit = document.querySelector(`#preco_unitario_cell_${itemId} .edit-mode`);
    const estoqueInicialEdit = document.querySelector(`#estoque_inicial_cell_${itemId} .edit-mode`);
    const estoqueAtualEdit = document.querySelector(`#estoque_atual_cell_${itemId} .edit-mode`);

    const displayActions = document.getElementById(`display_actions_${itemId}`);
    const editActions = document.getElementById(`edit_actions_${itemId}`);

    const hiddenPrecoUnitario = document.getElementById(`hidden_preco_unitario_${itemId}`);
    const hiddenEstoqueInicial = document.getElementById(`hidden_estoque_inicial_${itemId}`);
    const hiddenEstoqueAtual = document.getElementById(`hidden_estoque_atual_${itemId}`);

    if (editActions.style.display === 'none' && !cancel) {
        // Enter edit mode
        precoUnitarioDisplay.style.display = 'none';
        estoqueInicialDisplay.style.display = 'none';
        estoqueAtualDisplay.style.display = 'none';

        precoUnitarioEdit.style.display = 'inline-block';
        estoqueInicialEdit.style.display = 'inline-block';
        estoqueAtualEdit.style.display = 'inline-block';

        displayActions.style.display = 'none';
        editActions.style.display = 'inline-block';

        // Set initial values for edit inputs
        precoUnitarioEdit.value = precoUnitarioDisplay.textContent.replace('R$ ', '').replace(',', '.');
        estoqueInicialEdit.value = estoqueInicialDisplay.textContent;
        estoqueAtualEdit.value = estoqueAtualDisplay.textContent;

    } else {
        // Exit edit mode (after save, initial load, or cancel)
        precoUnitarioDisplay.style.display = 'inline-block';
        estoqueInicialDisplay.style.display = 'inline-block';
        estoqueAtualDisplay.style.display = 'inline-block';

        precoUnitarioEdit.style.display = 'none';
        estoqueInicialEdit.style.display = 'none';
        estoqueAtualEdit.style.display = 'none';

        displayActions.style.display = 'inline-block';
        editActions.style.display = 'none';

        if (cancel) {
            // Revert values on cancel
            precoUnitarioEdit.value = precoUnitarioDisplay.textContent.replace('R$ ', '').replace(',', '.');
            estoqueInicialEdit.value = estoqueInicialDisplay.textContent;
            estoqueAtualEdit.value = estoqueAtualDisplay.textContent;
        } else {
            // Populate hidden fields for submission on save
            hiddenPrecoUnitario.value = precoUnitarioEdit.value;
            hiddenEstoqueInicial.value = estoqueInicialEdit.value;
            hiddenEstoqueAtual.value = estoqueAtualEdit.value;
        }
    }
}

// Initialize all items to display mode
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('tr').forEach(row => {
        const precoUnitarioDisplay = row.querySelector('[id^="preco_unitario_cell_"] .display-mode');
        if (precoUnitarioDisplay) {
            const itemId = precoUnitarioDisplay.closest('td').id.replace('preco_unitario_cell_', '');
            toggleEditMode(itemId, true); // Call with cancel=true to ensure display mode
        }
    });
});
</script>
