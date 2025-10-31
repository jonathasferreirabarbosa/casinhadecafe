<?php /* View para o formulário de edição de pedidos pelo admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo htmlspecialchars($titulo); ?> #<?php echo htmlspecialchars($pedido['id']); ?></h3>
        <a href="/admin/pedidos" class="button-secondary">Voltar para Pedidos</a>
    </div>
    <div class="card-content">

        <form action="/admin/pedidos/atualizar/<?php echo $pedido['id']; ?>" method="POST" id="form-pedido">
            
            <div class="form-group">
                <label for="cliente_id">Cliente:</label>
                <select id="cliente_id" name="cliente_id" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php echo ($pedido['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cliente['nome'] . ' (' . $cliente['email'] . ')'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fornada_id">Fornada:</label>
                <select id="fornada_id" name="fornada_id" required>
                    <option value="">Selecione uma fornada</option>
                    <?php foreach ($fornadas as $fornada): ?>
                        <option value="<?php echo $fornada['id']; ?>" <?php echo ($pedido['fornada_id'] == $fornada['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($fornada['titulo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h4>Itens do Pedido</h4>
            <div id="itens-pedido-container">
                <!-- Itens do pedido serão renderizados aqui via JavaScript -->
            </div>

            <div id="total-pedido-container" style="text-align: right; margin-top: 10px;">
                <strong>Total do Pedido: <span id="total-pedido">R$ 0,00</span></strong>
            </div>

            <div class="form-group">
                <button type="button" id="add-item-btn" class="button-secondary">Adicionar Item</button>
            </div>

            <div class="form-group">
                <label for="status_pagamento">Status do Pagamento:</label>
                <select id="status_pagamento" name="status_pagamento" class="form-control">
                    <option value="pendente" <?php echo ($pedido['status_pagamento'] == 'pendente') ? 'selected' : ''; ?>>Aguardando Pagamento</option>
                    <option value="confirmado" <?php echo ($pedido['status_pagamento'] == 'confirmado') ? 'selected' : ''; ?>>Pagamento Confirmado</option>
                    <option value="pago_total" <?php echo ($pedido['status_pagamento'] == 'pago_total') ? 'selected' : ''; ?>>Pago Integralmente</option>
                    <option value="expirado" <?php echo ($pedido['status_pagamento'] == 'expirado') ? 'selected' : ''; ?>>Expirado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="entregue">Pedido Entregue:</label>
                <input type="checkbox" id="entregue" name="entregue" value="1" <?php echo ($pedido['entregue']) ? 'checked' : ''; ?>>
            </div>

            <button type="submit" class="button-primary">Salvar Alterações</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fornadaSelect = document.getElementById('fornada_id');
    const itensContainer = document.getElementById('itens-pedido-container');
    const addItemBtn = document.getElementById('add-item-btn');
    const initialFornadaId = '<?php echo $pedido["fornada_id"]; ?>';

    window.itensFornada = [];
    window.totalItensFornada = 0;
    const initialItems = <?php echo json_encode($pedido['itens']); ?>;

    function fetchFornadaItems(fornadaId, callback) {
        fetch(`/admin/fornadas/itens/${fornadaId}`)
            .then(response => response.json())
            .then(data => {
                window.itensFornada = data;
                window.totalItensFornada = data.length;
                if (callback) {
                    callback();
                }
            });
    }

    function renderInitialItems() {
        initialItems.forEach((item, index) => {
            const itemHtml = createItemSelect(window.itensFornada, index, [], item);
            if (itemHtml) {
                itensContainer.insertAdjacentHTML('beforeend', itemHtml);
                const newRow = itensContainer.lastElementChild;
                const selectInput = newRow.querySelector('select');
                const quantityInput = newRow.querySelector('input[type="number"]');
                selectInput.value = item.item_fornada_id;
                quantityInput.value = item.quantidade;
            }
        });
        updateAllSelects();
    }

    function createItemSelect(itens, index, selectedIds = [], currentItem = null) {
        if (!itens || itens.length === 0) return '';
        
        let options = itens.map(item => {
            const isSelected = currentItem && currentItem.item_fornada_id == item.id;
            const isDisabled = selectedIds.includes(item.id.toString()) && !isSelected;
            const disabled = isDisabled ? 'disabled' : '';

                            let estoque = item.estoque_atual;

                            if (isSelected) {

                                estoque += parseInt(currentItem.quantidade);

                            }

                    

                            return `<option value="${item.id}" data-estoque="${estoque}" ${disabled}>${item.produto_nome} (${item.tipo_unidade})</option>`;        }).join('');

        return `
            <div class="item-pedido-row form-group">
                <select name="itens_pedido[${index}][item_id]" class="form-control item-select">${options}</select>
                <input type="number" name="itens_pedido[${index}][quantidade]" value="1" min="1" placeholder="Qtd" class="form-control quantity-input">
                <span class="unit-price"></span>
                <span class="subtotal"></span>
                <span class="estoque-disponivel"></span>
                <button type="button" class="button-danger remove-item-btn">Remover</button>
            </div>
        `;
    }

    function updateAllSelects() {
        const allSelects = Array.from(itensContainer.querySelectorAll('select'));
        const allSelectedIds = allSelects.map(select => select.value);

        allSelects.forEach(select => {
            const currentSelectValue = select.value;
            Array.from(select.options).forEach(option => {
                option.disabled = allSelectedIds.includes(option.value) && option.value !== currentSelectValue;
            });

            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
                const estoque = selectedOption.dataset.estoque;
                const quantidadeInput = select.closest('.item-pedido-row').querySelector('input[type="number"]');
                const estoqueSpan = select.closest('.item-pedido-row').querySelector('.estoque-disponivel');
                quantidadeInput.max = estoque;
                estoqueSpan.textContent = `(Disponível: ${estoque})`;
            }
        });

        if (itensContainer.children.length >= window.totalItensFornada) {
            addItemBtn.style.display = 'none';
        } else {
            addItemBtn.style.display = 'block';
        }
    }

    addItemBtn.addEventListener('click', function() {
        const index = itensContainer.children.length;
        const selectedIds = Array.from(itensContainer.querySelectorAll('select')).map(select => select.value);
        const itemHtml = createItemSelect(window.itensFornada, index, selectedIds);
        if (itemHtml) {
            itensContainer.insertAdjacentHTML('beforeend', itemHtml);
            const newRow = itensContainer.lastElementChild;
            newRow.querySelector('select').dispatchEvent(new Event('change'));
        }
    });

    itensContainer.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item-btn')) {
            e.target.closest('.item-pedido-row').remove();
            updateAllSelects();
        }
    });

    itensContainer.addEventListener('change', function(e) {
        if (e.target.tagName === 'SELECT') {
            updateAllSelects();
        }
    });

    // Fetch initial items and render them
    fetchFornadaItems(initialFornadaId, function() {
        renderInitialItems();
        updateTotals();
    });

    function updateTotals() {
        let totalPedido = 0;
        const itemRows = itensContainer.querySelectorAll('.item-pedido-row');
        itemRows.forEach(row => {
            const select = row.querySelector('select');
            const quantityInput = row.querySelector('input[type="number"]');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const itemFornada = window.itensFornada.find(item => item.id == selectedOption.value);
                if (itemFornada) {
                    const unitPrice = parseFloat(itemFornada.preco_unitario);
                    const quantity = parseInt(quantityInput.value) || 0;
                    const subtotal = unitPrice * quantity;

                    row.querySelector('.unit-price').textContent = `R$ ${unitPrice.toFixed(2)}`;
                    row.querySelector('.subtotal').textContent = `R$ ${subtotal.toFixed(2)}`;
                    totalPedido += subtotal;
                }
            }
        });

        document.getElementById('total-pedido').textContent = `R$ ${totalPedido.toFixed(2)}`;
    }

    itensContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-select')) {
            updateAllSelects();
            updateTotals();
        }
    });

    itensContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateTotals();
        }
    });

    // Call updateTotals after initial rendering
    renderInitialItems();
    updateTotals();
});
</script>

<style>
.item-pedido-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
.item-pedido-row select { flex: 4; }
.item-pedido-row input { flex: 1; }
.remove-item-btn { flex: 0 0 auto; }
</style>