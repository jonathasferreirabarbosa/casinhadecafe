/* Tabela de Configurações Globais (Para Sugestão 1.2) */
CREATE TABLE Configuracoes (
    chave VARCHAR(50) PRIMARY KEY,
    valor VARCHAR(255) NOT NULL
    /* Ex: ('TEMPO_EXPIRACAO_RESERVA_HORAS', '24'). '0' = desativado */
);

/* Tabela de Usuários (Para Sugestão 2) */
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NULL, /* Nome é Opcional */
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    /* Princípio de Segurança: NUNCA armazene senhas em texto puro. */
    senha_hash VARCHAR(255) NOT NULL, 
    tipo_usuario ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* Tabela central de produtos */
CREATE TABLE Produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    imagem_url VARCHAR(255),
    /* Fundamental para o requisito de "variar tipo de unidade" */
    tipo_unidade VARCHAR(50) COMMENT 'Ex: Fatia, Pacote 150g, Forma 300g',
    /* Flag para o módulo de Orçamentos */
    disponivel_para_orcamento BOOLEAN NOT NULL DEFAULT FALSE
);

/* Tabela das "Fornadas" (Lotes de Venda) */
CREATE TABLE Fornadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao_adicional TEXT,
    data_inicio_pedidos DATETIME NOT NULL,
    data_fim_pedidos DATETIME NOT NULL,
    status ENUM('planejada', 'ativa', 'concluida', 'cancelada') NOT NULL DEFAULT 'planejada'
);

/* Tabela "Pivô" que liga Produtos às Fornadas (Define o inventário) */
CREATE TABLE Itens_Fornada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fornada_id INT NOT NULL,
    produto_id INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    estoque_inicial INT NOT NULL,
    estoque_atual INT NOT NULL,
    FOREIGN KEY (fornada_id) REFERENCES Fornadas(id),
    FOREIGN KEY (produto_id) REFERENCES Produtos(id)
);

/* Tabela de Pedidos (Pré-Reservas) */
CREATE TABLE Pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fornada_id INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10, 2) NOT NULL,
    /* Status de pagamento agora inclui o fluxo de expiração */
    status_pagamento ENUM('pendente_50', 'confirmado_50', 'pago_total', 'expirado') NOT NULL DEFAULT 'pendente_50',
    status_pedido ENUM('em_processamento', 'pronto_retirada', 'entregue', 'cancelado') NOT NULL DEFAULT 'em_processamento',
    /* * Este campo será NULL se a configuração 
     * 'TEMPO_EXPIRACAO_RESERVA_HORAS' for '0'.
     */
    data_expiracao_reserva DATETIME NULL, 
    FOREIGN KEY (cliente_id) REFERENCES Usuarios(id),
    FOREIGN KEY (fornada_id) REFERENCES Fornadas(id)
);

/* Tabela dos Itens dentro de um Pedido */
CREATE TABLE Itens_Pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    item_fornada_id INT NOT NULL,
    quantidade INT NOT NULL,
    /* Armazenamos o preço no momento da compra para garantir integridade histórica */
    preco_unitario_no_momento DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES Pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (item_fornada_id) REFERENCES Itens_Fornada(id)
);

/* --- MÓDULO DE ORÇAMENTOS REFORMULADO (Para Sugestão 3) --- */

/* Passo 1: O Cliente (logado) faz a solicitação */
CREATE TABLE Solicitacoes_Orcamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    detalhes_pedido TEXT NOT NULL,
    data_evento DATE,
    status ENUM('solicitado', 'proposta_enviada', 'cancelado') NOT NULL DEFAULT 'solicitado',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES Usuarios(id)
);

/* Passo 2: A Admin cria a Proposta (O Orçamento) */
CREATE TABLE Orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitacao_id INT NOT NULL,
    admin_id INT NOT NULL, /* Quem criou a proposta */
    valor_total DECIMAL(10, 2) NOT NULL,
    valor_sinal DECIMAL(10, 2) NOT NULL,
    /* Para o link direto (Sugestão 3) */
    link_hash VARCHAR(64) NOT NULL UNIQUE, 
    status ENUM('pendente_cliente', 'aprovado', 'recusado_cliente', 'cancelado_admin') NOT NULL DEFAULT 'pendente_cliente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_validade DATE, /* Opcional: Proposta válida até... */
    observacoes_admin TEXT,
    FOREIGN KEY (solicitacao_id) REFERENCES Solicitacoes_Orcamento(id),
    FOREIGN KEY (admin_id) REFERENCES Usuarios(id)
);

/* Passo 3: Os itens daquela Proposta */
CREATE TABLE Itens_Orcamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orcamento_id INT NOT NULL,
    /* Pode ser um produto do catálogo ou um item customizado */
    produto_id INT NULL, 
    descricao_item_customizado VARCHAR(255) NOT NULL, /* Ex: "Bolo 2 andares tema Sereia" */
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (orcamento_id) REFERENCES Orcamentos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES Produtos(id)
);