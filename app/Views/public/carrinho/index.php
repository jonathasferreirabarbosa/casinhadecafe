<?php /* View para exibir o carrinho de compras */ ?>

<div class="container my-5">
    <h2><?php echo htmlspecialchars($titulo); ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <?php if (empty($carrinho)) : ?>
        <div class="text-center">
            <p>Seu carrinho está vazio.</p>
            <a href="/fornadas" class="btn btn-primary">Ver fornadas abertas</a>
        </div>
    <?php else : ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrinho as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                        <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                        <td>
                            <form action="/carrinho/atualizar" method="POST" class="form-inline">
                                <input type="hidden" name="item_fornada_id" value="<?php echo $item['item_fornada_id']; ?>">
                                <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" max="<?php echo $item['estoque_atual']; ?>" class="form-control mr-2" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-secondary">Atualizar</button>
                            </form>
                        </td>
                        <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="/carrinho/remover/<?php echo $item['item_fornada_id']; ?>" class="btn btn-sm btn-danger">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right">
            <h4>Total: R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?></h4>
            <a href="/checkout" class="btn btn-success">Finalizar Pré-Reserva</a>
        </div>
    <?php endif; ?>
</div>
