-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 28 mai 2024 à 09:19
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `autoroute`
--

-- --------------------------------------------------------

--
-- Structure de la table `badge`
--

CREATE TABLE `badge` (
  `id` bigint(19) UNSIGNED NOT NULL,
  `badge` char(4) NOT NULL,
  `nom` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `badge`
--

INSERT INTO `badge` (`id`, `badge`, `nom`) VALUES
(4, '0001', 'Charles'),
(5, '0002', 'Hello'),
(13, '0145', 'Jason jj'),
(16, '0051', 'test'),
(19, '0432', 'holle'),
(21, '0112', 'Samuel 2');

-- --------------------------------------------------------

--
-- Structure de la table `garepeage`
--

CREATE TABLE `garepeage` (
  `id` bigint(20) NOT NULL,
  `GarePeage` varchar(4) NOT NULL,
  `nomPeage` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `garepeage`
--

INSERT INTO `garepeage` (`id`, `GarePeage`, `nomPeage`) VALUES
(1, '0010', 'Montpellier'),
(2, '0020', 'Nime'),
(3, '0030', 'Lyon'),
(4, '0040', 'Nice'),
(11, '0050', 'Grenoble'),
(13, '0060', 'Beziers');

-- --------------------------------------------------------

--
-- Structure de la table `portique`
--

CREATE TABLE `portique` (
  `id` bigint(20) NOT NULL,
  `isEntrer` tinyint(4) NOT NULL,
  `noPortique` tinyint(4) NOT NULL,
  `fkGarePeage` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `portique`
--

INSERT INTO `portique` (`id`, `isEntrer`, `noPortique`, `fkGarePeage`) VALUES
(5, 1, 1, 1),
(7, 0, 3, 1),
(8, 0, 4, 1),
(15, 0, 1, 2),
(16, 1, 1, 2),
(17, 1, 1, 3),
(18, 0, 1, 3),
(19, 0, 1, 4),
(20, 1, 1, 4),
(22, 1, 1, 13),
(23, 1, 1, 13);

-- --------------------------------------------------------

--
-- Structure de la table `tarification`
--

CREATE TABLE `tarification` (
  `fkGarePeageSource` bigint(20) NOT NULL,
  `fkGarePeageDestination` bigint(20) NOT NULL,
  `tarif` decimal(10,2) NOT NULL,
  `distance` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `tarification`
--

INSERT INTO `tarification` (`fkGarePeageSource`, `fkGarePeageDestination`, `tarif`, `distance`) VALUES
(1, 2, 9.50, 70.00),
(1, 3, 8.80, 68.70),
(1, 4, 10.50, 50.05),
(2, 3, 10.55, 80.00),
(2, 4, 9.55, 45.89),
(3, 1, 8.80, 76.50),
(3, 2, 7.50, 50.30),
(3, 4, 10.95, 80.00);

-- --------------------------------------------------------

--
-- Structure de la table `trajet`
--

CREATE TABLE `trajet` (
  `id` bigint(20) NOT NULL,
  `dateEntree` timestamp NULL DEFAULT NULL,
  `dateSortie` timestamp NULL DEFAULT NULL,
  `fkPortiqueEntree` bigint(20) NOT NULL,
  `fkPortiqueSortie` bigint(20) DEFAULT NULL,
  `fkBadge` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `trajet`
--

INSERT INTO `trajet` (`id`, `dateEntree`, `dateSortie`, `fkPortiqueEntree`, `fkPortiqueSortie`, `fkBadge`) VALUES
(4, '2024-05-25 16:33:49', '2024-05-25 12:49:21', 5, 15, 4),
(5, '0000-00-00 00:00:00', NULL, 8, NULL, 19),
(6, '2024-05-26 01:49:38', '2024-05-26 09:55:17', 17, 5, 16),
(7, '2024-05-26 01:52:15', NULL, 16, NULL, 19),
(8, '2024-05-26 01:00:25', NULL, 16, NULL, 19),
(9, '2024-05-26 01:55:34', '2024-05-26 02:02:54', 16, 15, 19),
(10, '2024-05-27 23:29:34', '2024-05-27 23:31:24', 17, 20, 21);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `badge_UNIQUE` (`badge`);

--
-- Index pour la table `garepeage`
--
ALTER TABLE `garepeage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `GarePeage_UNIQUE` (`GarePeage`);

--
-- Index pour la table `portique`
--
ALTER TABLE `portique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Portique_GarePeage_idx` (`fkGarePeage`);

--
-- Index pour la table `tarification`
--
ALTER TABLE `tarification`
  ADD PRIMARY KEY (`fkGarePeageSource`,`fkGarePeageDestination`),
  ADD KEY `fk_Tarification_GarePeage1_idx` (`fkGarePeageSource`),
  ADD KEY `fk_Tarification_GarePeage2_idx` (`fkGarePeageDestination`);

--
-- Index pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Trajet_Portique1_idx` (`fkPortiqueEntree`),
  ADD KEY `fk_Trajet_Portique2_idx` (`fkPortiqueSortie`),
  ADD KEY `fk_Trajet_badge1_idx` (`fkBadge`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `badge`
--
ALTER TABLE `badge`
  MODIFY `id` bigint(19) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `garepeage`
--
ALTER TABLE `garepeage`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `portique`
--
ALTER TABLE `portique`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `trajet`
--
ALTER TABLE `trajet`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `portique`
--
ALTER TABLE `portique`
  ADD CONSTRAINT `fk_Portique_GarePeage` FOREIGN KEY (`fkGarePeage`) REFERENCES `garepeage` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tarification`
--
ALTER TABLE `tarification`
  ADD CONSTRAINT `fk_Tarification_GarePeage1` FOREIGN KEY (`fkGarePeageSource`) REFERENCES `garepeage` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Tarification_GarePeage2` FOREIGN KEY (`fkGarePeageDestination`) REFERENCES `garepeage` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD CONSTRAINT `fk_Trajet_Portique1` FOREIGN KEY (`fkPortiqueEntree`) REFERENCES `portique` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trajet_Portique2` FOREIGN KEY (`fkPortiqueSortie`) REFERENCES `portique` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trajet_badge1` FOREIGN KEY (`fkBadge`) REFERENCES `badge` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
