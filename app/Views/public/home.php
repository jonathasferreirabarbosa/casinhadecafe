<?php
// Este é o arquivo de View para a página inicial.
// A variável $titulo e $descricao são passadas pelo HomeController.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casinha de Café - <?php echo htmlspecialchars($titulo); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        /* Define a paleta de cores como variáveis CSS para fácil reutilização */
        :root {
            --cor-fundo: #F9F6F1; /* Bege Suave */
            --cor-texto: #3E2723; /* Marrom Escuro */
            --cor-suporte: #6D4C41; /* Marrom Médio */
            --cor-destaque: #A13333; /* Vermelho Amora */
            --cor-sucesso: #556B2F; /* Verde Oliva */
            --cor-aviso: #DAA520; /* Amarelo Mostarda */
            --fonte-titulos: 'Playfair Display', serif;
            --fonte-corpo: 'Lato', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: var(--fonte-corpo);
            background-color: var(--cor-fundo);
            color: var(--cor-texto);
            line-height: 1.6;
        }

        h1, h2, h3 {
            font-family: var(--fonte-titulos);
            color: var(--cor-texto);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Estilo de Botão Provisório */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            color: var(--cor-fundo);
            background-color: var(--cor-destaque);
            border: none;
            border-radius: 50px; /* Botão "pilled" */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn:hover {
            background-color: #8a2b2b; /* Um tom mais escuro do vermelho amora */
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

    <header class="container">
        <h1>Casinha de Café</h1>
        <nav>
            <!-- A navegação virá aqui -->
        </nav>
    </header>

    <main class="container">
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <p><?php echo htmlspecialchars($descricao); ?></p>
        
        <p>Esta é a página inicial sendo carregada a partir do <strong>HomeController</strong> e da view <strong>home.php</strong>.</p>
        <p>O sistema de rotas está funcionando!</p>

        <a href="#" class="btn">Ver Fornadas Abertas</a>
    </main>

</body>
</html>
