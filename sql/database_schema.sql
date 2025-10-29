-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 29/10/2025 às 14:07
-- Versão do servidor: 8.0.43-34
-- Versão do PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bb95d015_casinha`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `Configuracoes`
--

CREATE TABLE `Configuracoes` (
  `chave` varchar(50) NOT NULL,
  `valor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Fornadas`
--

CREATE TABLE `Fornadas` (
  `id` int NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao_adicional` text,
  `data_inicio_pedidos` datetime NOT NULL,
  `data_fim_pedidos` datetime NOT NULL,
  `status` enum('planejada','ativa','concluida','cancelada') NOT NULL DEFAULT 'planejada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Itens_Fornada`
--

CREATE TABLE `Itens_Fornada` (
  `id` int NOT NULL,
  `fornada_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `estoque_inicial` int NOT NULL,
  `estoque_atual` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Itens_Orcamento`
--

CREATE TABLE `Itens_Orcamento` (
  `id` int NOT NULL,
  `orcamento_id` int NOT NULL,
  `produto_id` int DEFAULT NULL,
  `descricao_item_customizado` varchar(255) NOT NULL,
  `quantidade` int NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Itens_Pedido`
--

CREATE TABLE `Itens_Pedido` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `item_fornada_id` int NOT NULL,
  `quantidade` int NOT NULL,
  `preco_unitario_no_momento` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Orcamentos`
--

CREATE TABLE `Orcamentos` (
  `id` int NOT NULL,
  `solicitacao_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `valor_sinal` decimal(10,2) NOT NULL,
  `link_hash` varchar(64) NOT NULL,
  `status` enum('pendente_cliente','aprovado','recusado_cliente','cancelado_admin') NOT NULL DEFAULT 'pendente_cliente',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_validade` date DEFAULT NULL,
  `observacoes_admin` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Pedidos`
--

CREATE TABLE `Pedidos` (
  `id` int NOT NULL,
  `cliente_id` int NOT NULL,
  `fornada_id` int NOT NULL,
  `data_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_total` decimal(10,2) NOT NULL,
  `status_pagamento` enum('pendente_50','confirmado_50','pago_total','expirado') NOT NULL DEFAULT 'pendente_50',
  `status_pedido` enum('em_processamento','pronto_retirada','entregue','cancelado') NOT NULL DEFAULT 'em_processamento',
  `data_expiracao_reserva` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Produtos`
--

CREATE TABLE `Produtos` (
  `id` int NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text,
  `imagem_url` varchar(255) DEFAULT NULL,
  `tipo_unidade` varchar(50) DEFAULT NULL COMMENT 'Ex: Fatia, Pacote 150g, Forma 300g',
  `disponivel_para_orcamento` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Solicitacoes_Orcamento`
--

CREATE TABLE `Solicitacoes_Orcamento` (
  `id` int NOT NULL,
  `cliente_id` int NOT NULL,
  `detalhes_pedido` text NOT NULL,
  `data_evento` date DEFAULT NULL,
  `status` enum('solicitado','proposta_enviada','cancelado') NOT NULL DEFAULT 'solicitado',
  `data_solicitacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id` int NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `tipo_usuario` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `Configuracoes`
--
ALTER TABLE `Configuracoes`
  ADD PRIMARY KEY (`chave`);

--
-- Índices de tabela `Fornadas`
--
ALTER TABLE `Fornadas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `Itens_Fornada`
--
ALTER TABLE `Itens_Fornada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornada_id` (`fornada_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `Itens_Orcamento`
--
ALTER TABLE `Itens_Orcamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orcamento_id` (`orcamento_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `Itens_Pedido`
--
ALTER TABLE `Itens_Pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `item_fornada_id` (`item_fornada_id`);

--
-- Índices de tabela `Orcamentos`
--
ALTER TABLE `Orcamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `link_hash` (`link_hash`),
  ADD KEY `solicitacao_id` (`solicitacao_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Índices de tabela `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `fornada_id` (`fornada_id`);

--
-- Índices de tabela `Produtos`
--
ALTER TABLE `Produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `Solicitacoes_Orcamento`
--
ALTER TABLE `Solicitacoes_Orcamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `Fornadas`
--
ALTER TABLE `Fornadas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Itens_Fornada`
--
ALTER TABLE `Itens_Fornada`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Itens_Orcamento`
--
ALTER TABLE `Itens_Orcamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Itens_Pedido`
--
ALTER TABLE `Itens_Pedido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Orcamentos`
--
ALTER TABLE `Orcamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Pedidos`
--
ALTER TABLE `Pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Produtos`
--
ALTER TABLE `Produtos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Solicitacoes_Orcamento`
--
ALTER TABLE `Solicitacoes_Orcamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `Itens_Fornada`
--
ALTER TABLE `Itens_Fornada`
  ADD CONSTRAINT `Itens_Fornada_ibfk_1` FOREIGN KEY (`fornada_id`) REFERENCES `Fornadas` (`id`),
  ADD CONSTRAINT `Itens_Fornada_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `Produtos` (`id`);

--
-- Restrições para tabelas `Itens_Orcamento`
--
ALTER TABLE `Itens_Orcamento`
  ADD CONSTRAINT `Itens_Orcamento_ibfk_1` FOREIGN KEY (`orcamento_id`) REFERENCES `Orcamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Itens_Orcamento_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `Produtos` (`id`);

--
-- Restrições para tabelas `Itens_Pedido`
--
ALTER TABLE `Itens_Pedido`
  ADD CONSTRAINT `Itens_Pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `Pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Itens_Pedido_ibfk_2` FOREIGN KEY (`item_fornada_id`) REFERENCES `Itens_Fornada` (`id`);

--
-- Restrições para tabelas `Orcamentos`
--
ALTER TABLE `Orcamentos`
  ADD CONSTRAINT `Orcamentos_ibfk_1` FOREIGN KEY (`solicitacao_id`) REFERENCES `Solicitacoes_Orcamento` (`id`),
  ADD CONSTRAINT `Orcamentos_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `Usuarios` (`id`);

--
-- Restrições para tabelas `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD CONSTRAINT `Pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `Usuarios` (`id`),
  ADD CONSTRAINT `Pedidos_ibfk_2` FOREIGN KEY (`fornada_id`) REFERENCES `Fornadas` (`id`);

--
-- Restrições para tabelas `Solicitacoes_Orcamento`
--
ALTER TABLE `Solicitacoes_Orcamento`
  ADD CONSTRAINT `Solicitacoes_Orcamento_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `Usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
