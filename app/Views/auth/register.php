<?php
// View para a página de cadastro
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casinha de Café - <?php echo htmlspecialchars($titulo); ?></title>
    
    <!-- Google Fonts e Estilos (mesmo da home) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --cor-fundo: #F9F6F1;
            --cor-texto: #3E2723;
            --cor-suporte: #6D4C41;
            --cor-destaque: #A13333;
            --cor-sucesso: #556B2F;
            --cor-aviso: #DAA520;
            --fonte-titulos: 'Playfair Display', serif;
            --fonte-corpo: 'Lato', sans-serif;
        }
        body {
            margin: 0; padding: 20px; font-family: var(--fonte-corpo); background-color: var(--cor-fundo); color: var(--cor-texto); line-height: 1.6; display: flex; justify-content: center; align-items: center; min-height: 100vh;
        }
        .container {
            max-width: 450px; width: 100%; padding: 40px; background-color: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(62, 39, 35, 0.08);
        }
        h1, h2, h3 { font-family: var(--fonte-titulos); color: var(--cor-texto); text-align: center; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type='text'], input[type='email'], input[type='password'], input[type='tel'] {
            width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box; transition: border-color 0.3s;
        }
        input:focus { border-color: var(--cor-destaque); outline: none; }
        .btn {
            width: 100%; display: inline-block; padding: 12px 25px; font-size: 16px; font-weight: bold; text-align: center; text-decoration: none; color: var(--cor-fundo); background-color: var(--cor-destaque); border: none; border-radius: 50px; cursor: pointer; transition: background-color 0.3s, transform 0.2s;
        }
        .btn:hover { background-color: #8a2b2b; transform: translateY(-2px); }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: var(--cor-destaque); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        
        <form action="/register" method="POST">
            <div class="form-group">
                <label for="nome">Nome (Opcional)</label>
                <input type="text" id="nome" name="nome">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <button type="submit" class="btn">Criar Conta</button>
        </form>

        <div class="login-link">
            <p>Já tem uma conta? <a href="/login">Faça login</a></p>
        </div>
    </div>

</body>
</html>
