<?php
// Garante que a sessão está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pega as mensagens da sessão, se existirem
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// Pega o input antigo da sessão, se existir
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casinha de Café - <?php echo htmlspecialchars($titulo); ?></title>
    
    <!-- Reutilizando os mesmos estilos da página de registro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --cor-fundo: #F9F6F1; --cor-texto: #3E2723; --cor-suporte: #6D4C41;
            --cor-destaque: #A13333; --cor-sucesso: #556B2F; --cor-aviso: #DAA520;
            --fonte-titulos: 'Playfair Display', serif; --fonte-corpo: 'Lato', sans-serif;
        }
        body { margin: 0; padding: 20px; font-family: var(--fonte-corpo); background-color: var(--cor-fundo); color: var(--cor-texto); display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { max-width: 450px; width: 100%; padding: 40px; background-color: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(62, 39, 35, 0.08); }
        h2 { font-family: var(--fonte-titulos); text-align: center; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type='email'], input[type='password'] { width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box; }
        .btn { width: 100%; display: inline-block; padding: 12px 25px; font-size: 16px; font-weight: bold; text-align: center; text-decoration: none; color: var(--cor-fundo); background-color: var(--cor-destaque); border: none; border-radius: 50px; cursor: pointer; transition: background-color 0.3s; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: var(--cor-destaque); text-decoration: none; font-weight: bold; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 8px; text-align: center; }
        .alert-error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
    </style>
</head>
<body>

    <div class="container">
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>

        <div class="register-link">
            <p>Não tem uma conta? <a href="/register">Cadastre-se</a></p>
        </div>
    </div>

</body>
</html>
