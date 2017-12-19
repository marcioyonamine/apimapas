-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18-Set-2017 às 15:52
-- Versão do servidor: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agenda`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `mapas`
--

CREATE TABLE `mapas` (
  `id` int(11) NOT NULL,
  `id_mapas` int(11) NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `valor` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `mapas`
--

INSERT INTO `mapas` (`id`, `id_mapas`, `tipo`, `valor`) VALUES
(2, 3, 2, 'Museu de Santo André Dr. Octaviano Armando  Gaiarsa'),
(3, 5, 2, 'Casa do Olhar Luiz Sacilotto'),
(4, 6, 2, 'ELD - Centro de Dança de Santo André'),
(5, 7, 2, 'Emia Aron Feldman - Escola Municipal de Iniciação Artística'),
(6, 8, 2, 'ELCV - Escola Livre de Cinema e Vídeo'),
(7, 9, 2, 'Teatro Conchita de Moraes'),
(8, 10, 2, 'Espaço Permanente do Acervo de Arte Contemporânea de Santo André - Pinacoteca'),
(9, 11, 2, 'Salão de Exposições'),
(10, 12, 2, 'Teatro Municipal de Santo André Antonio Houaiss'),
(11, 13, 2, 'Saguão do Teatro Municipal de Santo André'),
(12, 14, 2, 'Auditório Heleny Guariba'),
(13, 15, 2, 'Concha Acústica da Praça do Carmo'),
(14, 16, 2, 'Feira de Artesanato - Paço Municipal'),
(15, 17, 2, 'Feira de Artesanato - Rua Cesário Mota'),
(16, 18, 2, 'Feira de Artesanato - Praça do Carmo'),
(17, 19, 2, 'Feira de Artesanato - Ipiranguinha'),
(18, 20, 2, 'Feira de Artesanato - Jaçatuba'),
(19, 21, 2, 'Biblioteca Nair Lacerda'),
(20, 22, 2, 'Biblioteca Distrital Cecília Meireles'),
(21, 23, 2, 'Biblioteca Cata Preta'),
(22, 24, 2, 'Biblioteca Parque Erasmo'),
(23, 25, 2, 'Biblioteca Praça Internacional'),
(24, 27, 2, 'Biblioteca Vila Humaitá'),
(25, 28, 2, 'Biblioteca de Paranapiacaba Ábia Ferreira Francisco'),
(26, 30, 2, 'Biblioteca Vila Floresta'),
(27, 31, 2, 'Biblioteca Vila Linda'),
(28, 32, 2, 'Biblioteca Vila Palmares'),
(29, 35, 2, 'Espaço Infantil (Biblioteca Nair Lacerda)'),
(30, 36, 2, 'Espaço dos Escritores da Região'),
(31, 37, 2, 'Espaço Permanente de Fotografia João Colovatti'),
(32, 38, 2, 'Espaço Reflexos'),
(33, 39, 2, 'Gibiteca Municipal'),
(34, 41, 2, 'Salão Nobre Burle Marx'),
(35, 46, 2, 'Palco do Saguão do Teatro Municipal de Santo André'),
(36, 48, 2, 'Biblioteca do Museu de Santo André'),
(37, 50, 2, 'Palco do Parque Central'),
(38, 51, 2, 'Palco do Parque da Juventude'),
(39, 52, 2, 'Espaço da Cantina do Parque Pref. Celso Daniel'),
(40, 53, 2, 'Coreto da Chácara Pignatari'),
(41, 54, 2, 'Palco da SEDE DA BANDA LIRA, Parque Ipiranguinha'),
(42, 55, 2, 'Calçadão da Oliveira Lima'),
(43, 56, 2, 'Calçadão da Rua Eliza Fláquer'),
(44, 57, 2, 'Travessa Diana'),
(45, 58, 2, 'Estacionamento do Paço Municipal de Santo André'),
(46, 60, 2, 'Parlatório do Paço Municipal de Santo André'),
(47, 65, 2, 'Vila de Paranapiacaba'),
(48, 66, 2, 'Praça do Antigo Mercado'),
(49, 67, 2, 'Largo dos Padeiros'),
(50, 68, 2, 'Bar do Campo'),
(51, 71, 2, 'Clube União Lyra Serrano'),
(52, 73, 2, 'Sala Especial do Museu de Santo André'),
(53, 75, 2, 'BIBLIOTECA DO CENTRO DE DANÇA - BCD'),
(54, 76, 2, 'Estação Prefeito Celso Daniel'),
(55, 79, 2, 'Figueira – “Ficus macrophilla Desfontaines ex persoon\"'),
(56, 80, 2, 'Residência de Bernardino Queiroz dos Santos – Casa do Olhar Luiz Sacilotto'),
(57, 81, 2, 'Residência de Dona Paulina Isabel de Queiroz – Casa da Palavra Mário Quintana'),
(58, 84, 2, 'Biblioteca Vila Sá'),
(59, 89, 2, 'CESA Vila Linda - sala multiuso'),
(60, 90, 2, 'CESA Vila Humaitá - Sala Multiuso'),
(61, 91, 2, 'CESA Vila Floresta - Sala Multiuso'),
(62, 92, 2, 'CESA Cata Preta - Sala Multiuso'),
(63, 93, 2, 'CESA Jardim Santo André - Sala Multiuso'),
(64, 94, 2, 'CESA Jardim Santo Alberto - Sala Multiuso'),
(65, 98, 2, 'CESA Parque Andreense - Sala Multiuso'),
(66, 99, 2, 'CESA Vila Sá - Sala Multiuso'),
(67, 101, 2, 'CESA Parque Erasmo - Sala Multiuso'),
(68, 102, 2, 'CESA Parque Novo Oratório - Sala Multiuso'),
(69, 103, 2, 'Associação dos Moradores Jardim Ana Maria - Sala Multiuso'),
(70, 104, 2, 'Feira Livre do Vinil - FIP 2016'),
(71, 105, 2, 'Biblioteca Santo Alberto'),
(72, 109, 2, 'CEU das Artes Jd. Marek'),
(73, 112, 2, 'Sala de Reuniões da SCT'),
(74, 117, 2, 'Cine-Teatro Carlos Gomes'),
(75, 135, 2, 'Casa da Palavra Mário Quintana'),
(76, 136, 2, 'Parque Prefeito Celso Daniel'),
(77, 137, 2, 'PARQUE JAÇATUBA - PARQUE REGIONAL DA CRIANÇA PALHAÇO ESTREMILIQUE'),
(78, 138, 2, 'CHÁCARA PIGNATARI - Parque Regional Prof. Antônio Pezzolo'),
(79, 142, 2, 'Biblioteca CEU MAREK'),
(80, 145, 2, 'Departamento de Cultura'),
(81, 146, 2, 'BRINQUEDOTECA'),
(82, 148, 2, 'LUDOTECA'),
(83, 163, 2, 'Casa Fox (Casa da Memória)'),
(84, 170, 2, 'ELT - ESCOLA LIVRE DE TEATRO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mapas`
--
ALTER TABLE `mapas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mapas`
--
ALTER TABLE `mapas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
