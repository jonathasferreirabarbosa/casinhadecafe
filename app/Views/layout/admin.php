<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo htmlspecialchars($titulo ?? 'Casinha de Café'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="sidebar">
        <h1 class="sidebar-title">Casinha de Café</h1>
        <nav class="sidebar-nav">
            <ul>
                <!-- Ícone de Dashboard (Grid) -->
                <li>
                    <a href="/dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <!-- Ícone de Pedidos (Documento) -->
                <li>
                    <a href="/admin/pedidos">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Pedidos
                    </a>
                </li>
                <!-- Ícone de Fornadas (Caixa de Arquivo) -->
                <li>
                    <a href="/admin/fornadas">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Fornadas
                    </a>
                </li>
                <!-- Ícone de Orçamentos (Calculadora) -->
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm3-6h.008v.008H11.25v-.008zm0 3h.008v.008H11.25v-.008zm0 3h.008v.008H11.25v-.008zm3-6h.008v.008H14.25v-.008zm0 3h.008v.008H14.25v-.008zm0 3h.008v.008H14.25v-.008zM6 18V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v12A2.25 2.25 0 0115.75 20.25h-7.5A2.25 2.25 0 016 18z" />
                        </svg>
                        Orçamentos
                    </a>
                </li>
                <!-- Ícone de Produtos (Cubo) -->
                <li>
                    <a href="/admin/produtos">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        Produtos
                    </a>
                </li>
                <!-- Ícone de Clientes (Usuários) -->
                <li>
                    <a href="/admin/usuarios">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Clientes
                    </a>
                </li>
                <!-- Ícone de Configurações (Engrenagem) -->
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-1.003 1.11-1.226.55-.223 1.19-.223 1.74 0 .55.223 1.02.684 1.11 1.226l.053.322c.446.223.865.49 1.25.79l.298-.158c.542-.287 1.17-.223 1.67.149l.07.07c.446.446.57 1.11.287 1.67l-.158.298c.3.385.567.804.79 1.25l.322.053c.542.09.9.56 1.11 1.11.223.55.223 1.19 0 1.74-.223.55-.684 1.02-1.226 1.11l-.322.053c-.223.446-.49.865-.79 1.25l.158.298c.287.542.223 1.17-.149 1.67l-.07.07c-.446.446-1.11.57-1.67.287l-.298-.158c-.385.3-.804.567-1.25.79l-.053.322c-.09.542-.56 1.003-1.11 1.226-.55.223-1.19-.223-1.74 0-.55-.223-1.02-.684-1.11-1.226l-.053-.322c-.446-.223-.865-.49-1.25-.79l-.298.158c-.542.287-1.17.223-1.67-.149l-.07-.07c-.446-.446-.57-1.11-.287-1.67l.158-.298c-.3-.385-.567-.804-.79-1.25l-.322-.053c-.542-.09-.9-.56-1.11-1.11-.223-.55-.223-1.19 0-1.74.223.55.684 1.02 1.226 1.11l.322-.053c.223-.446.49-.865.79-1.25l-.158-.298c-.287-.542-.223-1.17.149-1.67l.07-.07c.446.446 1.11-.57 1.67-.287l.298.158c.385-.3.804-.567 1.25-.79l.053-.322zM12 13.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        Configurações
                    </a>
                </li>
                <!-- Ícone de Sair (Logout) -->
                <li>
                    <a href="/logout">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Sair
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header class="main-header">
            <h2><?php echo htmlspecialchars($titulo ?? 'Painel'); ?></h2>
        </header>
        <main class="content-body">
            <?php echo $content; ?>
        </main>
    </div>
</body>
</html>
