<?php /* View para o formulário de criação/edição de produtos - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($produto['id']) ? 'Editar Produto' : 'Adicionar Novo Produto'; ?></h3>
        <a href="/admin/produtos" class="button-secondary">Voltar para Produtos</a>
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

        <form action="<?php echo isset($produto['id']) ? '/admin/produtos/atualizar/' . $produto['id'] : '/admin/produtos/salvar'; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="imagem_arquivo">Imagem do Produto:</label>
                                            <label for="imagem_arquivo" class="file-upload-button">Escolher Imagem</label>
                <input type="file" id="imagem_arquivo" name="imagem_arquivo" accept="image/*" style="display: none;">
                <span id="file-name" style="margin-left: 10px;">Nenhum arquivo escolhido</span>
                <script>
                    document.getElementById('imagem_arquivo').addEventListener('change', function() {
                        var fileName = this.files[0] ? this.files[0].name : 'Nenhum arquivo escolhido';
                        document.getElementById('file-name').textContent = fileName;
                    });
                </script>
                <?php if (!empty($produto['imagem_arquivo'])): ?>
                    <p class="current-image-info">Imagem atual:</p>
                    <img src="/public/uploads/<?php echo htmlspecialchars($produto['imagem_arquivo']); ?>" alt="Imagem do Produto" class="current-product-image">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remover_imagem" name="remover_imagem" value="1">
                        <label for="remover_imagem">Remover imagem atual</label>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tipo_unidade">Tipo de Unidade:</label>
                <input type="text" id="tipo_unidade" name="tipo_unidade" value="<?php echo htmlspecialchars($produto['tipo_unidade'] ?? ''); ?>" placeholder="Ex: Fatia, Pacote 150g, Forma 300g">
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="disponivel_para_orcamento" name="disponivel_para_orcamento" value="1" <?php echo (isset($produto['disponivel_para_orcamento']) && $produto['disponivel_para_orcamento']) ? 'checked' : ''; ?>>
                <label for="disponivel_para_orcamento">Disponível para Orçamento?</label>
            </div>

            <button type="submit" class="button-primary"><?php echo isset($produto['id']) ? 'Salvar Alterações' : 'Adicionar Produto'; ?></button>
        </form>
    </div>
</div>
