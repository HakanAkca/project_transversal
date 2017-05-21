-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 21 Mai 2017 à 20:14
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `transversal`
--

-- --------------------------------------------------------

--
-- Structure de la table `barcodes`
--

CREATE TABLE `barcodes` (
  `id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `bottlesNumber` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `barcodeUsed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `barcodes`
--

INSERT INTO `barcodes` (`id`, `barcode`, `bottlesNumber`, `cost`, `barcodeUsed`) VALUES
(123, '473998', 15, 30, 1),
(124, '020105', 22, 44, 1),
(125, '132747', 20, 40, 1),
(126, '182920', 42, 84, 0),
(127, '695048', 25, 50, 0);

-- --------------------------------------------------------

--
-- Structure de la table `catalogs`
--

CREATE TABLE `catalogs` (
  `id` int(11) NOT NULL,
  `partner` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expirationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `catalogs`
--

INSERT INTO `catalogs` (`id`, `partner`, `city`, `deal`, `cost`, `description`, `image`, `date`, `expirationDate`) VALUES
(28, 'PIMKIE', 'Paris', '5â‚¬', 15, 'Profitez d\'une petite rÃ©duction chez Pimkie valable jusqu\'a la fin du mois de juillet.', 'uploads/PIMKIE.png', '2017-05-21 18:37:33', '2017-07-30 00:00:00'),
(29, 'Co\'Corico', 'Paris', '3â‚¬', 10, 'Une viennoiserie achetÃ©e, la deuxiÃ¨me a moitiÃ© prix !', 'uploads/Co\'Corico.png', '2017-05-21 18:45:51', '2017-07-30 00:00:00'),
(30, 'La Tour de Cuivre', 'Paris', '5%', 20, '5% de rÃ©ductions sur un achat a partir de 20â‚¬. Remise immÃ©diate en caisse.', 'uploads/La Tour de Cuivre.png', '2017-05-21 19:03:21', '2017-07-30 00:00:00'),
(31, 'Naturalia', 'Lyon', '5â‚¬', 20, '5 euros de rÃ©duction sur votre prochain achat a Naturalia !', 'uploads/Naturalia.png', '2017-05-21 22:09:02', '2017-07-30 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `deals`
--

CREATE TABLE `deals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `deals`
--

INSERT INTO `deals` (`id`, `user_id`, `catalog_id`, `date`) VALUES
(28, 1, 29, '2017-05-21 18:56:49'),
(29, 1, 30, '2017-05-21 19:04:17'),
(30, 33, 30, '2017-05-21 22:05:09'),
(31, 33, 29, '2017-05-21 22:05:29');

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `partner` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `deal` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `expirationDate` datetime NOT NULL,
  `vote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `surveys`
--

INSERT INTO `surveys` (`id`, `partner`, `description`, `deal`, `image`, `expirationDate`, `vote`) VALUES
(40, 'Batistin', 'Chez Batistin, on sert des burgers ! Mais pas nâ€™importe quels burgers, les notres sont fait avec des produits frais, sÃ©lectionnÃ©s pour vous, le tout avec une bonne dose dâ€™amour !', 'pour un burger achetÃ©, un dessert offert', 'uploads/poll/sondage3.png', '2017-06-21 20:12:53', 1),
(41, 'Le cartel', 'Au cartel, on mange dans la bonne humeur dans une ambiance Ã  lâ€™ancienne. Notre restaurant accueille rÃ©guliÃ¨rement des concerts de groupes du coin !', 'pour un menu achetÃ©, le cafÃ© est offert', 'uploads/poll/sondage2.png', '2017-06-21 20:12:53', 2),
(42, 'Bubble Tea', 'Bubble Tea est un salon de thÃ© situÃ© au carrefour de la rue au pain et de la rue AndrÃ©.  Venez dÃ©couvrir nos diffÃ©rents parfums !', 'pour un menu achetÃ©, un dessert est offert', 'uploads/poll/sondage1.png', '2017-06-21 20:12:53', 1);

-- --------------------------------------------------------

--
-- Structure de la table `surveys_tmp`
--

CREATE TABLE `surveys_tmp` (
  `id` int(11) NOT NULL,
  `partner` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `deal` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `expirationDate` datetime NOT NULL,
  `vote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `surveys_tmp`
--

INSERT INTO `surveys_tmp` (`id`, `partner`, `description`, `deal`, `image`, `expirationDate`, `vote`) VALUES
(54, 'Batistin', 'Chez Batistin, on sert des burgers ! Mais pas nâ€™importe quels burgers, les notres sont fait avec des produits frais, sÃ©lectionnÃ©s pour vous, le tout avec une bonne dose dâ€™amour !', 'pour un burger achetÃ©, un dessert offert', 'uploads/surveys/sondage3.png', '2017-06-21 20:16:20', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `costs` int(11) NOT NULL,
  `bottlesNumber` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `city`, `password`, `costs`, `bottlesNumber`, `level`, `date`, `image`, `vote`) VALUES
(1, 'AdminTritus', 'tritusfundation@gmail.com', 'Paris', '$2y$10$axQQbtEO9mt/0r/t4ZFt1eDV4lR5nHyDgrglWRDCu2YYph5DxJ3KC', 44, 37, 4, '2017-05-21 18:28:54', 'assets/img/defaultProfile.png', 1),
(33, 'UserParis', 'UserParis@gmail.com', 'Paris', '$2y$10$HgVkcykWgjRqNwMkewt7Gu8Qo7HErJZKd.78zS8tBo6r59NKZNaSG', 10, 20, 3, '2017-05-21 18:33:22', 'assets/img/defaultProfile.png', 0),
(34, 'UserLyon', 'UserLyon@gmail.com', 'Lyon', '$2y$10$pmh8Q1oF2SVCAsrjXTj3q./gkUCEjKdsGqUee7e1ULAS3MAd.pR9a', 0, 0, 1, '2017-05-21 22:02:56', 'assets/img/defaultProfile.png', 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `barcodes`
--
ALTER TABLE `barcodes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `catalogs`
--
ALTER TABLE `catalogs`
  ADD UNIQUE KEY `ID` (`id`);

--
-- Index pour la table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `offre_id` (`catalog_id`);

--
-- Index pour la table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `surveys_tmp`
--
ALTER TABLE `surveys_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `ID` (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `barcodes`
--
ALTER TABLE `barcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;
--
-- AUTO_INCREMENT pour la table `catalogs`
--
ALTER TABLE `catalogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pour la table `deals`
--
ALTER TABLE `deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pour la table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT pour la table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT pour la table `surveys_tmp`
--
ALTER TABLE `surveys_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `deals`
--
ALTER TABLE `deals`
  ADD CONSTRAINT `deals_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
