<?php /* View para o formulário de criação de pedidos pelo admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo htmlspecialchars($titulo); ?></h3>
        <a href="/admin/pedidos" class="button-secondary">Voltar para Pedidos</a>
    </div>
    <div class="card-content">

        <form action="/admin/pedidos/salvar" method="POST" id="form-pedido">
            
            <div class="form-group">
                <label for="cliente_id">Cliente:</label>
                <select id="cliente_id" name="cliente_id" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nome'] . ' (' . $cliente['email'] . ')'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fornada_id">Fornada:</label>
                <select id="fornada_id" name="fornada_id" required>
                    <option value="">Selecione uma fornada</option>
                    <?php foreach ($fornadas as $fornada): ?>
                        <option value="<?php echo $fornada['id']; ?>"><?php echo htmlspecialchars($fornada['titulo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h4>Itens do Pedido</h4>
            <div id="itens-pedido-container">
                <!-- Itens da fornada selecionada aparecerão aqui via JavaScript -->
            </div>

            <div id="total-pedido-container" style="text-align: right; margin-top: 10px;">
                <strong>Total do Pedido: <span id="total-pedido">R$ 0,00</span></strong>
            </div>

            <div class="form-group">
                <button type="button" id="add-item-btn" class="button-secondary" style="display: none;">Adicionar Item</button>
            </div>

            <button type="submit" class="button-primary">Salvar Pedido</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fornadaSelect = document.getElementById('fornada_id');
    const itensContainer = document.getElementById('itens-pedido-container');
    const addItemBtn = document.getElementById('add-item-btn');

    fornadaSelect.addEventListener('change', function() {
        const fornadaId = this.value;
        itensContainer.innerHTML = ''; // Limpa o container
        addItemBtn.style.display = 'none';

        if (fornadaId) {
            fetch(`/admin/fornadas/itens/${fornadaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        addItemBtn.style.display = 'block';
                        window.itensFornada = data; // Armazena os itens globalmente na janela
                        window.totalItensFornada = data.length;
                    } else {
                        window.itensFornada = [];
                        window.totalItensFornada = 0;
                    }
                });
        }
    });

    addItemBtn.addEventListener('click', function() {
        const numItensAdicionados = itensContainer.children.length;
        if (numItensAdicionados >= window.totalItensFornada) {
            addItemBtn.style.display = 'none';
            return;
        }

        const fornadaId = fornadaSelect.value;
        const itensFornada = window.itensFornada;
        const selectedIds = Array.from(itensContainer.querySelectorAll('select')).map(select => select.value);
        const itemHtml = createItemSelect(itensFornada, numItensAdicionados, selectedIds);
        if (itemHtml) {
            itensContainer.insertAdjacentHTML('beforeend', itemHtml);
            // Dispara o evento change no novo select para inicializar o estoque
            const novoSelect = itensContainer.lastElementChild.querySelector('select');
            novoSelect.dispatchEvent(new Event('change'));
        }

        if (itensContainer.children.length >= window.totalItensFornada) {
            addItemBtn.style.display = 'none';
        }
    });

    function createItemSelect(itens, index, selectedIds = []) {
        if (!itens || itens.length === 0) return '';
        let firstAvailableItem = null;
        let options = itens.map(item => {
            const disabled = selectedIds.includes(item.id.toString()) ? 'disabled' : '';
            if (!disabled && !firstAvailableItem) {
                firstAvailableItem = item;
            }
            return `<option value="${item.id}" data-estoque="${item.estoque_atual}" ${disabled}>${item.produto_nome} (${item.tipo_unidade})</option>`;
        }).join('');
        
        const estoqueInicial = firstAvailableItem ? `(Disponível: ${firstAvailableItem.estoque_atual})` : '';

        return `
            <div class="item-pedido-row form-group">
                <select name="itens_pedido[${index}][item_id]" class="form-control item-select">${options}</select>
                <input type="number" name="itens_pedido[${index}][quantidade]" value="1" min="1" placeholder="Qtd" class="form-control quantity-input">
                <span class="unit-price"></span>
                <span class="subtotal"></span>
                <span class="estoque-disponivel">${estoqueInicial}</span>
                <button type="button" class="button-danger remove-item-btn">Remover</button>
            </div>
        `;
    }

    // Event listener para remover item
    itensContainer.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item-btn')) {
            e.target.closest('.item-pedido-row').remove();
            addItemBtn.style.display = 'block';
        }
    });
    
    // Event listener para atualizar o max da quantidade e exibir o estoque quando um item é selecionado
    itensContainer.addEventListener('change', function(e) {
        if (e.target && e.target.tagName === 'SELECT') {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const estoque = selectedOption.dataset.estoque;
            const quantidadeInput = e.target.closest('.item-pedido-row').querySelector('input[type="number"]');
            const estoqueSpan = e.target.closest('.item-pedido-row').querySelector('.estoque-disponivel');
            
            quantidadeInput.max = estoque;
            estoqueSpan.textContent = `(Disponível: ${estoque})`;

            // Atualiza os outros selects para desabilitar as opções já selecionadas
            const allSelects = Array.from(itensContainer.querySelectorAll('select'));
            const allSelectedIds = allSelects.map(select => select.value);

            allSelects.forEach(select => {
                const currentSelectValue = select.value;
                Array.from(select.options).forEach(option => {
                    if (allSelectedIds.includes(option.value) && option.value !== currentSelectValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        }
    });

    // Validação do formulário antes do submit
    document.getElementById('form-pedido').addEventListener('submit', function(e) {
        const itens = itensContainer.querySelectorAll('.item-pedido-row');
        let hasError = false;
        itens.forEach(item => {
            const select = item.querySelector('select');
            const quantidadeInput = item.querySelector('input[type="number"]');
            const selectedOption = select.options[select.selectedIndex];
            const estoque = parseInt(selectedOption.dataset.estoque);
            const quantidade = parseInt(quantidadeInput.value);

            if (quantidade > estoque) {
                alert(`A quantidade para o item "${selectedOption.text}" excede o estoque disponível de ${estoque} unidades.`);
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault(); // Impede o envio do formulário
        }
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
            updateTotals();
        }
    });

    itensContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateTotals();
        }
    });
});
</script>

<style>
.item-pedido-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
.item-pedido-row select { flex: 4; }
.item-pedido-row input { flex: 1; }
.remove-item-btn { flex: 0 0 auto; }
</style>
