<?php /* View para exibir os detalhes de uma fornada e seus produtos */ ?>

<div class="container">
    <h2 class="text-center my-4"><?php echo htmlspecialchars($fornada['titulo']); ?></h2>
    <p class="text-center text-muted"><?php echo htmlspecialchars($fornada['descricao_adicional']); ?></p>
    <p class="text-center">
        <small>Pedidos até <?php echo date('d/m/Y', strtotime($fornada['data_fim_pedidos'])); ?></small>
    </p>

    <div class="row mt-5">
        <?php foreach ($fornada['itens'] as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['produto_nome']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($item['tipo_unidade']); ?></p>
                        <p class="card-text font-weight-bold">R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></p>
                        
                        <?php if ($item['estoque_atual'] > 0): ?>
                            <form action="/carrinho/adicionar" method="POST" class="mt-auto">
                                <input type="hidden" name="item_fornada_id" value="<?php echo $item['item_fornada_id']; ?>">
                                <div class="form-group">
                                    <label for="quantidade-<?php echo $item['item_fornada_id']; ?>">Quantidade:</label>
                                    <input type="number" id="quantidade-<?php echo $item['item_fornada_id']; ?>" name="quantidade" class="form-control" value="1" min="1" max="<?php echo $item['estoque_atual']; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Adicionar ao Carrinho</button>
                            </form>
                        <?php else: ?>
                            <p class="text-danger mt-auto font-weight-bold">Esgotado!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
