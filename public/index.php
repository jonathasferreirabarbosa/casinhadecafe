<?php

// --- Ponto de Entrada da Aplicação (Front Controller) ---

// Define uma constante para o diretório raiz do projeto para facilitar a inclusão de arquivos.
// __DIR__ é o diretório do arquivo atual (public), então subimos um nível.
define('ROOT_PATH', dirname(__DIR__));

// Carrega o arquivo de configuração do banco de dados.
require_once ROOT_PATH . '/config/database.php';

// Carrega a classe principal de conexão com o banco de dados.
require_once ROOT_PATH . '/app/Core/Database.php';

// --- Teste de Conexão com o Banco de Dados ---
echo "<h1>Casinha de Café - Teste de Configuração</h1>";

try {
    // Tenta obter a instância da conexão com o banco de dados.
    $db = Database::getInstance()->getConnection();
    
    // Se a linha acima não lançar uma exceção, a conexão foi bem-sucedida.
    echo "<p style='color: #556B2F;'><strong>Conexão com o banco de dados MySQL estabelecida com sucesso!</strong></p>";
    echo "<p>O próximo passo será criar o Router para carregar as páginas dinamicamente.</p>";

} catch (Exception $e) {
    // Se houver qualquer erro (seja da nossa classe ou do PDO), ele será capturado aqui.
    echo "<p style='color: #A13333;'><strong>Falha ao conectar com o banco de dados.</strong></p>";
    echo "<p><strong>Mensagem do Erro:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Instruções:</strong></p>";
    echo "<ol>";
    echo "<li>Verifique se o serviço do MySQL (ou MariaDB) está em execução.";
    echo "<li>Abra o arquivo <code>config/database.php</code> e confirme se os dados (DB_HOST, DB_NAME, DB_USER, DB_PASS) estão corretos.";
    echo "<li>Certifique-se de que o banco de dados 'casinhacafe' foi criado no seu MySQL.";
    echo "</ol>";
}

// Futuramente, aqui chamaremos o Router para processar a URL.
// Ex: $router = new Router();
//     $router->dispatch();
