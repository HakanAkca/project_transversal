-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Ven 07 Avril 2017 à 11:24
-- Version du serveur :  5.6.35
-- Version de PHP :  7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `transversal`
--

-- --------------------------------------------------------

--
-- Structure de la table `offres_catalogue`
--

CREATE TABLE `offres_catalogue` (
  `ID` int(11) NOT NULL,
  `Partenaire` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Secteur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Reduction` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Cout` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Pseudo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Points` int(11) NOT NULL,
  `BottlesNumber` int(11) NOT NULL,
  `Level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`ID`, `Pseudo`, `Email`, `City`, `Password`, `Points`, `BottlesNumber`, `Level`) VALUES
(1, 'admin', 'hakan.akca@supinternet.fr', 'paris', '$2y$10$c2FsdHlzYWx0eXNhbHR5cuFYLWqyckAGswm2.X4Qs/DrZlN4kmx/a', 0, 0, 1),
(2, 'hakan', 'hakanakca10@gmail.com', 'paris', '$2y$10$c2FsdHlzYWx0eXNhbHR5cusbU.jhZ3/Zaoa0X2v3/o1mXueLcWACG', 0, 0, 1),
(3, 'admin2', 'hakan23@gmail.com', 'paris', '$2y$10$c2FsdHlzYWx0eXNhbHR5cuzj9qkKiCpTp1u0VGOYpG/Z4EQXG/gym', 0, 0, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `offres_catalogue`
--
ALTER TABLE `offres_catalogue`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `offres_catalogue`
--
ALTER TABLE `offres_catalogue`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;