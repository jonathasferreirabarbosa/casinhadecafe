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
                    } else {
                        window.itensFornada = [];
                    }
                });
        }
    });

    addItemBtn.addEventListener('click', function() {
        const fornadaId = fornadaSelect.value;
        const itensFornada = window.itensFornada;
        const itemHtml = createItemSelect(itensFornada, itensContainer.children.length);
        if (itemHtml) {
            itensContainer.insertAdjacentHTML('beforeend', itemHtml);
            // Dispara o evento change no novo select para inicializar o estoque
            const novoSelect = itensContainer.lastElementChild.querySelector('select');
            novoSelect.dispatchEvent(new Event('change'));
        }
    });

    function createItemSelect(itens, index) {
        if (!itens || itens.length === 0) return '';
        let options = itens.map(item => `<option value="${item.id}" data-estoque="${item.estoque_atual}">${item.produto_nome} (${item.tipo_unidade})</option>`).join('');
        return `
            <div class="item-pedido-row form-group">
                <select name="itens_pedido[${index}][item_id]" class="form-control">${options}</select>
                <input type="number" name="itens_pedido[${index}][quantidade]" value="1" min="1" placeholder="Qtd" class="form-control">
                <span class="estoque-disponivel"></span>
                <button type="button" class="button-danger remove-item-btn">Remover</button>
            </div>
        `;
    }

    // Event listener para remover item
    itensContainer.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item-btn')) {
            e.target.closest('.item-pedido-row').remove();
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
});
</script>

<style>
.item-pedido-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
.item-pedido-row select { flex: 4; }
.item-pedido-row input { flex: 1; }
.remove-item-btn { flex: 0 0 auto; }
</style>
