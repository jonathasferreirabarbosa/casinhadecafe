<?php
session_start();

// Detalhes da conexão MySQL para InfinityFree
$db_host = 'sql303.infinityfree.com';
$db_name = 'if0_39743407_casinha_cafe';
$db_user = 'if0_39743407';
$db_pass = 'SUA_SENHA_DO_MYSQL_AQUI'; // *** SUBSTITUA PELA SUA SENHA REAL DO MYSQL ***
$db_port = '3306';

$dsn = "mysql:host=$db_host;dbname=$db_name;port=$db_port";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $email;
            header('Location: admin-dashboard.php'); // Redireciona para .php
            exit;
        } else {
            $message = 'Usuário ou senha inválidos.';
        }
    } catch (PDOException $e) {
        $message = 'Erro de conexão com o banco de dados: ' . $e->getMessage();
        // Em um ambiente de produção, você logaria o erro em vez de exibi-lo.
        // error_log($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administração</title>
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
            <h1 class="text-3xl font-bold brand-brown" style="font-family: 'Playfair Display', serif;">Acesso Restrito</h1>
            <p class="text-gray-600">Administração da Casinha de Café</p>
        </div>

        <?php if ($message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
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
                    Entrar
                </button>
            </div>
             <div class="text-center mt-4">
                <a href="register.php" class="inline-block align-baseline font-bold text-sm text-pink-500 hover:text-pink-800">
                    Não tem uma conta? Crie uma agora
                </a>
            </div>
        </form>
    </div>

</body>
</html>
