<?php

require_once ROOT_PATH . '/app/Core/Controller.php';

class HomeController extends Controller {

    /**
     * Exibe a página inicial do site.
     */
    public function index() {
        // Dados a serem passados para a view
        $data = [
            'titulo' => 'Página Inicial',
            'descricao' => 'Bem-vindo à Casinha de Café!'
        ];

        // Renderiza a view 'public/home' e passa os dados para ela
        $this->view('public/home', $data);
    }

    /**
     * Página de teste de conexão com o banco de dados.
     */
    public function testDb() {
        echo "<h1>Teste de Conexão com o Banco de Dados</h1>";
        try {
            // Tenta obter a instância da conexão com o banco de dados.
            $db = \App\Core\Database::getInstance()->getConnection();
            
            echo "<p style='color: #556B2F;'><strong>Conexão com o banco de dados MySQL estabelecida com sucesso!</strong></p>";
            echo "<p>A aplicação está pronta para interagir com o banco de dados.</p>";

        } catch (\Exception $e) {
            echo "<p style='color: #A13333;'><strong>Falha ao conectar com o banco de dados.</strong></p>";
            echo "<p><strong>Mensagem do Erro:</strong> " . $e->getMessage() . "</p>";
            echo "<p><strong>Instruções:</strong></p>";
            echo "<ol>";
            echo "<li>Verifique se o serviço do MySQL está em execução no seu servidor.";
            echo "<li>Confirme se as credenciais (host, nome do banco, usuário, senha) no seu arquivo de configuração estão corretas para o ambiente de produção.";
            echo "<li>Certifique-se de que o banco de dados 'casinhacafe' foi criado e as tabelas do arquivo <code>schema.sql</code> foram importadas.";
            echo "</ol>";
        }
    }
}
