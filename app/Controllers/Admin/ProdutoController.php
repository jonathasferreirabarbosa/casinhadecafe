<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Produto;

class ProdutoController extends Controller
{
    private $produtoModel;
    private const UPLOAD_DIR = ROOT_PATH . '/public/uploads/';

    public function __construct()
    {
        // Protege a área de admin
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            // Se não for admin, redireciona para o login ou uma página de "não autorizado"
            header('Location: /login');
            exit;
        }

        $this->produtoModel = new Produto();

        // Garante que o diretório de uploads existe
        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0777, true);
        }
    }

    /**
     * Helper para lidar com o upload de imagens.
     * @param array $fileData Dados do arquivo de $_FILES.
     * @return string|null Nome do arquivo salvo ou null em caso de erro.
     */
    private function handleImageUpload($fileData)
    {
        if ($fileData['error'] === UPLOAD_ERR_OK) {
            $fileName = uniqid() . '_' . basename($fileData['name']);
            $targetFilePath = self::UPLOAD_DIR . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            // Validações básicas
            $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error_message'] = 'Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.';
                return null;
            }

            if ($fileData['size'] > 5000000) { // 5MB
                $_SESSION['error_message'] = 'O arquivo é muito grande. Máximo 5MB.';
                return null;
            }

            if (move_uploaded_file($fileData['tmp_name'], $targetFilePath)) {
                return $fileName;
            } else {
                $_SESSION['error_message'] = 'Erro ao mover o arquivo enviado.';
                return null;
            }
        }
        // Se não houver arquivo ou houver erro de upload, retorna null
        return null;
    }

    /**
     * Exibe a lista de produtos.
     */
    public function index()
    {
        $produtos = $this->produtoModel->getAll();

        $data = [
            'titulo' => 'Gestão de Produtos',
            'produtos' => $produtos
        ];

        $this->view('admin/produtos/index', $data, 'admin');
    }

    /**
     * Exibe o formulário para criar um novo produto.
     */
    public function create()
    {
        $data = [
            'titulo' => 'Adicionar Produto',
            'produto' => [] // Produto vazio para o formulário de criação
        ];
        $this->view('admin/produtos/form', $data, 'admin');
    }

    /**
     * Armazena um novo produto no banco de dados.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'tipo_unidade' => $_POST['tipo_unidade'] ?? '',
                'disponivel_para_orcamento' => isset($_POST['disponivel_para_orcamento']) ? 1 : 0,
                'imagem_arquivo' => null // Valor padrão
            ];

            // Lida com o upload da imagem
            if (isset($_FILES['imagem_arquivo']) && $_FILES['imagem_arquivo']['error'] === UPLOAD_ERR_OK) {
                $uploadedFileName = $this->handleImageUpload($_FILES['imagem_arquivo']);
                if ($uploadedFileName) {
                    $dados['imagem_arquivo'] = $uploadedFileName;
                } else {
                    // Se o upload falhou, redireciona de volta com a mensagem de erro já definida em handleImageUpload
                    $_SESSION['old_input'] = $dados;
                    header('Location: /admin/produtos/criar');
                    exit;
                }
            }

            // Validação básica
            if (empty($dados['nome'])) {
                $_SESSION['error_message'] = 'O nome do produto é obrigatório.';
                // Redirecionar de volta para o formulário com os dados preenchidos
                $_SESSION['old_input'] = $dados;
                header('Location: /admin/produtos/criar');
                exit;
            }

            if ($this->produtoModel->create($dados)) {
                $_SESSION['success_message'] = 'Produto adicionado com sucesso!';
                header('Location: /admin/produtos');
                exit;
            } else {
                // Se falhar ao salvar no DB, tentar remover o arquivo que foi salvo
                if ($dados['imagem_arquivo'] && file_exists(self::UPLOAD_DIR . $dados['imagem_arquivo'])) {
                    unlink(self::UPLOAD_DIR . $dados['imagem_arquivo']);
                }
                $_SESSION['error_message'] = 'Erro ao adicionar produto. Tente novamente.';
                $_SESSION['old_input'] = $dados;
                header('Location: /admin/produtos/criar');
                exit;
            }
        }
        header('Location: /admin/produtos'); // Redireciona se não for POST
        exit;
    }

    /**
     * Exibe o formulário para editar um produto existente.
     * @param int $id O ID do produto a ser editado.
     */
    public function edit($id)
    {
        $produto = $this->produtoModel->find($id);

        if (!$produto) {
            $_SESSION['error_message'] = 'Produto não encontrado.';
            header('Location: /admin/produtos');
            exit;
        }

        $data = [
            'titulo' => 'Editar Produto',
            'produto' => $produto
        ];
        $this->view('admin/produtos/form', $data, 'admin');
    }

    /**
     * Atualiza um produto existente no banco de dados.
     * @param int $id O ID do produto a ser atualizado.
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produtoExistente = $this->produtoModel->find($id);
            if (!$produtoExistente) {
                $_SESSION['error_message'] = 'Produto não encontrado para atualização.';
                header('Location: /admin/produtos');
                exit;
            }

            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'tipo_unidade' => $_POST['tipo_unidade'] ?? '',
                'disponivel_para_orcamento' => isset($_POST['disponivel_para_orcamento']) ? 1 : 0,
                'imagem_arquivo' => $produtoExistente['imagem_arquivo'] // Mantém a imagem existente por padrão
            ];

            // Lida com o upload da nova imagem
            if (isset($_FILES['imagem_arquivo']) && $_FILES['imagem_arquivo']['error'] === UPLOAD_ERR_OK) {
                $uploadedFileName = $this->handleImageUpload($_FILES['imagem_arquivo']);
                if ($uploadedFileName) {
                    // Se um novo arquivo foi enviado com sucesso, deleta o antigo (se existir)
                    if ($produtoExistente['imagem_arquivo'] && file_exists(self::UPLOAD_DIR . $produtoExistente['imagem_arquivo'])) {
                        unlink(self::UPLOAD_DIR . $produtoExistente['imagem_arquivo']);
                    }
                    $dados['imagem_arquivo'] = $uploadedFileName;
                } else {
                    // Se o upload falhou, redireciona de volta com a mensagem de erro já definida em handleImageUpload
                    $_SESSION['old_input'] = $dados;
                    header('Location: /admin/produtos/editar/' . $id);
                    exit;
                }
            } else if (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] === '1') {
                // Se a opção de remover imagem foi marcada
                if ($produtoExistente['imagem_arquivo'] && file_exists(self::UPLOAD_DIR . $produtoExistente['imagem_arquivo'])) {
                    unlink(self::UPLOAD_DIR . $produtoExistente['imagem_arquivo']);
                }
                $dados['imagem_arquivo'] = null;
            }

            // Validação básica
            if (empty($dados['nome'])) {
                $_SESSION['error_message'] = 'O nome do produto é obrigatório.';
                $_SESSION['old_input'] = $dados;
                header('Location: /admin/produtos/editar/' . $id);
                exit;
            }

            if ($this->produtoModel->update($id, $dados)) {
                $_SESSION['success_message'] = 'Produto atualizado com sucesso!';
                header('Location: /admin/produtos');
                exit;
            } else {
                // Se falhar ao salvar no DB, e um novo arquivo foi enviado, tentar remover o novo arquivo
                if ($uploadedFileName && file_exists(self::UPLOAD_DIR . $uploadedFileName)) {
                    unlink(self::UPLOAD_DIR . $uploadedFileName);
                }
                $_SESSION['error_message'] = 'Erro ao atualizar produto. Tente novamente.';
                $_SESSION['old_input'] = $dados;
                header('Location: /admin/produtos/editar/' . $id);
                exit;
            }
        }
        header('Location: /admin/produtos'); // Redireciona se não for POST
        exit;
    }

    /**
     * Deleta um produto do banco de dados.
     * @param int $id O ID do produto a ser deletado.
     */
    public function delete($id)
    {
        $produto = $this->produtoModel->find($id);
        if (!$produto) {
            $_SESSION['error_message'] = 'Produto não encontrado para exclusão.';
            header('Location: /admin/produtos');
            exit;
        }

        // Tenta deletar o produto do banco de dados
        if ($this->produtoModel->delete($id)) {
            // Se o produto foi deletado com sucesso do DB, tenta remover o arquivo de imagem
            if ($produto['imagem_arquivo'] && file_exists(self::UPLOAD_DIR . $produto['imagem_arquivo'])) {
                unlink(self::UPLOAD_DIR . $produto['imagem_arquivo']);
            }
            $_SESSION['success_message'] = 'Produto excluído com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir produto. Tente novamente.';
        }
        header('Location: /admin/produtos');
        exit;
    }
}
