<?php /* View para o formulário de criação/edição de usuários - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($usuario['id']) ? 'Editar Usuário' : 'Adicionar Novo Usuário'; ?></h3>
        <a href="/admin/usuarios" class="button-secondary">Voltar para Usuários</a>
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

        <form action="<?php echo isset($usuario['id']) ? '/admin/usuarios/atualizar/' . $usuario['id'] : '/admin/usuarios/salvar'; ?>" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" required>
            </div>

            <?php if (!isset($usuario['id'])): // Senha apenas na criação ?>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="tipo_usuario">Tipo de Usuário:</label>
                <select id="tipo_usuario" name="tipo_usuario">
                    <option value="cliente" <?php echo (isset($usuario['tipo_usuario']) && $usuario['tipo_usuario'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="admin" <?php echo (isset($usuario['tipo_usuario']) && $usuario['tipo_usuario'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="button-primary"><?php echo isset($usuario['id']) ? 'Salvar Alterações' : 'Adicionar Usuário'; ?></button>
        </form>
    </div>
</div>
