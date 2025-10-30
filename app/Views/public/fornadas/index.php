<?php /* View para listar as fornadas ativas - renderizada no layout public */ ?>

<div class="container">
    <h2 class="text-center my-4"><?php echo htmlspecialchars($titulo); ?></h2>

    <?php if (empty($fornadas)): ?>
        <div class="alert alert-info text-center">
            <p>Nenhuma fornada aberta no momento. Volte em breve!</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($fornadas as $fornada): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo htmlspecialchars($fornada['titulo']); ?></h4>
                            <p class="card-text"><?php echo htmlspecialchars($fornada['descricao_adicional']); ?></p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Pedidos de <?php echo date('d/m/Y', strtotime($fornada['data_inicio_pedidos'])); ?> 
                                    até <?php echo date('d/m/Y', strtotime($fornada['data_fim_pedidos'])); ?>
                                </small>
                            </p>
                            <a href="/fornadas/ver/<?php echo $fornada['id']; ?>" class="btn btn-primary">Ver Produtos e Encomendar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
