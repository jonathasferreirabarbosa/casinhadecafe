<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?></title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column; }
        a { color: #A13333; }
    </style>
</head>
<body>
    <h1>Bem-vindo ao seu Dashboard, <?php echo htmlspecialchars($nome_usuario); ?>!</h1>
    <p>Esta é uma página protegida, visível apenas para usuários logados.</p>
    <p><a href="/logout">Sair (Logout)</a></p>
</body>
</html>
