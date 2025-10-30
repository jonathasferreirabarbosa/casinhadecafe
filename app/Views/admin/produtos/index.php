<?php /* View para listar os produtos - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Produtos Cadastrados</h3>
        <a href="/admin/produtos/criar" class="button-primary">Adicionar Produto</a>
    </div>
    <div class="card-content">
        <?php if (empty($produtos)): ?>
            <p>Nenhum produto cadastrado ainda. <a href="/admin/produtos/criar">Clique aqui para adicionar o primeiro!</a></p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Tipo de Unidade</th>
                        <th>Para Orçamento?</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['id']); ?></td>
                            <td>
                                <?php if (!empty($produto['imagem_arquivo'])): ?>
                                    <img src="/public/uploads/<?php echo htmlspecialchars($produto['imagem_arquivo']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['tipo_unidade']); ?></td>
                            <td><?php echo $produto['disponivel_para_orcamento'] ? 'Sim' : 'Não'; ?></td>
                            <td class="actions">
                                <a href="/admin/produtos/editar/<?php echo $produto['id']; ?>" class="button-secondary">Editar</a>
                                                                <a href="/admin/produtos/deletar/<?php echo $produto['id']; ?>" class="button-danger" onclick="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
