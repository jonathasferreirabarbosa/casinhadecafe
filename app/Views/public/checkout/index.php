<?php /* View para a página de checkout */ ?>

<div class="container my-5">
    <h3><?php echo htmlspecialchars($titulo); ?></h3>

    <div class="row">
        <div class="col-md-8">
            <h4>Resumo do Pedido</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrinho as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                            <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total a Pagar</h4>
                    <p class="card-text display-4">R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?></p>
                    <hr>
                    <h5>Instruções</h5>
                    <p>Ao clicar em "Confirmar Pré-Reserva", seu pedido será registrado em nosso sistema.</p>
                    <p>Você será redirecionado para uma página com os detalhes do pedido e a chave PIX para pagamento do sinal de 50% (<strong>R$ <?php echo number_format($valorTotal / 2, 2, ',', '.'); ?></strong>).</p>
                    <p>Sua reserva ficará pendente até a confirmação do pagamento.</p>
                    
                    <form action="/checkout/processar" method="POST">
                        <button type="submit" class="btn btn-success btn-lg btn-block">Confirmar Pré-Reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
