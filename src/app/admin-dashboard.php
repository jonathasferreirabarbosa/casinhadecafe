<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Casinha de Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDF8F5;
            color: #4A2E2A;
        }
        h1 {
            font-family: 'Playfair Display', serif;
        }
         .brand-pink-500 {
            background-color: #D9A6A4;
        }
        .brand-pink-600 {
            background-color: #C78C8A;
        }
        .sidebar {
            width: 250px;
            min-width: 250px;
            background-color: #6b4f4b; /* Darker brown */
            color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            gap: 0.75rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .sidebar a:hover {
            background-color: #8d6e63; /* Lighter brown on hover */
        }
        .sidebar a.active {
            background-color: #8d6e63;
        }
        .content-iframe {
            flex-grow: 1;
            border: none;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="sidebar flex flex-col py-6">
            <div class="px-6 mb-8">
                <h1 class="text-3xl font-bold playfair">Casinha de Café</h1>
                <p class="text-sm text-gray-300">Painel Admin</p>
            </div>
            <nav class="flex-grow">
                <ul>
                    <li>
                        <a href="/admin-dashboard.php" target="content_frame" class="active">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/manage_clients.php" target="content_frame">
                            <i class="fas fa-users"></i>
                            Gerenciar Clientes
                        </a>
                    </li>
                    <li>
                        <a href="/manage_products.php" target="content_frame">
                            <i class="fas fa-coffee"></i>
                            Gerenciar Produtos
                        </a>
                    </li>
                    <li>
                        <a href="/manage_proposals.php" target="content_frame">
                            <i class="fas fa-file-invoice"></i>
                            Gerenciar Propostas
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="mt-auto px-6">
                <a href="/logout.php" class="flex items-center gap-2 px-4 py-2 font-semibold text-white brand-pink-500 rounded-md hover:brand-pink-600">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow p-6 bg-gray-50">
            <iframe name="content_frame" class="content-iframe" src="/manage_clients.php"></iframe>
        </main>
    </div>

    <script>
        // Script to handle active link in sidebar
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            const iframe = document.querySelector('.content-iframe');

            // Function to set active link
            const setActiveLink = (url) => {
                sidebarLinks.forEach(link => {
                    if (link.href === url) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            };

            // Set active link on initial load based on iframe src
            setActiveLink(iframe.src);

            // Set active link when iframe content changes (e.g., user clicks a link inside iframe)
            iframe.onload = () => {
                // This might not work due to same-origin policy if iframe content is from different domain
                // For same-origin, you can get iframe.contentWindow.location.href
                // For now, we rely on clicks on sidebar links
            };

            // Handle clicks on sidebar links
            sidebarLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    // Prevent default link behavior if it's the dashboard link
                    if (link.getAttribute('href') === '/admin-dashboard.php') {
                        event.preventDefault();
                        // Load manage_clients.php as default for dashboard
                        iframe.src = '/manage_clients.php';
                        setActiveLink(link.href);
                    } else {
                        setActiveLink(link.href);
                    }
                });
            });

            // Initial load of content into iframe
            iframe.src = '/manage_clients.php';
        });
    </script>

</body>
</html>
