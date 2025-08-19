<?php
require_once __DIR__ . '/../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = $_POST['email'];
        // Hash da senha para armazenamento seguro
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");
        $stmt->execute(['email' => $email, 'password_hash' => $password_hash]);

        $message = 'Usuário criado com sucesso! Você já pode fazer o login.';

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Código de violação de unicidade para MySQL (geral)
            $message = 'Este email já existe. Por favor, escolha outro.';
        } else {
            $message = 'Erro ao registrar o usuário: ' . $e->getMessage();
            // Em um ambiente de produção, você logaria o erro em vez de exibi-lo.
            // error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Administração</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .brand-brown {
            color: #6b4f4b;
        }
    </style>
</head>
<body class="bg-stone-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold brand-brown" style="font-family: 'Playfair Display', serif;">Criar Conta</h1>
            <p class="text-gray-600">Administração da Casinha de Café</p>
        </div>

        <?php if ($message): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <form action="/register.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Senha:</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Criar Conta
                </button>
            </div>
            <div class="text-center mt-4">
                <a href="/login.php" class="inline-block align-baseline font-bold text-sm text-pink-500 hover:text-pink-800">
                    Já tem uma conta? Faça o login
                </a>
            </div>
        </form>
    </div>

</body>
</html>
