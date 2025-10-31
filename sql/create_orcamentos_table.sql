-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 31/10/2025 às 17:54
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

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `Orcamentos`
--
ALTER TABLE `Orcamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `link_hash` (`link_hash`),
  ADD KEY `solicitacao_id` (`solicitacao_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `Orcamentos`
--
ALTER TABLE `Orcamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `Orcamentos`
--
ALTER TABLE `Orcamentos`
  ADD CONSTRAINT `Orcamentos_ibfk_1` FOREIGN KEY (`solicitacao_id`) REFERENCES `Solicitacoes_Orcamento` (`id`),
  ADD CONSTRAINT `Orcamentos_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `Usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
