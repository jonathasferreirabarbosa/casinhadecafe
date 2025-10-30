<?php /* View para listar os usuários/clientes - renderizada no layout admin */ ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Usuários Cadastrados</h3>
        <a href="/admin/usuarios/criar" class="button-primary">Adicionar Usuário</a>
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

        <?php if (empty($usuarios)): ?>
            <p>Nenhum usuário cadastrado ainda. <a href="/admin/usuarios/criar">Clique aqui para adicionar o primeiro!</a></p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nome'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <?php 
                                    $telefoneLimpo = preg_replace('/[^0-9]/', '', $usuario['telefone']);
                                    $urlWhatsapp = 'https://wa.me/55' . $telefoneLimpo;
                                ?>
                                <a href="<?php echo $urlWhatsapp; ?>" class="button-whatsapp" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.1-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                    </svg>
                                    <?php echo htmlspecialchars($usuario['telefone']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></td>
                            <td class="actions">
                                <a href="/admin/usuarios/editar/<?php echo $usuario['id']; ?>" class="button-secondary">Editar</a>
                                <a href="/admin/usuarios/alterar-senha/<?php echo $usuario['id']; ?>" class="button-secondary">Alterar Senha</a>
                                <a href="/admin/usuarios/deletar/<?php echo $usuario['id']; ?>" class="button-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
