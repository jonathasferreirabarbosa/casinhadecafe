<?php /* View para listar as fornadas - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fornadas Cadastradas</h3>
        <a href="/admin/fornadas/criar" class="button-primary">Adicionar Fornada</a>
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

        <?php if (empty($fornadas)): ?>
            <p>Nenhuma fornada cadastrada ainda. <a href="/admin/fornadas/criar">Clique aqui para adicionar a primeira!</a></p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Início Pedidos</th>
                        <th>Fim Pedidos</th>
                        <th>Data Entrega</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fornadas as $fornada): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fornada['id']); ?></td>
                            <td><?php echo htmlspecialchars($fornada['titulo']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fornada['data_inicio_pedidos']))); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fornada['data_fim_pedidos']))); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fornada['data_entrega']))); ?></td>
                            <td><?php echo htmlspecialchars($fornada['status']); ?></td>
                            <td class="actions">
                                <a href="/admin/fornadas/<?php echo $fornada['id']; ?>/itens" class="button-primary">Gerenciar Itens</a>
                                <a href="/admin/fornadas/editar/<?php echo $fornada['id']; ?>" class="button-secondary">Editar</a>
                                <a href="/admin/fornadas/deletar/<?php echo $fornada['id']; ?>" class="button-danger" onclick="return confirm('Tem certeza que deseja excluir esta fornada? Esta ação não pode ser desfeita.');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
