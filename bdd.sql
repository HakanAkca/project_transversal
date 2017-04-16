-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Jeu 13 Avril 2017 à 18:21
-- Version du serveur :  5.6.35
-- Version de PHP :  7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `project_transversal`
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
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `bottlesNumber` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `city`, `password`, `points`, `bottlesNumber`, `level`) VALUES
(7, 'admin', 'hakan.akca@supinternet.fr', 'paris', '$2y$10$c2FsdHlzYWx0eXNhbHR5cuFYLWqyckAGswm2.X4Qs/DrZlN4kmx/a', 0, 0, 1),
(8, 'hakan', 'test@gmail.com', 'paris', '$2y$10$c2FsdHlzYWx0eXNhbHR5cusbU.jhZ3/Zaoa0X2v3/o1mXueLcWACG', 0, 0, 1);

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
  ADD UNIQUE KEY `ID` (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;