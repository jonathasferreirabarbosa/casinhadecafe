<?php /* View para alterar a senha de um usuário - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Alterar Senha para: <?php echo htmlspecialchars($usuario['nome'] ?? $usuario['email']); ?></h3>
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

        <form action="/admin/usuarios/alterar-senha/<?php echo $usuario['id']; ?>" method="POST">
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>

            <button type="submit" class="button-primary">Alterar Senha</button>
        </form>
    </div>
</div>
