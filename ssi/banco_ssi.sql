-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/03/2024 às 14:07
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco_ssi`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `historico_id` int(11) NOT NULL,
  `data_atualizacao` datetime NOT NULL,
  `descricao_atualizacao` varchar(255) NOT NULL,
  `ssi_ssi_id` int(11) NOT NULL,
  `usuario_chapa` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico`
--

INSERT INTO `historico` (`historico_id`, `data_atualizacao`, `descricao_atualizacao`, `ssi_ssi_id`, `usuario_chapa`) VALUES
(40, '2024-03-08 16:16:05', 'teste', 8, '123456'),
(41, '2024-03-13 13:31:20', 'teste 2', 8, '123456'),
(42, '2024-03-13 13:35:06', 'FIM', 8, '123456'),
(43, '2024-03-13 14:53:37', 'Computador foi limpo e trocado pasta térmica.', 9, '123456');

-- --------------------------------------------------------

--
-- Estrutura para tabela `peca`
--

CREATE TABLE `peca` (
  `peca_id` int(11) NOT NULL,
  `data_peca` datetime NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `fk_ssi_id` int(11) NOT NULL,
  `fk_usuario_chapa` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `peca`
--

INSERT INTO `peca` (`peca_id`, `data_peca`, `descricao`, `valor`, `fk_ssi_id`, `fk_usuario_chapa`) VALUES
(313, '2024-03-08 16:16:11', 'teste', 9.00, 8, '123456'),
(314, '2024-03-13 13:31:29', 'peça', 89.00, 8, '123456'),
(315, '2024-03-13 13:35:12', 'FIM', 89.00, 8, '123456'),
(316, '2024-03-13 14:53:50', 'memória ram ', 100.00, 9, '123456');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `servico_id` int(11) NOT NULL,
  `mostrar` char(1) DEFAULT NULL,
  `nome_servico` varchar(100) NOT NULL,
  `area_servico` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`servico_id`, `mostrar`, `nome_servico`, `area_servico`) VALUES
(7, '1', 'Manutenção em Equipamentos (computador / impressora)', 'TI'),
(8, '1', 'Rede e Internet', 'TI'),
(9, '1', 'Sistemas e Intranet', 'TI'),
(10, '1', 'Site do IDR', 'TI'),
(11, '1', 'Segurança (câmeras)', 'TI'),
(12, '1', 'Telefonia', 'TI'),
(14, '0', 'Teste Área Serviço', 'CDT');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ssi`
--

CREATE TABLE `ssi` (
  `ssi_id` int(11) NOT NULL,
  `nome_solicitante` varchar(100) NOT NULL,
  `chapa_solicitante` char(6) NOT NULL,
  `centro_de_custo` varchar(100) NOT NULL,
  `ramal` char(4) NOT NULL,
  `desc_problema` varchar(255) NOT NULL,
  `pat_equipamento` varchar(100) DEFAULT NULL,
  `data_registro` datetime NOT NULL,
  `andamento` char(1) DEFAULT NULL,
  `data_finalizacao` datetime DEFAULT NULL,
  `fk_usuario_chapa` char(6) DEFAULT NULL,
  `fk_servico_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ssi`
--

INSERT INTO `ssi` (`ssi_id`, `nome_solicitante`, `chapa_solicitante`, `centro_de_custo`, `ramal`, `desc_problema`, `pat_equipamento`, `data_registro`, `andamento`, `data_finalizacao`, `fk_usuario_chapa`, `fk_servico_id`) VALUES
(8, 'Gabriel Vaz Scremim', '997811', '1010101010101010', '2211', 'Monitor com problema', '100001919149', '2024-03-05 14:07:52', '3', NULL, '222222', 7),
(9, 'Wesley', '615262', '4201006000', '2474', 'Computador não liga', '100001919149', '2024-03-05 15:20:51', '3', NULL, '222222', 7),
(10, 'Gabriel Vaz Scremim', '997811', '12345678910123', '2211', 'Teste', '10001919149', '2024-03-05 16:03:09', '1', NULL, NULL, NULL),
(12, 'Alisson ', '858588', '4201006000', '8585', 'Inserir fôlderes', '', '2024-03-08 15:25:26', '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `chapa` char(6) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `Ramal` char(4) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` enum('gerente','tecnico','usuario') NOT NULL DEFAULT 'usuario',
  `mostrar` char(1) NOT NULL DEFAULT '1',
  `area_tecnico` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`chapa`, `nome`, `Ramal`, `senha`, `tipo_usuario`, `mostrar`, `area_tecnico`) VALUES
('123456', 'Wesley Vinicius Vioto Silva', '2274', '$2y$10$048S/3RY1S8.ywyMDxXTP.EWfbkfcRgMGhZ4HHq78jhsdPWCxx8PC', 'gerente', '1', 'TI'),
('222222', 'Nelson', '2211', '$2y$10$toBy7Q45dID5fpOhWGPbdOb4XaEPekhCIF.XfXl0C3Nd424Mo9N5O', 'tecnico', '1', 'TI'),
('858588', 'Alisson', '8585', '$2y$10$pIW8RC8pG7jlmYP/QR6XLulXDTTkkKStHJ7vXhsQbmyO4UR0XIS7K', 'usuario', '1', NULL),
('997811', 'Gabriel Vaz Scremim', '2211', '$2y$10$ouRS7P25Ro1Cam.kBX3exetdIJaYpeu8aOPlmDiCKt0wdGsJ0yVMC', 'usuario', '1', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`historico_id`),
  ADD KEY `fk_historico_ssi1_idx` (`ssi_ssi_id`),
  ADD KEY `fk_historico_usuario1_idx` (`usuario_chapa`);

--
-- Índices de tabela `peca`
--
ALTER TABLE `peca`
  ADD PRIMARY KEY (`peca_id`),
  ADD KEY `fk_peca_ssi1_idx` (`fk_ssi_id`),
  ADD KEY `fk_peca_usuario1_idx` (`fk_usuario_chapa`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`servico_id`);

--
-- Índices de tabela `ssi`
--
ALTER TABLE `ssi`
  ADD PRIMARY KEY (`ssi_id`),
  ADD KEY `fk_ssi_usuario_idx` (`fk_usuario_chapa`),
  ADD KEY `fk_ssi_servico1_idx` (`fk_servico_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`chapa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `historico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `peca`
--
ALTER TABLE `peca`
  MODIFY `peca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `servico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `ssi`
--
ALTER TABLE `ssi`
  MODIFY `ssi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `fk_historico_ssi1` FOREIGN KEY (`ssi_ssi_id`) REFERENCES `ssi` (`ssi_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_historico_usuario1` FOREIGN KEY (`usuario_chapa`) REFERENCES `usuario` (`chapa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `peca`
--
ALTER TABLE `peca`
  ADD CONSTRAINT `fk_peca_ssi1` FOREIGN KEY (`fk_ssi_id`) REFERENCES `ssi` (`ssi_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_peca_usuario1` FOREIGN KEY (`fk_usuario_chapa`) REFERENCES `usuario` (`chapa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `ssi`
--
ALTER TABLE `ssi`
  ADD CONSTRAINT `fk_ssi_servico1` FOREIGN KEY (`fk_servico_id`) REFERENCES `servico` (`servico_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ssi_usuario` FOREIGN KEY (`fk_usuario_chapa`) REFERENCES `usuario` (`chapa`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
