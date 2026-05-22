-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 19 mai 2026 à 15:13
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `erp_scolaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entite_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entite_id` int UNSIGNED DEFAULT NULL,
  `donnees_avant` json DEFAULT NULL,
  `donnees_apres` json DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `affectations_cours`
--

DROP TABLE IF EXISTS `affectations_cours`;
CREATE TABLE IF NOT EXISTS `affectations_cours` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `personnel_id` int UNSIGNED NOT NULL,
  `classe_id` int UNSIGNED NOT NULL,
  `matiere_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `coefficient` decimal(4,2) DEFAULT '1.00',
  `heures_hebdo` decimal(4,1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_affect` (`personnel_id`,`classe_id`,`matiere_id`,`annee_scolaire_id`),
  KEY `classe_id` (`classe_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `affectations_cours`
--

INSERT INTO `affectations_cours` (`id`, `personnel_id`, `classe_id`, `matiere_id`, `annee_scolaire_id`, `coefficient`, `heures_hebdo`) VALUES
(1, 1, 1, 1, 1, 9.00, 5.0),
(2, 2, 1, 2, 1, 5.00, 4.0),
(3, 3, 1, 8, 1, 6.00, 2.0),
(4, 4, 1, 7, 1, 6.00, 2.0),
(5, 5, 1, 5, 1, 4.00, 2.0),
(6, 2, 1, 6, 1, 2.00, 2.0),
(7, 6, 1, 4, 1, 3.00, 2.0),
(8, 7, 1, 3, 1, 3.00, 2.0),
(9, 9, 1, 10, 1, 2.00, 2.0),
(10, 10, 1, 11, 1, 1.00, 2.0),
(11, 9, 1, 12, 1, 1.00, 2.0),
(12, 1, 2, 1, 1, 9.00, 5.0),
(13, 2, 2, 2, 1, 5.00, 4.0),
(14, 3, 2, 8, 1, 6.00, 2.0),
(15, 4, 2, 7, 1, 6.00, 2.0),
(16, 5, 2, 5, 1, 4.00, 2.0),
(17, 2, 2, 6, 1, 2.00, 2.0),
(18, 6, 2, 4, 1, 3.00, 2.0),
(19, 7, 2, 3, 1, 3.00, 2.0),
(20, 9, 2, 10, 1, 2.00, 2.0),
(21, 10, 2, 11, 1, 1.00, 2.0),
(22, 1, 3, 1, 1, 9.00, 5.0),
(23, 2, 3, 2, 1, 5.00, 4.0),
(24, 3, 3, 8, 1, 6.00, 2.0),
(25, 4, 3, 7, 1, 6.00, 2.0),
(26, 5, 3, 5, 1, 4.00, 2.0),
(27, 6, 3, 4, 1, 3.00, 2.0),
(28, 7, 3, 3, 1, 3.00, 2.0),
(29, 8, 3, 9, 1, 2.00, 2.0),
(30, 9, 3, 10, 1, 2.00, 2.0),
(31, 10, 3, 11, 1, 1.00, 2.0);

-- --------------------------------------------------------

--
-- Structure de la table `annees_scolaires`
--

DROP TABLE IF EXISTS `annees_scolaires`;
CREATE TABLE IF NOT EXISTS `annees_scolaires` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `libelle` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `en_cours` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_annee` (`etablissement_id`,`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annees_scolaires`
--

INSERT INTO `annees_scolaires` (`id`, `etablissement_id`, `libelle`, `date_debut`, `date_fin`, `en_cours`, `created_at`) VALUES
(1, 2, '2025-2026', '2025-09-02', '2026-07-10', 1, '2026-05-18 20:57:09');

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `auteur_id` int UNSIGNED NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cible` enum('tous','enseignants','eleves','parents','classe_specifique') COLLATE utf8mb4_unicode_ci DEFAULT 'tous',
  `classe_id` int UNSIGNED DEFAULT NULL,
  `priorite` enum('normale','importante','urgente') COLLATE utf8mb4_unicode_ci DEFAULT 'normale',
  `publie_le` datetime DEFAULT NULL,
  `expire_le` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`),
  KEY `auteur_id` (`auteur_id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `etablissement_id`, `auteur_id`, `titre`, `contenu`, `cible`, `classe_id`, `priorite`, `publie_le`, `expire_le`, `created_at`) VALUES
(2, 2, 3, 'Rentrée scolaire 2025-2026', 'Nous souhaitons la bienvenue à tous les élèves et au personnel pour cette nouvelle année scolaire. Les cours débutent le lundi 2 septembre 2025.', 'tous', NULL, 'normale', NULL, NULL, '2026-05-18 18:59:30'),
(3, 2, 3, 'Réunion parents d\'élèves', 'Une réunion d\'information pour les parents d\'élèves de Terminale se tiendra le samedi 18 octobre 2025 à 9h00 dans la grande salle.', 'parents', NULL, 'importante', NULL, NULL, '2026-05-18 18:59:30'),
(4, 2, 3, 'Compositions du 1er trimestre', 'Les compositions du premier trimestre auront lieu du 8 au 18 décembre 2025. Les emplois du temps détaillés seront affichés.', 'tous', NULL, 'urgente', NULL, NULL, '2026-05-18 18:59:30'),
(5, 2, 3, 'Résultats T1 disponibles', 'Les résultats et bulletins du premier trimestre sont disponibles. Les parents sont invités à se présenter à l\'administration.', 'parents', NULL, 'importante', NULL, NULL, '2026-05-18 18:59:31'),
(6, 2, 3, 'Concours général 2026', 'Les élèves souhaitant participer au Concours Général doivent déposer leur dossier avant le 15 janvier 2026.', 'eleves', NULL, 'normale', NULL, NULL, '2026-05-18 18:59:31');

-- --------------------------------------------------------

--
-- Structure de la table `auth_logs`
--

DROP TABLE IF EXISTS `auth_logs`;
CREATE TABLE IF NOT EXISTS `auth_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` enum('login','logout','echec') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `auth_logs`
--

INSERT INTO `auth_logs` (`id`, `user_id`, `ip`, `user_agent`, `action`, `created_at`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-18 17:52:56'),
(2, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'logout', '2026-05-18 18:56:45'),
(3, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-18 18:56:50'),
(5, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 09:11:11'),
(6, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 09:33:08'),
(7, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 09:53:36'),
(8, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 09:58:30'),
(9, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:03:38'),
(10, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:16:52'),
(11, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:17:16'),
(12, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:28:54'),
(13, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:32:54'),
(14, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:53:04'),
(15, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'logout', '2026-05-19 10:57:13'),
(16, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:58:00'),
(17, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'logout', '2026-05-19 10:58:36'),
(18, 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 10:59:08'),
(19, 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'logout', '2026-05-19 11:01:12'),
(20, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 11:02:07'),
(21, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'logout', '2026-05-19 11:02:48'),
(22, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'login', '2026-05-19 11:06:54'),
(23, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'echec', '2026-05-19 15:36:21'),
(24, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'echec', '2026-05-19 15:36:53'),
(25, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'echec', '2026-05-19 15:37:04');

-- --------------------------------------------------------

--
-- Structure de la table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
CREATE TABLE IF NOT EXISTS `bulletins` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `inscription_id` int UNSIGNED NOT NULL,
  `periode_id` int UNSIGNED NOT NULL,
  `moyenne_generale` decimal(5,2) DEFAULT NULL,
  `rang` smallint UNSIGNED DEFAULT NULL,
  `mention` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effectif_classe` smallint UNSIGNED DEFAULT NULL,
  `appreciation_conseil` text COLLATE utf8mb4_unicode_ci,
  `conseil_classe` enum('passage','redoublement','exclusion','felicitations','encouragements') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fichier_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genere_le` datetime DEFAULT NULL,
  `valide` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bulletin` (`inscription_id`,`periode_id`),
  KEY `periode_id` (`periode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bulletins`
--

INSERT INTO `bulletins` (`id`, `inscription_id`, `periode_id`, `moyenne_generale`, `rang`, `mention`, `effectif_classe`, `appreciation_conseil`, `conseil_classe`, `fichier_pdf`, `genere_le`, `valide`, `created_at`) VALUES
(1, 21, 1, 14.71, 2, 'Bien', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(2, 22, 1, 9.74, 11, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(3, 23, 1, 10.96, 7, 'Passable', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(4, 24, 1, 13.23, 6, 'Assez Bien', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(5, 25, 1, 6.73, 17, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(6, 26, 1, 10.88, 8, 'Passable', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(7, 27, 1, 13.73, 5, 'Assez Bien', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(8, 28, 1, 10.60, 10, 'Passable', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(9, 29, 1, 9.52, 13, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(10, 30, 1, 4.79, 18, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(11, 31, 1, 10.63, 9, 'Passable', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(12, 32, 1, 9.55, 12, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(13, 33, 1, 14.72, 1, 'Bien', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(14, 34, 1, 9.32, 15, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(15, 35, 1, 4.63, 19, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:57'),
(16, 36, 1, 14.10, 3, 'Bien', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:58'),
(17, 37, 1, 9.46, 14, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:58'),
(18, 38, 1, 8.66, 16, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:58'),
(19, 39, 1, 14.00, 4, 'Bien', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:58'),
(20, 40, 1, 4.41, 20, 'Ajourné', 20, NULL, NULL, NULL, NULL, 0, '2026-05-19 09:36:58');

-- --------------------------------------------------------

--
-- Structure de la table `categories_comptables`
--

DROP TABLE IF EXISTS `categories_comptables`;
CREATE TABLE IF NOT EXISTS `categories_comptables` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('recette','depense') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories_comptables`
--

INSERT INTO `categories_comptables` (`id`, `etablissement_id`, `nom`, `type`) VALUES
(1, 2, 'Scolarité', 'recette'),
(2, 2, 'Divers recettes', 'recette'),
(3, 2, 'Salaires', 'depense'),
(4, 2, 'Fournitures', 'depense'),
(5, 2, 'Entretien', 'depense');

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `niveau_id` int UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `effectif_max` tinyint UNSIGNED DEFAULT '40',
  `titulaire_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_classe` (`etablissement_id`,`annee_scolaire_id`,`nom`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`),
  KEY `niveau_id` (`niveau_id`),
  KEY `titulaire_id` (`titulaire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `etablissement_id`, `annee_scolaire_id`, `niveau_id`, `nom`, `effectif_max`, `titulaire_id`, `created_at`) VALUES
(1, 2, 1, 1, '2nde A', 36, NULL, '2026-05-18 20:57:13'),
(2, 2, 1, 2, '1ère D', 34, NULL, '2026-05-18 20:57:13'),
(3, 2, 1, 3, 'Tle C', 32, NULL, '2026-05-18 20:57:13');

-- --------------------------------------------------------

--
-- Structure de la table `creneaux_horaires`
--

DROP TABLE IF EXISTS `creneaux_horaires`;
CREATE TABLE IF NOT EXISTS `creneaux_horaires` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('cours','pause','repas') COLLATE utf8mb4_unicode_ci DEFAULT 'cours',
  `ordre` tinyint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `creneaux_horaires`
--

INSERT INTO `creneaux_horaires` (`id`, `etablissement_id`, `heure_debut`, `heure_fin`, `nom`, `type`, `ordre`) VALUES
(1, 2, '07:30:00', '08:30:00', '1er cours', 'cours', 1),
(2, 2, '08:30:00', '09:30:00', '2e cours', 'cours', 2),
(3, 2, '09:30:00', '10:30:00', '3e cours', 'cours', 3),
(4, 2, '10:45:00', '11:45:00', '4e cours', 'cours', 4),
(5, 2, '11:45:00', '12:45:00', '5e cours', 'cours', 5),
(6, 2, '13:30:00', '14:30:00', '6e cours', 'cours', 6),
(7, 2, '14:30:00', '15:30:00', '7e cours', 'cours', 7),
(8, 2, '15:30:00', '16:30:00', '8e cours', 'cours', 8);

-- --------------------------------------------------------

--
-- Structure de la table `cycles`
--

DROP TABLE IF EXISTS `cycles`;
CREATE TABLE IF NOT EXISTS `cycles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` tinyint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cycles`
--

INSERT INTO `cycles` (`id`, `etablissement_id`, `nom`, `ordre`) VALUES
(1, 2, 'Lycée', 1);

-- --------------------------------------------------------

--
-- Structure de la table `dossiers_paiement`
--

DROP TABLE IF EXISTS `dossiers_paiement`;
CREATE TABLE IF NOT EXISTS `dossiers_paiement` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `inscription_id` int UNSIGNED NOT NULL,
  `montant_total` decimal(12,2) NOT NULL,
  `montant_paye` decimal(12,2) DEFAULT '0.00',
  `statut` enum('non_paye','partiel','solde','exonere') COLLATE utf8mb4_unicode_ci DEFAULT 'non_paye',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inscription_id` (`inscription_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossiers_paiement`
--

INSERT INTO `dossiers_paiement` (`id`, `inscription_id`, `montant_total`, `montant_paye`, `statut`, `created_at`, `updated_at`) VALUES
(1, 1, 280000.00, 280000.00, 'solde', '2026-05-18 20:57:17', NULL),
(2, 2, 280000.00, 280000.00, 'solde', '2026-05-18 20:57:17', NULL),
(3, 3, 280000.00, 196000.00, 'partiel', '2026-05-18 20:57:17', NULL),
(4, 4, 280000.00, 84000.00, 'partiel', '2026-05-18 20:57:17', NULL),
(5, 5, 280000.00, 140000.00, 'partiel', '2026-05-18 20:57:18', NULL),
(6, 6, 280000.00, 168000.00, 'partiel', '2026-05-18 20:57:18', NULL),
(7, 7, 280000.00, 0.00, 'non_paye', '2026-05-18 20:57:18', NULL),
(8, 8, 280000.00, 0.00, 'non_paye', '2026-05-18 20:57:19', NULL),
(9, 9, 280000.00, 0.00, 'non_paye', '2026-05-18 20:57:19', NULL),
(10, 10, 280000.00, 280000.00, 'solde', '2026-05-18 20:57:19', NULL),
(11, 11, 280000.00, 280000.00, 'solde', '2026-05-18 20:57:20', NULL),
(12, 12, 280000.00, 280000.00, 'solde', '2026-05-18 20:57:20', NULL),
(13, 13, 280000.00, 196000.00, 'partiel', '2026-05-18 20:57:20', NULL),
(14, 14, 280000.00, 84000.00, 'partiel', '2026-05-18 20:57:21', NULL),
(15, 15, 280000.00, 140000.00, 'partiel', '2026-05-18 20:57:21', NULL),
(16, 16, 280000.00, 168000.00, 'partiel', '2026-05-18 20:57:21', NULL),
(17, 17, 280000.00, 0.00, 'non_paye', '2026-05-18 20:57:22', NULL),
(18, 18, 280000.00, 0.00, 'non_paye', '2026-05-18 20:57:22', NULL),
(19, 19, 280000.00, 0.00, 'non_paye', '2026-05-18 20:57:23', NULL),
(20, 20, 280000.00, 280000.00, 'solde', '2026-05-18 20:57:23', NULL),
(21, 21, 300000.00, 300000.00, 'solde', '2026-05-18 20:57:23', NULL),
(22, 22, 300000.00, 300000.00, 'solde', '2026-05-18 20:57:24', NULL),
(23, 23, 300000.00, 210000.00, 'partiel', '2026-05-18 20:57:25', NULL),
(24, 24, 300000.00, 90000.00, 'partiel', '2026-05-18 20:57:25', NULL),
(25, 25, 300000.00, 150000.00, 'partiel', '2026-05-18 20:57:25', NULL),
(26, 26, 300000.00, 180000.00, 'partiel', '2026-05-18 20:57:26', NULL),
(27, 27, 300000.00, 0.00, 'non_paye', '2026-05-18 20:57:26', NULL),
(28, 28, 300000.00, 0.00, 'non_paye', '2026-05-18 20:57:26', NULL),
(29, 29, 300000.00, 0.00, 'non_paye', '2026-05-18 20:57:27', NULL),
(30, 30, 300000.00, 300000.00, 'solde', '2026-05-18 20:57:27', NULL),
(31, 31, 300000.00, 300000.00, 'solde', '2026-05-18 20:57:27', NULL),
(32, 32, 300000.00, 300000.00, 'solde', '2026-05-18 20:57:28', NULL),
(33, 33, 300000.00, 210000.00, 'partiel', '2026-05-18 20:57:29', NULL),
(34, 34, 300000.00, 90000.00, 'partiel', '2026-05-18 20:57:29', NULL),
(35, 35, 300000.00, 150000.00, 'partiel', '2026-05-18 20:57:30', NULL),
(36, 36, 300000.00, 180000.00, 'partiel', '2026-05-18 20:57:30', NULL),
(37, 37, 300000.00, 0.00, 'non_paye', '2026-05-18 20:57:31', NULL),
(38, 38, 300000.00, 0.00, 'non_paye', '2026-05-18 20:57:31', NULL),
(39, 39, 300000.00, 0.00, 'non_paye', '2026-05-18 20:57:31', NULL),
(40, 40, 300000.00, 300000.00, 'solde', '2026-05-18 20:57:31', NULL),
(41, 41, 320000.00, 320000.00, 'solde', '2026-05-18 20:57:31', NULL),
(42, 42, 320000.00, 320000.00, 'solde', '2026-05-18 20:57:32', NULL),
(43, 43, 320000.00, 224000.00, 'partiel', '2026-05-18 20:57:32', NULL),
(44, 44, 320000.00, 96000.00, 'partiel', '2026-05-18 20:57:33', NULL),
(45, 45, 320000.00, 160000.00, 'partiel', '2026-05-18 20:57:33', NULL),
(46, 46, 320000.00, 192000.00, 'partiel', '2026-05-18 20:57:33', NULL),
(47, 47, 320000.00, 0.00, 'non_paye', '2026-05-18 20:57:33', NULL),
(48, 48, 320000.00, 0.00, 'non_paye', '2026-05-18 20:57:34', NULL),
(49, 49, 320000.00, 0.00, 'non_paye', '2026-05-18 20:57:34', NULL),
(50, 50, 320000.00, 320000.00, 'solde', '2026-05-18 20:57:34', NULL),
(51, 51, 320000.00, 320000.00, 'solde', '2026-05-18 20:57:34', NULL),
(52, 52, 320000.00, 320000.00, 'solde', '2026-05-18 20:57:35', NULL),
(53, 53, 320000.00, 224000.00, 'partiel', '2026-05-18 20:57:35', NULL),
(54, 54, 320000.00, 96000.00, 'partiel', '2026-05-18 20:57:36', NULL),
(55, 55, 320000.00, 160000.00, 'partiel', '2026-05-18 20:57:36', NULL),
(56, 56, 320000.00, 192000.00, 'partiel', '2026-05-18 20:57:36', NULL),
(57, 57, 320000.00, 0.00, 'non_paye', '2026-05-18 20:57:36', NULL),
(58, 58, 320000.00, 0.00, 'non_paye', '2026-05-18 20:57:37', NULL),
(59, 59, 320000.00, 0.00, 'non_paye', '2026-05-18 20:57:37', NULL),
(60, 60, 320000.00, 320000.00, 'solde', '2026-05-18 20:57:37', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `etablissement_id` int UNSIGNED NOT NULL,
  `matricule` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenoms` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationalite` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent1_nom` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent1_telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent1_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent1_profession` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent2_nom` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent2_telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent2_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `groupe_sanguin` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes_medicales` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('actif','diplome','transfere','exclu','archive') COLLATE utf8mb4_unicode_ci DEFAULT 'actif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricule` (`matricule`),
  KEY `user_id` (`user_id`),
  KEY `idx_etablissement` (`etablissement_id`),
  KEY `idx_nom` (`nom`,`prenoms`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `eleves`
--

INSERT INTO `eleves` (`id`, `user_id`, `etablissement_id`, `matricule`, `nom`, `prenoms`, `sexe`, `date_naissance`, `lieu_naissance`, `nationalite`, `photo`, `adresse`, `telephone`, `email`, `parent1_nom`, `parent1_telephone`, `parent1_email`, `parent1_profession`, `parent2_nom`, `parent2_telephone`, `parent2_email`, `groupe_sanguin`, `notes_medicales`, `statut`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 2, 'LMA25260001', 'KEÏTA', 'Koffi', 'M', '2009-09-02', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KOUYATÉ Seydou', '+225 07 27 78 60', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:17', NULL, NULL),
(2, NULL, 2, 'LMA25260002', 'COULIBALY', 'Aminata', 'F', '2008-04-17', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TRAORÉ Fatoumata', '+225 07 55 41 47', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:17', NULL, NULL),
(3, NULL, 2, 'LMA25260003', 'DOUMBIA', 'Ibrahim', 'M', '2008-12-22', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Seydou', '+225 07 21 83 69', NULL, NULL, NULL, NULL, NULL, 'B+', NULL, 'actif', '2026-05-18 20:57:17', NULL, NULL),
(4, NULL, 2, 'LMA25260004', 'COULIBALY', 'Aïssatou', 'F', '2008-06-14', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DEMBÉLÉ Fatoumata', '+225 07 51 41 92', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:17', NULL, NULL),
(5, NULL, 2, 'LMA25260005', 'CISSÉ', 'Adama', 'M', '2008-09-10', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DIABATÉ Seydou', '+225 07 71 44 87', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:18', NULL, NULL),
(6, NULL, 2, 'LMA25260006', 'CAMARA', 'Mariam', 'F', '2008-07-12', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Fatoumata', '+225 07 16 70 69', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:18', NULL, NULL),
(7, NULL, 2, 'LMA25260007', 'SOW', 'Mamadou', 'M', '2009-05-01', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'OUATTARA Seydou', '+225 07 27 68 97', NULL, NULL, NULL, NULL, NULL, 'O-', NULL, 'actif', '2026-05-18 20:57:18', NULL, NULL),
(8, NULL, 2, 'LMA25260008', 'TOURÉ', 'Safiatou', 'F', '2008-06-26', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DOUMBIA Fatoumata', '+225 07 35 44 29', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:19', NULL, NULL),
(9, NULL, 2, 'LMA25260009', 'DIABATÉ', 'Youssouf', 'M', '2008-01-24', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'COULIBALY Seydou', '+225 07 82 44 84', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:19', NULL, NULL),
(10, NULL, 2, 'LMA25260010', 'OUATTARA', 'Dieneba', 'F', '2009-08-25', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Fatoumata', '+225 07 11 27 77', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:19', NULL, NULL),
(11, NULL, 2, 'LMA25260011', 'COULIBALY', 'Lamine', 'M', '2008-05-05', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'CAMARA Seydou', '+225 07 75 51 83', NULL, NULL, NULL, NULL, NULL, 'A+', NULL, 'actif', '2026-05-18 20:57:19', NULL, NULL),
(12, NULL, 2, 'LMA25260012', 'SANOGO', 'Rokia', 'F', '2009-06-25', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'OUATTARA Fatoumata', '+225 07 30 67 21', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:20', NULL, NULL),
(13, NULL, 2, 'LMA25260013', 'KEÏTA', 'Oumar', 'M', '2009-05-24', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SOW Seydou', '+225 07 21 52 69', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:20', NULL, NULL),
(14, NULL, 2, 'LMA25260014', 'BARRY', 'Assiatou', 'F', '2009-11-26', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'BAMBA Fatoumata', '+225 07 89 97 92', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:21', NULL, NULL),
(15, NULL, 2, 'LMA25260015', 'SYLLA', 'Tiéba', 'M', '2009-04-05', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'FOFANA Seydou', '+225 07 63 32 19', NULL, NULL, NULL, NULL, NULL, 'A+', NULL, 'actif', '2026-05-18 20:57:21', NULL, NULL),
(16, NULL, 2, 'LMA25260016', 'SOW', 'Fanta', 'F', '2008-01-15', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'BAMBA Fatoumata', '+225 07 35 44 32', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:21', NULL, NULL),
(17, NULL, 2, 'LMA25260017', 'DIALLO', 'Lanciné', 'M', '2008-08-20', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TRAORÉ Seydou', '+225 07 47 68 71', NULL, NULL, NULL, NULL, NULL, 'A+', NULL, 'actif', '2026-05-18 20:57:22', NULL, NULL),
(18, NULL, 2, 'LMA25260018', 'KOUYATÉ', 'Saran', 'F', '2009-01-21', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TOURÉ Fatoumata', '+225 07 87 32 58', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:22', NULL, NULL),
(19, NULL, 2, 'LMA25260019', 'KONÉ', 'Foussény', 'M', '2009-07-12', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KOUYATÉ Seydou', '+225 07 11 35 80', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:22', NULL, NULL),
(20, NULL, 2, 'LMA25260020', 'BALDÉ', 'Mawa', 'F', '2008-09-11', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SYLLA Fatoumata', '+225 07 81 60 98', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:23', NULL, NULL),
(21, NULL, 2, 'LMA25260021', 'COULIBALY', 'Koffi', 'M', '2008-03-03', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KOUYATÉ Seydou', '+225 07 38 74 13', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:23', NULL, NULL),
(22, NULL, 2, 'LMA25260022', 'OUATTARA', 'Aminata', 'F', '2007-10-12', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TRAORÉ Fatoumata', '+225 07 21 97 59', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:24', NULL, NULL),
(23, NULL, 2, 'LMA25260023', 'SOW', 'Ibrahim', 'M', '2007-03-06', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'COULIBALY Seydou', '+225 07 45 61 97', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:24', NULL, NULL),
(24, NULL, 2, 'LMA25260024', 'CAMARA', 'Aïssatou', 'F', '2008-08-08', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KEÏTA Fatoumata', '+225 07 39 53 24', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:25', NULL, NULL),
(25, NULL, 2, 'LMA25260025', 'COULIBALY', 'Adama', 'M', '2007-02-01', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SYLLA Seydou', '+225 07 88 10 35', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:25', NULL, NULL),
(26, NULL, 2, 'LMA25260026', 'SANOGO', 'Mariam', 'F', '2007-11-09', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DIALLO Fatoumata', '+225 07 82 49 74', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:26', NULL, NULL),
(27, NULL, 2, 'LMA25260027', 'KONÉ', 'Mamadou', 'M', '2007-12-21', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'FOFANA Seydou', '+225 07 65 86 25', NULL, NULL, NULL, NULL, NULL, 'O-', NULL, 'actif', '2026-05-18 20:57:26', NULL, NULL),
(28, NULL, 2, 'LMA25260028', 'SOW', 'Safiatou', 'F', '2007-08-19', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'BARRY Fatoumata', '+225 07 46 56 55', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:26', NULL, NULL),
(29, NULL, 2, 'LMA25260029', 'BARRY', 'Youssouf', 'M', '2008-07-09', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SOW Seydou', '+225 07 91 46 67', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:27', NULL, NULL),
(30, NULL, 2, 'LMA25260030', 'SOW', 'Dieneba', 'F', '2008-05-02', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Fatoumata', '+225 07 77 23 30', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:27', NULL, NULL),
(31, NULL, 2, 'LMA25260031', 'SANOGO', 'Lamine', 'M', '2007-04-13', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KONÉ Seydou', '+225 07 11 61 36', NULL, NULL, NULL, NULL, NULL, 'O-', NULL, 'actif', '2026-05-18 20:57:27', NULL, NULL),
(32, NULL, 2, 'LMA25260032', 'SANOGO', 'Rokia', 'F', '2007-08-28', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KONÉ Fatoumata', '+225 07 47 14 79', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:28', NULL, NULL),
(33, NULL, 2, 'LMA25260033', 'TOURÉ', 'Oumar', 'M', '2007-08-17', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DIALLO Seydou', '+225 07 81 65 43', NULL, NULL, NULL, NULL, NULL, 'A+', NULL, 'actif', '2026-05-18 20:57:29', NULL, NULL),
(34, NULL, 2, 'LMA25260034', 'DIABATÉ', 'Assiatou', 'F', '2007-04-22', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'FOFANA Fatoumata', '+225 07 44 62 74', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:29', NULL, NULL),
(35, NULL, 2, 'LMA25260035', 'CISSÉ', 'Tiéba', 'M', '2007-07-18', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DOUMBIA Seydou', '+225 07 10 18 44', NULL, NULL, NULL, NULL, NULL, 'O-', NULL, 'actif', '2026-05-18 20:57:30', NULL, NULL),
(36, NULL, 2, 'LMA25260036', 'SOW', 'Fanta', 'F', '2008-08-26', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KEÏTA Fatoumata', '+225 07 84 92 88', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:30', NULL, NULL),
(37, NULL, 2, 'LMA25260037', 'KONÉ', 'Lanciné', 'M', '2008-06-03', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'CISSÉ Seydou', '+225 07 45 79 56', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:30', NULL, NULL),
(38, NULL, 2, 'LMA25260038', 'SANOGO', 'Saran', 'F', '2007-09-08', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KONÉ Fatoumata', '+225 07 49 39 46', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:31', NULL, NULL),
(39, NULL, 2, 'LMA25260039', 'SANOGO', 'Foussény', 'M', '2008-03-06', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KOUYATÉ Seydou', '+225 07 61 42 17', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:31', NULL, NULL),
(40, NULL, 2, 'LMA25260040', 'OUATTARA', 'Mawa', 'F', '2007-08-20', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SYLLA Fatoumata', '+225 07 54 59 90', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:31', NULL, NULL),
(41, NULL, 2, 'LMA25260041', 'BAMBA', 'Koffi', 'M', '2007-01-16', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'DOUMBIA Seydou', '+225 07 76 54 67', NULL, NULL, NULL, NULL, NULL, 'B+', NULL, 'actif', '2026-05-18 20:57:31', NULL, NULL),
(42, NULL, 2, 'LMA25260042', 'DOUMBIA', 'Aminata', 'F', '2007-10-24', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'BARRY Fatoumata', '+225 07 25 46 41', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:32', NULL, NULL),
(43, NULL, 2, 'LMA25260043', 'KEÏTA', 'Ibrahim', 'M', '2006-05-26', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TOURÉ Seydou', '+225 07 88 14 45', NULL, NULL, NULL, NULL, NULL, 'O-', NULL, 'actif', '2026-05-18 20:57:32', NULL, NULL),
(44, NULL, 2, 'LMA25260044', 'CISSÉ', 'Aïssatou', 'F', '2006-09-11', 'Yamoussoukro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KEÏTA Fatoumata', '+225 07 61 59 75', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:33', NULL, NULL),
(45, NULL, 2, 'LMA25260045', 'KEÏTA', 'Adama', 'M', '2007-01-19', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KONÉ Seydou', '+225 07 40 73 21', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:33', NULL, NULL),
(46, NULL, 2, 'LMA25260046', 'KOUYATÉ', 'Mariam', 'F', '2007-10-06', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KEÏTA Fatoumata', '+225 07 32 40 28', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:33', NULL, NULL),
(47, NULL, 2, 'LMA25260047', 'FOFANA', 'Mamadou', 'M', '2007-03-28', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KEÏTA Seydou', '+225 07 18 89 14', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:33', NULL, NULL),
(48, NULL, 2, 'LMA25260048', 'SOW', 'Safiatou', 'F', '2007-09-24', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SYLLA Fatoumata', '+225 07 22 17 59', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:34', NULL, NULL),
(49, NULL, 2, 'LMA25260049', 'KOUYATÉ', 'Youssouf', 'M', '2006-06-02', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'KONÉ Seydou', '+225 07 90 41 52', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:34', NULL, NULL),
(50, NULL, 2, 'LMA25260050', 'SYLLA', 'Dieneba', 'F', '2006-12-20', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TRAORÉ Fatoumata', '+225 07 30 17 52', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:34', NULL, NULL),
(51, NULL, 2, 'LMA25260051', 'SOW', 'Lamine', 'M', '2006-10-08', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'FOFANA Seydou', '+225 07 49 34 10', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:34', NULL, NULL),
(52, NULL, 2, 'LMA25260052', 'KONÉ', 'Rokia', 'F', '2006-07-15', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Fatoumata', '+225 07 25 46 38', NULL, NULL, NULL, NULL, NULL, 'AB+', NULL, 'actif', '2026-05-18 20:57:35', NULL, NULL),
(53, NULL, 2, 'LMA25260053', 'SANOGO', 'Oumar', 'M', '2007-10-04', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Seydou', '+225 07 61 56 31', NULL, NULL, NULL, NULL, NULL, 'A-', NULL, 'actif', '2026-05-18 20:57:35', NULL, NULL),
(54, NULL, 2, 'LMA25260054', 'BAMBA', 'Assiatou', 'F', '2006-08-04', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'FOFANA Fatoumata', '+225 07 50 52 10', NULL, NULL, NULL, NULL, NULL, 'B+', NULL, 'actif', '2026-05-18 20:57:36', NULL, NULL),
(55, NULL, 2, 'LMA25260055', 'DIALLO', 'Tiéba', 'M', '2006-11-07', 'San-Pédro', 'Ivoirienne', NULL, NULL, NULL, NULL, 'BAMBA Seydou', '+225 07 53 29 70', NULL, NULL, NULL, NULL, NULL, 'A+', NULL, 'actif', '2026-05-18 20:57:36', NULL, NULL),
(56, NULL, 2, 'LMA25260056', 'FOFANA', 'Fanta', 'F', '2006-08-14', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'FOFANA Fatoumata', '+225 07 18 14 32', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:36', NULL, NULL),
(57, NULL, 2, 'LMA25260057', 'TRAORÉ', 'Lanciné', 'M', '2006-03-23', 'Daloa', 'Ivoirienne', NULL, NULL, NULL, NULL, 'TOURÉ Seydou', '+225 07 13 86 35', NULL, NULL, NULL, NULL, NULL, 'B+', NULL, 'actif', '2026-05-18 20:57:36', NULL, NULL),
(58, NULL, 2, 'LMA25260058', 'SYLLA', 'Saran', 'F', '2007-02-12', 'Bouaké', 'Ivoirienne', NULL, NULL, NULL, NULL, 'CISSÉ Fatoumata', '+225 07 25 89 70', NULL, NULL, NULL, NULL, NULL, 'AB-', NULL, 'actif', '2026-05-18 20:57:37', NULL, NULL),
(59, NULL, 2, 'LMA25260059', 'OUATTARA', 'Foussény', 'M', '2006-04-04', 'Abidjan', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Seydou', '+225 07 37 52 30', NULL, NULL, NULL, NULL, NULL, 'B-', NULL, 'actif', '2026-05-18 20:57:37', NULL, NULL),
(60, NULL, 2, 'LMA25260060', 'KONÉ', 'Mawa', 'F', '2006-10-05', 'Korhogo', 'Ivoirienne', NULL, NULL, NULL, NULL, 'SANOGO Fatoumata', '+225 07 43 20 97', NULL, NULL, NULL, NULL, NULL, 'O+', NULL, 'actif', '2026-05-18 20:57:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `emplois_du_temps`
--

DROP TABLE IF EXISTS `emplois_du_temps`;
CREATE TABLE IF NOT EXISTS `emplois_du_temps` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `classe_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `affectation_id` int UNSIGNED NOT NULL,
  `salle_id` int UNSIGNED DEFAULT NULL,
  `creneau_id` int UNSIGNED NOT NULL,
  `jour_semaine` tinyint NOT NULL COMMENT '1=Lundi, 5=Vendredi, 6=Samedi',
  `valide_du` date DEFAULT NULL,
  `valide_au` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_enseignant_creneau` (`affectation_id`,`creneau_id`,`jour_semaine`,`annee_scolaire_id`),
  UNIQUE KEY `uk_salle_creneau` (`salle_id`,`creneau_id`,`jour_semaine`,`annee_scolaire_id`),
  KEY `classe_id` (`classe_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`),
  KEY `creneau_id` (`creneau_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `emplois_du_temps`
--

INSERT INTO `emplois_du_temps` (`id`, `classe_id`, `annee_scolaire_id`, `affectation_id`, `salle_id`, `creneau_id`, `jour_semaine`, `valide_du`, `valide_au`, `created_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, NULL, NULL, '2026-05-18 20:57:15'),
(2, 1, 1, 1, 1, 2, 1, NULL, NULL, '2026-05-18 20:57:15'),
(3, 1, 1, 2, 1, 3, 1, NULL, NULL, '2026-05-18 20:57:15'),
(4, 1, 1, 5, 1, 4, 1, NULL, NULL, '2026-05-18 20:57:16'),
(5, 1, 1, 4, 2, 5, 1, NULL, NULL, '2026-05-18 20:57:16'),
(6, 1, 1, 3, 2, 6, 1, NULL, NULL, '2026-05-18 20:57:16'),
(7, 1, 1, 7, 1, 1, 2, NULL, NULL, '2026-05-18 20:57:16'),
(8, 1, 1, 8, 1, 2, 2, NULL, NULL, '2026-05-18 20:57:16'),
(9, 1, 1, 6, 1, 3, 2, NULL, NULL, '2026-05-18 20:57:16'),
(10, 1, 1, 1, 1, 4, 2, NULL, NULL, '2026-05-18 20:57:16'),
(11, 1, 1, 2, 1, 5, 2, NULL, NULL, '2026-05-18 20:57:16'),
(12, 1, 1, 9, 5, 6, 2, NULL, NULL, '2026-05-18 20:57:16'),
(13, 1, 1, 3, 2, 1, 3, NULL, NULL, '2026-05-18 20:57:16'),
(14, 1, 1, 4, 2, 2, 3, NULL, NULL, '2026-05-18 20:57:16'),
(15, 1, 1, 1, 1, 3, 3, NULL, NULL, '2026-05-18 20:57:16'),
(16, 1, 1, 5, 1, 4, 3, NULL, NULL, '2026-05-18 20:57:16'),
(17, 1, 1, 7, 1, 5, 3, NULL, NULL, '2026-05-18 20:57:16'),
(18, 1, 1, 10, 6, 6, 3, NULL, NULL, '2026-05-18 20:57:16'),
(19, 1, 1, 2, 1, 1, 4, NULL, NULL, '2026-05-18 20:57:16'),
(20, 1, 1, 8, 1, 2, 4, NULL, NULL, '2026-05-18 20:57:16'),
(21, 1, 1, 1, 1, 4, 4, NULL, NULL, '2026-05-18 20:57:16'),
(22, 1, 1, 5, 1, 5, 4, NULL, NULL, '2026-05-18 20:57:16'),
(23, 1, 1, 6, 1, 6, 4, NULL, NULL, '2026-05-18 20:57:16'),
(24, 1, 1, 4, 2, 1, 5, NULL, NULL, '2026-05-18 20:57:16'),
(25, 1, 1, 3, 2, 2, 5, NULL, NULL, '2026-05-18 20:57:16'),
(26, 1, 1, 2, 1, 3, 5, NULL, NULL, '2026-05-18 20:57:16'),
(27, 1, 1, 7, 1, 4, 5, NULL, NULL, '2026-05-18 20:57:16'),
(28, 1, 1, 11, 1, 5, 5, NULL, NULL, '2026-05-18 20:57:16'),
(29, 1, 1, 1, 1, 6, 5, NULL, NULL, '2026-05-18 20:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

DROP TABLE IF EXISTS `emprunts`;
CREATE TABLE IF NOT EXISTS `emprunts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `livre_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour_prevu` date NOT NULL,
  `date_retour_effectif` date DEFAULT NULL,
  `statut` enum('en_cours','rendu','en_retard','perdu') COLLATE utf8mb4_unicode_ci DEFAULT 'en_cours',
  `amende` decimal(8,2) DEFAULT '0.00',
  `amende_payee` tinyint(1) DEFAULT '0',
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `livre_id` (`livre_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etablissements`
--

DROP TABLE IF EXISTS `etablissements`;
CREATE TABLE IF NOT EXISTS `etablissements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('primaire','college','lycee','universite') COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_etablissement` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `devise` char(3) COLLATE utf8mb4_unicode_ci DEFAULT 'XOF',
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Côte d''Ivoire',
  `actif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_etablissement` (`code_etablissement`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etablissements`
--

INSERT INTO `etablissements` (`id`, `nom`, `type`, `logo`, `adresse`, `telephone`, `email`, `site_web`, `code_etablissement`, `devise`, `pays`, `actif`, `created_at`, `updated_at`) VALUES
(2, 'Lycée Moderne d\'Abidjan', 'lycee', NULL, 'Cocody, Boulevard de la Corniche, Abidjan', '+225 27 22 44 00 11', 'contact@lma-abidjan.ci', NULL, 'LMA', 'XOF', 'Côte d\'Ivoire', 1, '2026-05-18 20:57:08', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `affectation_id` int UNSIGNED NOT NULL,
  `periode_id` int UNSIGNED NOT NULL,
  `type_evaluation_id` int UNSIGNED NOT NULL,
  `titre` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_evaluation` date NOT NULL,
  `note_sur` decimal(5,2) DEFAULT '20.00',
  `coefficient` decimal(4,2) DEFAULT '1.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `affectation_id` (`affectation_id`),
  KEY `periode_id` (`periode_id`),
  KEY `type_evaluation_id` (`type_evaluation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `affectation_id`, `periode_id`, `type_evaluation_id`, `titre`, `date_evaluation`, `note_sur`, `coefficient`, `created_at`) VALUES
(1, 1, 1, 1, 'Devoir n°1 — MATH', '2025-10-18', 20.00, 1.00, '2026-05-18 20:57:37'),
(2, 1, 1, 1, 'Devoir n°2 — MATH', '2025-11-10', 20.00, 1.00, '2026-05-18 20:57:38'),
(3, 1, 1, 2, 'Composition T1 — MATH', '2025-12-06', 20.00, 2.00, '2026-05-18 20:57:38'),
(4, 2, 1, 1, 'Devoir n°1 — FR', '2025-10-12', 20.00, 1.00, '2026-05-18 20:57:40'),
(5, 2, 1, 1, 'Devoir n°2 — FR', '2025-11-28', 20.00, 1.00, '2026-05-18 20:57:40'),
(6, 2, 1, 2, 'Composition T1 — FR', '2025-12-09', 20.00, 2.00, '2026-05-18 20:57:40'),
(7, 3, 1, 1, 'Devoir n°1 — PC', '2025-10-30', 20.00, 1.00, '2026-05-18 20:57:44'),
(8, 3, 1, 1, 'Devoir n°2 — PC', '2025-11-17', 20.00, 1.00, '2026-05-18 20:57:44'),
(9, 3, 1, 2, 'Composition T1 — PC', '2025-12-15', 20.00, 2.00, '2026-05-18 20:57:44'),
(10, 4, 1, 1, 'Devoir n°1 — SVT', '2025-10-24', 20.00, 1.00, '2026-05-18 20:57:47'),
(11, 4, 1, 1, 'Devoir n°2 — SVT', '2025-11-24', 20.00, 1.00, '2026-05-18 20:57:47'),
(12, 4, 1, 2, 'Composition T1 — SVT', '2025-12-01', 20.00, 2.00, '2026-05-18 20:57:47'),
(13, 5, 1, 1, 'Devoir n°1 — ANG', '2025-10-26', 20.00, 1.00, '2026-05-18 20:57:51'),
(14, 5, 1, 1, 'Devoir n°2 — ANG', '2025-11-10', 20.00, 1.00, '2026-05-18 20:57:51'),
(15, 5, 1, 2, 'Composition T1 — ANG', '2025-12-16', 20.00, 2.00, '2026-05-18 20:57:51'),
(16, 6, 1, 1, 'Devoir n°1 — ESP', '2025-10-18', 20.00, 1.00, '2026-05-18 20:57:54'),
(17, 6, 1, 1, 'Devoir n°2 — ESP', '2025-11-22', 20.00, 1.00, '2026-05-18 20:57:54'),
(18, 6, 1, 2, 'Composition T1 — ESP', '2025-12-05', 20.00, 2.00, '2026-05-18 20:57:54'),
(19, 7, 1, 1, 'Devoir n°1 — HG', '2025-10-30', 20.00, 1.00, '2026-05-18 20:57:57'),
(20, 7, 1, 1, 'Devoir n°2 — HG', '2025-11-29', 20.00, 1.00, '2026-05-18 20:57:57'),
(21, 7, 1, 2, 'Composition T1 — HG', '2025-12-12', 20.00, 2.00, '2026-05-18 20:57:57'),
(22, 8, 1, 1, 'Devoir n°1 — PHILO', '2025-10-07', 20.00, 1.00, '2026-05-18 20:58:01'),
(23, 8, 1, 1, 'Devoir n°2 — PHILO', '2025-11-10', 20.00, 1.00, '2026-05-18 20:58:01'),
(24, 8, 1, 2, 'Composition T1 — PHILO', '2025-12-08', 20.00, 2.00, '2026-05-18 20:58:01'),
(25, 9, 1, 1, 'Devoir n°1 — EPS', '2025-10-04', 20.00, 1.00, '2026-05-18 20:58:05'),
(26, 9, 1, 1, 'Devoir n°2 — EPS', '2025-11-09', 20.00, 1.00, '2026-05-18 20:58:05'),
(27, 9, 1, 2, 'Composition T1 — EPS', '2025-12-15', 20.00, 2.00, '2026-05-18 20:58:05'),
(28, 10, 1, 1, 'Devoir n°1 — INFO', '2025-10-19', 20.00, 1.00, '2026-05-18 20:58:09'),
(29, 10, 1, 1, 'Devoir n°2 — INFO', '2025-11-28', 20.00, 1.00, '2026-05-18 20:58:09'),
(30, 10, 1, 2, 'Composition T1 — INFO', '2025-12-03', 20.00, 2.00, '2026-05-18 20:58:09'),
(31, 11, 1, 1, 'Devoir n°1 — ARTS', '2025-10-15', 20.00, 1.00, '2026-05-18 20:58:12'),
(32, 11, 1, 1, 'Devoir n°2 — ARTS', '2025-11-29', 20.00, 1.00, '2026-05-18 20:58:12'),
(33, 11, 1, 2, 'Composition T1 — ARTS', '2025-12-08', 20.00, 2.00, '2026-05-18 20:58:12'),
(34, 12, 1, 1, 'Devoir n°1 — MATH', '2025-10-29', 20.00, 1.00, '2026-05-18 20:58:15'),
(35, 12, 1, 1, 'Devoir n°2 — MATH', '2025-11-11', 20.00, 1.00, '2026-05-18 20:58:15'),
(36, 12, 1, 2, 'Composition T1 — MATH', '2025-12-03', 20.00, 2.00, '2026-05-18 20:58:15'),
(37, 13, 1, 1, 'Devoir n°1 — FR', '2025-10-01', 20.00, 1.00, '2026-05-18 20:58:17'),
(38, 13, 1, 1, 'Devoir n°2 — FR', '2025-11-19', 20.00, 1.00, '2026-05-18 20:58:17'),
(39, 13, 1, 2, 'Composition T1 — FR', '2025-12-11', 20.00, 2.00, '2026-05-18 20:58:17'),
(40, 14, 1, 1, 'Devoir n°1 — PC', '2025-10-09', 20.00, 1.00, '2026-05-18 20:58:20'),
(41, 14, 1, 1, 'Devoir n°2 — PC', '2025-11-29', 20.00, 1.00, '2026-05-18 20:58:20'),
(42, 14, 1, 2, 'Composition T1 — PC', '2025-12-12', 20.00, 2.00, '2026-05-18 20:58:20'),
(43, 15, 1, 1, 'Devoir n°1 — SVT', '2025-10-11', 20.00, 1.00, '2026-05-18 20:58:24'),
(44, 15, 1, 1, 'Devoir n°2 — SVT', '2025-11-10', 20.00, 1.00, '2026-05-18 20:58:24'),
(45, 15, 1, 2, 'Composition T1 — SVT', '2025-12-10', 20.00, 2.00, '2026-05-18 20:58:24'),
(46, 16, 1, 1, 'Devoir n°1 — ANG', '2025-10-23', 20.00, 1.00, '2026-05-18 20:58:28'),
(47, 16, 1, 1, 'Devoir n°2 — ANG', '2025-11-23', 20.00, 1.00, '2026-05-18 20:58:28'),
(48, 16, 1, 2, 'Composition T1 — ANG', '2025-12-08', 20.00, 2.00, '2026-05-18 20:58:28'),
(49, 17, 1, 1, 'Devoir n°1 — ESP', '2025-10-19', 20.00, 1.00, '2026-05-18 20:58:31'),
(50, 17, 1, 1, 'Devoir n°2 — ESP', '2025-11-13', 20.00, 1.00, '2026-05-18 20:58:31'),
(51, 17, 1, 2, 'Composition T1 — ESP', '2025-12-04', 20.00, 2.00, '2026-05-18 20:58:31'),
(52, 18, 1, 1, 'Devoir n°1 — HG', '2025-10-15', 20.00, 1.00, '2026-05-18 20:58:35'),
(53, 18, 1, 1, 'Devoir n°2 — HG', '2025-11-13', 20.00, 1.00, '2026-05-18 20:58:35'),
(54, 18, 1, 2, 'Composition T1 — HG', '2025-12-11', 20.00, 2.00, '2026-05-18 20:58:35'),
(55, 19, 1, 1, 'Devoir n°1 — PHILO', '2025-10-10', 20.00, 1.00, '2026-05-18 20:58:38'),
(56, 19, 1, 1, 'Devoir n°2 — PHILO', '2025-11-26', 20.00, 1.00, '2026-05-18 20:58:38'),
(57, 19, 1, 2, 'Composition T1 — PHILO', '2025-12-17', 20.00, 2.00, '2026-05-18 20:58:38'),
(58, 20, 1, 1, 'Devoir n°1 — EPS', '2025-10-11', 20.00, 1.00, '2026-05-18 20:58:42'),
(59, 20, 1, 1, 'Devoir n°2 — EPS', '2025-11-24', 20.00, 1.00, '2026-05-18 20:58:42'),
(60, 20, 1, 2, 'Composition T1 — EPS', '2025-12-03', 20.00, 2.00, '2026-05-18 20:58:42'),
(61, 21, 1, 1, 'Devoir n°1 — INFO', '2025-10-12', 20.00, 1.00, '2026-05-18 20:58:45'),
(62, 21, 1, 1, 'Devoir n°2 — INFO', '2025-11-21', 20.00, 1.00, '2026-05-18 20:58:46'),
(63, 21, 1, 2, 'Composition T1 — INFO', '2025-12-10', 20.00, 2.00, '2026-05-18 20:58:46'),
(64, 22, 1, 1, 'Devoir n°1 — MATH', '2025-10-10', 20.00, 1.00, '2026-05-18 20:58:48'),
(65, 22, 1, 1, 'Devoir n°2 — MATH', '2025-11-14', 20.00, 1.00, '2026-05-18 20:58:48'),
(66, 22, 1, 2, 'Composition T1 — MATH', '2025-12-11', 20.00, 2.00, '2026-05-18 20:58:49'),
(67, 23, 1, 1, 'Devoir n°1 — FR', '2025-10-14', 20.00, 1.00, '2026-05-18 20:58:51'),
(68, 23, 1, 1, 'Devoir n°2 — FR', '2025-11-18', 20.00, 1.00, '2026-05-18 20:58:51'),
(69, 23, 1, 2, 'Composition T1 — FR', '2025-12-02', 20.00, 2.00, '2026-05-18 20:58:51'),
(70, 24, 1, 1, 'Devoir n°1 — PC', '2025-10-23', 20.00, 1.00, '2026-05-18 20:58:54'),
(71, 24, 1, 1, 'Devoir n°2 — PC', '2025-11-19', 20.00, 1.00, '2026-05-18 20:58:54'),
(72, 24, 1, 2, 'Composition T1 — PC', '2025-12-14', 20.00, 2.00, '2026-05-18 20:58:54'),
(73, 25, 1, 1, 'Devoir n°1 — SVT', '2025-10-15', 20.00, 1.00, '2026-05-18 20:58:56'),
(74, 25, 1, 1, 'Devoir n°2 — SVT', '2025-11-29', 20.00, 1.00, '2026-05-18 20:58:56'),
(75, 25, 1, 2, 'Composition T1 — SVT', '2025-12-05', 20.00, 2.00, '2026-05-18 20:58:56'),
(76, 26, 1, 1, 'Devoir n°1 — ANG', '2025-10-11', 20.00, 1.00, '2026-05-18 20:59:01'),
(77, 26, 1, 1, 'Devoir n°2 — ANG', '2025-11-01', 20.00, 1.00, '2026-05-18 20:59:01'),
(78, 26, 1, 2, 'Composition T1 — ANG', '2025-12-05', 20.00, 2.00, '2026-05-18 20:59:01'),
(79, 27, 1, 1, 'Devoir n°1 — HG', '2025-10-04', 20.00, 1.00, '2026-05-18 20:59:05'),
(80, 27, 1, 1, 'Devoir n°2 — HG', '2025-11-20', 20.00, 1.00, '2026-05-18 20:59:05'),
(81, 27, 1, 2, 'Composition T1 — HG', '2025-12-02', 20.00, 2.00, '2026-05-18 20:59:05'),
(82, 28, 1, 1, 'Devoir n°1 — PHILO', '2025-10-09', 20.00, 1.00, '2026-05-18 20:59:08'),
(83, 28, 1, 1, 'Devoir n°2 — PHILO', '2025-11-15', 20.00, 1.00, '2026-05-18 20:59:09'),
(84, 28, 1, 2, 'Composition T1 — PHILO', '2025-12-15', 20.00, 2.00, '2026-05-18 20:59:09'),
(85, 29, 1, 1, 'Devoir n°1 — ECO', '2025-10-13', 20.00, 1.00, '2026-05-18 20:59:12'),
(86, 29, 1, 1, 'Devoir n°2 — ECO', '2025-11-25', 20.00, 1.00, '2026-05-18 20:59:12'),
(87, 29, 1, 2, 'Composition T1 — ECO', '2025-12-02', 20.00, 2.00, '2026-05-18 20:59:12'),
(88, 30, 1, 1, 'Devoir n°1 — EPS', '2025-10-26', 20.00, 1.00, '2026-05-18 20:59:15'),
(89, 30, 1, 1, 'Devoir n°2 — EPS', '2025-11-02', 20.00, 1.00, '2026-05-18 20:59:15'),
(90, 30, 1, 2, 'Composition T1 — EPS', '2025-12-07', 20.00, 2.00, '2026-05-18 20:59:15'),
(91, 31, 1, 1, 'Devoir n°1 — INFO', '2025-10-25', 20.00, 1.00, '2026-05-18 20:59:17'),
(92, 31, 1, 1, 'Devoir n°2 — INFO', '2025-11-18', 20.00, 1.00, '2026-05-18 20:59:18'),
(93, 31, 1, 2, 'Composition T1 — INFO', '2025-12-16', 20.00, 2.00, '2026-05-18 20:59:18');

-- --------------------------------------------------------

--
-- Structure de la table `examens`
--

DROP TABLE IF EXISTS `examens`;
CREATE TABLE IF NOT EXISTS `examens` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `periode_id` int UNSIGNED DEFAULT NULL,
  `nom` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('interne','officiel','rattrapage') COLLATE utf8mb4_unicode_ci DEFAULT 'interne',
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`),
  KEY `periode_id` (`periode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `examens`
--

INSERT INTO `examens` (`id`, `etablissement_id`, `annee_scolaire_id`, `periode_id`, `nom`, `type`, `date_debut`, `date_fin`, `created_at`) VALUES
(1, 2, 1, 1, 'Compositions du 1er Trimestre 2025', 'interne', '2025-12-08', '2025-12-18', '2026-05-18 20:59:31');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `eleve_id` int UNSIGNED NOT NULL,
  `classe_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `date_inscription` date NOT NULL,
  `statut` enum('inscrit','en_attente','annule') COLLATE utf8mb4_unicode_ci DEFAULT 'inscrit',
  `numero_ordre` smallint UNSIGNED DEFAULT NULL,
  `ancien_etablissement` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documents_fournis` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_inscr` (`eleve_id`,`annee_scolaire_id`),
  KEY `classe_id` (`classe_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `eleve_id`, `classe_id`, `annee_scolaire_id`, `date_inscription`, `statut`, `numero_ordre`, `ancien_etablissement`, `documents_fournis`, `created_at`) VALUES
(1, 1, 1, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:17'),
(2, 2, 1, 1, '2025-09-04', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:17'),
(3, 3, 1, 1, '2025-09-16', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:17'),
(4, 4, 1, 1, '2025-09-15', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:17'),
(5, 5, 1, 1, '2025-09-08', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:18'),
(6, 6, 1, 1, '2025-09-03', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:18'),
(7, 7, 1, 1, '2025-09-09', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:18'),
(8, 8, 1, 1, '2025-09-09', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:19'),
(9, 9, 1, 1, '2025-09-13', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:19'),
(10, 10, 1, 1, '2025-09-12', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:19'),
(11, 11, 1, 1, '2025-09-03', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:20'),
(12, 12, 1, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:20'),
(13, 13, 1, 1, '2025-09-19', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:20'),
(14, 14, 1, 1, '2025-09-08', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:21'),
(15, 15, 1, 1, '2025-09-15', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:21'),
(16, 16, 1, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:21'),
(17, 17, 1, 1, '2025-09-05', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:22'),
(18, 18, 1, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:22'),
(19, 19, 1, 1, '2025-09-08', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:23'),
(20, 20, 1, 1, '2025-09-16', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:23'),
(21, 21, 2, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:23'),
(22, 22, 2, 1, '2025-09-16', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:24'),
(23, 23, 2, 1, '2025-09-17', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:25'),
(24, 24, 2, 1, '2025-09-14', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:25'),
(25, 25, 2, 1, '2025-09-11', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:25'),
(26, 26, 2, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:26'),
(27, 27, 2, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:26'),
(28, 28, 2, 1, '2025-09-06', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:26'),
(29, 29, 2, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:27'),
(30, 30, 2, 1, '2025-09-17', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:27'),
(31, 31, 2, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:27'),
(32, 32, 2, 1, '2025-09-13', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:28'),
(33, 33, 2, 1, '2025-09-13', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:29'),
(34, 34, 2, 1, '2025-09-15', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:29'),
(35, 35, 2, 1, '2025-09-12', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:30'),
(36, 36, 2, 1, '2025-09-04', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:30'),
(37, 37, 2, 1, '2025-09-03', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:31'),
(38, 38, 2, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:31'),
(39, 39, 2, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:31'),
(40, 40, 2, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:31'),
(41, 41, 3, 1, '2025-09-11', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:31'),
(42, 42, 3, 1, '2025-09-19', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:32'),
(43, 43, 3, 1, '2025-09-09', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:32'),
(44, 44, 3, 1, '2025-09-17', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:33'),
(45, 45, 3, 1, '2025-09-12', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:33'),
(46, 46, 3, 1, '2025-09-02', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:33'),
(47, 47, 3, 1, '2025-09-19', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:33'),
(48, 48, 3, 1, '2025-09-08', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:34'),
(49, 49, 3, 1, '2025-09-11', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:34'),
(50, 50, 3, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:34'),
(51, 51, 3, 1, '2025-09-17', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:34'),
(52, 52, 3, 1, '2025-09-10', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:35'),
(53, 53, 3, 1, '2025-09-09', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:35'),
(54, 54, 3, 1, '2025-09-08', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:36'),
(55, 55, 3, 1, '2025-09-17', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:36'),
(56, 56, 3, 1, '2025-09-12', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:36'),
(57, 57, 3, 1, '2025-09-04', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:36'),
(58, 58, 3, 1, '2025-09-08', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:37'),
(59, 59, 3, 1, '2025-09-09', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:37'),
(60, 60, 3, 1, '2025-09-17', 'inscrit', NULL, NULL, NULL, '2026-05-18 20:57:37');

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

DROP TABLE IF EXISTS `livres`;
CREATE TABLE IF NOT EXISTS `livres` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auteur` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editeur` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annee_edition` year DEFAULT NULL,
  `categorie` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localisation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exemplaires_total` smallint UNSIGNED DEFAULT '1',
  `exemplaires_dispo` smallint UNSIGNED DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `etablissement_id`, `isbn`, `titre`, `auteur`, `editeur`, `annee_edition`, `categorie`, `localisation`, `exemplaires_total`, `exemplaires_dispo`, `created_at`) VALUES
(1, 2, '978-2-01-235407-8', 'Mathématiques Tle C', 'Collectif Nathan', 'Nathan', '2023', 'Mathématiques', 'Rayon Mathématiques', 5, 3, '2026-05-18 20:59:28'),
(2, 2, '978-2-01-395001-0', 'Français 1ère', 'Collectif Hachette', 'Hachette', '2022', 'Français', 'Rayon Français', 4, 2, '2026-05-18 20:59:28'),
(3, 2, '978-2-01-167892-3', 'Physique-Chimie 2nde', 'Collectif Belin', 'Belin', '2023', 'Sciences', 'Rayon Sciences', 6, 4, '2026-05-18 20:59:29'),
(4, 2, '978-2-13-082456-7', 'Le Père Goriot', 'Honoré de Balzac', 'GF Flammarion', '0000', 'Littérature', 'Rayon Littérature', 3, 3, '2026-05-18 20:59:29'),
(5, 2, '978-2-07-040850-4', 'L\'Aventure ambiguë', 'Cheikh Hamidou Kane', 'Julliard', '1961', 'Littérature', 'Rayon Littérature', 4, 4, '2026-05-18 20:59:29'),
(6, 2, '978-2-07-036024-5', 'Une si longue lettre', 'Mariama Bâ', 'NEA', '1979', 'Littérature', 'Rayon Littérature', 5, 5, '2026-05-18 20:59:29'),
(7, 2, '978-2-02-023741-2', 'Histoire Géo Terminale', 'Collectif', 'Nathan', '2023', 'Histoire', 'Rayon Histoire', 4, 2, '2026-05-18 20:59:29'),
(8, 2, '978-2-01-140256-1', 'Philosophie Tle', 'Collectif', 'Hachette', '2023', 'Philosophie', 'Rayon Philosophie', 3, 1, '2026-05-18 20:59:29'),
(9, 2, '978-2-84879-122-3', 'SVT Première', 'Collectif', 'Didier', '2022', 'Sciences', 'Rayon Sciences', 5, 3, '2026-05-18 20:59:29'),
(10, 2, '978-2-01-000001-1', 'Anglais Seconde Step In', 'Collectif', 'Hachette', '2023', 'Anglais', 'Rayon Anglais', 4, 4, '2026-05-18 20:59:30'),
(11, 2, '978-2-01-000002-2', 'Python pour les lycéens', 'Gilles Dowek', 'Eyrolles', '2022', 'Informatique', 'Rayon Informatique', 3, 2, '2026-05-18 20:59:30'),
(12, 2, '978-2-01-000003-3', 'Économie Terminale ES', 'Collectif', 'Nathan', '2022', 'Économie', 'Rayon Économie', 4, 3, '2026-05-18 20:59:30');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('principale','optionnelle','activite') COLLATE utf8mb4_unicode_ci DEFAULT 'principale',
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `etablissement_id`, `nom`, `code`, `type`) VALUES
(1, 2, 'Mathématiques', 'MATH', 'principale'),
(2, 2, 'Français', 'FR', 'principale'),
(3, 2, 'Philosophie', 'PHILO', 'principale'),
(4, 2, 'Histoire-Géographie', 'HG', 'principale'),
(5, 2, 'Anglais', 'ANG', 'principale'),
(6, 2, 'Espagnol', 'ESP', 'principale'),
(7, 2, 'Sciences de la Vie et de la Terre', 'SVT', 'principale'),
(8, 2, 'Physique-Chimie', 'PC', 'principale'),
(9, 2, 'Économie', 'ECO', 'principale'),
(10, 2, 'Éducation Physique et Sportive', 'EPS', 'activite'),
(11, 2, 'Informatique', 'INFO', 'optionnelle'),
(12, 2, 'Arts Plastiques', 'ARTS', 'activite');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `expediteur_id` int UNSIGNED NOT NULL,
  `destinataire_id` int UNSIGNED NOT NULL,
  `sujet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenu` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `lu_le` datetime DEFAULT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `expediteur_id` (`expediteur_id`),
  KEY `destinataire_id` (`destinataire_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `moyennes`
--

DROP TABLE IF EXISTS `moyennes`;
CREATE TABLE IF NOT EXISTS `moyennes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `inscription_id` int UNSIGNED NOT NULL,
  `affectation_id` int UNSIGNED DEFAULT NULL,
  `periode_id` int UNSIGNED DEFAULT NULL,
  `moyenne` decimal(5,2) DEFAULT NULL,
  `rang` smallint UNSIGNED DEFAULT NULL,
  `appreciation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_moy` (`inscription_id`,`affectation_id`,`periode_id`),
  KEY `affectation_id` (`affectation_id`),
  KEY `periode_id` (`periode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `moyennes`
--

INSERT INTO `moyennes` (`id`, `inscription_id`, `affectation_id`, `periode_id`, `moyenne`, `rang`, `appreciation`, `updated_at`) VALUES
(1, 21, 12, 1, 15.53, NULL, NULL, '2026-05-19 09:36:47'),
(2, 21, 13, 1, 15.01, NULL, NULL, '2026-05-19 09:36:47'),
(3, 21, 14, 1, 11.47, NULL, NULL, '2026-05-19 09:36:47'),
(4, 21, 15, 1, 13.86, NULL, NULL, '2026-05-19 09:36:47'),
(5, 21, 16, 1, 17.62, NULL, NULL, '2026-05-19 09:36:47'),
(6, 21, 17, 1, 14.86, NULL, NULL, '2026-05-19 09:36:47'),
(7, 21, 18, 1, 15.14, NULL, NULL, '2026-05-19 09:36:47'),
(8, 21, 19, 1, 15.16, NULL, NULL, '2026-05-19 09:36:47'),
(9, 21, 20, 1, 14.91, NULL, NULL, '2026-05-19 09:36:47'),
(10, 21, 21, 1, 15.50, NULL, NULL, '2026-05-19 09:36:48'),
(11, 21, NULL, 1, 14.71, 2, 'Bien', '2026-05-19 09:36:56'),
(12, 22, 12, 1, 11.50, NULL, NULL, '2026-05-19 09:36:48'),
(13, 22, 13, 1, 12.27, NULL, NULL, '2026-05-19 09:36:48'),
(14, 22, 14, 1, 10.31, NULL, NULL, '2026-05-19 09:36:48'),
(15, 22, 15, 1, 8.66, NULL, NULL, '2026-05-19 09:36:48'),
(16, 22, 16, 1, 7.81, NULL, NULL, '2026-05-19 09:36:48'),
(17, 22, 17, 1, 9.13, NULL, NULL, '2026-05-19 09:36:48'),
(18, 22, 18, 1, 7.84, NULL, NULL, '2026-05-19 09:36:48'),
(19, 22, 19, 1, 8.13, NULL, NULL, '2026-05-19 09:36:48'),
(20, 22, 20, 1, 7.78, NULL, NULL, '2026-05-19 09:36:48'),
(21, 22, 21, 1, 7.68, NULL, NULL, '2026-05-19 09:36:48'),
(22, 22, NULL, 1, 9.74, 11, 'Insuffisant', '2026-05-19 09:36:56'),
(23, 23, 12, 1, 9.12, NULL, NULL, '2026-05-19 09:36:48'),
(24, 23, 13, 1, 12.82, NULL, NULL, '2026-05-19 09:36:48'),
(25, 23, 14, 1, 11.35, NULL, NULL, '2026-05-19 09:36:48'),
(26, 23, 15, 1, 11.43, NULL, NULL, '2026-05-19 09:36:48'),
(27, 23, 16, 1, 12.18, NULL, NULL, '2026-05-19 09:36:48'),
(28, 23, 17, 1, 8.17, NULL, NULL, '2026-05-19 09:36:48'),
(29, 23, 18, 1, 8.98, NULL, NULL, '2026-05-19 09:36:48'),
(30, 23, 19, 1, 13.10, NULL, NULL, '2026-05-19 09:36:48'),
(31, 23, 20, 1, 11.80, NULL, NULL, '2026-05-19 09:36:48'),
(32, 23, 21, 1, 11.55, NULL, NULL, '2026-05-19 09:36:48'),
(33, 23, NULL, 1, 10.96, 7, 'Passable', '2026-05-19 09:36:56'),
(34, 24, 12, 1, 10.35, NULL, NULL, '2026-05-19 09:36:48'),
(35, 24, 13, 1, 11.34, NULL, NULL, '2026-05-19 09:36:48'),
(36, 24, 14, 1, 14.10, NULL, NULL, '2026-05-19 09:36:48'),
(37, 24, 15, 1, 14.54, NULL, NULL, '2026-05-19 09:36:49'),
(38, 24, 16, 1, 14.40, NULL, NULL, '2026-05-19 09:36:49'),
(39, 24, 17, 1, 17.04, NULL, NULL, '2026-05-19 09:36:49'),
(40, 24, 18, 1, 14.28, NULL, NULL, '2026-05-19 09:36:49'),
(41, 24, 19, 1, 13.56, NULL, NULL, '2026-05-19 09:36:49'),
(42, 24, 20, 1, 14.61, NULL, NULL, '2026-05-19 09:36:49'),
(43, 24, 21, 1, 16.17, NULL, NULL, '2026-05-19 09:36:49'),
(44, 24, NULL, 1, 13.23, 6, 'Assez Bien', '2026-05-19 09:36:56'),
(45, 25, 12, 1, 7.85, NULL, NULL, '2026-05-19 09:36:49'),
(46, 25, 13, 1, 5.88, NULL, NULL, '2026-05-19 09:36:49'),
(47, 25, 14, 1, 8.13, NULL, NULL, '2026-05-19 09:36:49'),
(48, 25, 15, 1, 5.04, NULL, NULL, '2026-05-19 09:36:49'),
(49, 25, 16, 1, 8.40, NULL, NULL, '2026-05-19 09:36:49'),
(50, 25, 17, 1, 4.00, NULL, NULL, '2026-05-19 09:36:49'),
(51, 25, 18, 1, 5.43, NULL, NULL, '2026-05-19 09:36:49'),
(52, 25, 19, 1, 7.25, NULL, NULL, '2026-05-19 09:36:49'),
(53, 25, 20, 1, 6.46, NULL, NULL, '2026-05-19 09:36:49'),
(54, 25, 21, 1, 4.27, NULL, NULL, '2026-05-19 09:36:49'),
(55, 25, NULL, 1, 6.73, 17, 'Insuffisant', '2026-05-19 09:36:56'),
(56, 26, 12, 1, 12.14, NULL, NULL, '2026-05-19 09:36:49'),
(57, 26, 13, 1, 8.71, NULL, NULL, '2026-05-19 09:36:49'),
(58, 26, 14, 1, 10.54, NULL, NULL, '2026-05-19 09:36:49'),
(59, 26, 15, 1, 12.03, NULL, NULL, '2026-05-19 09:36:49'),
(60, 26, 16, 1, 12.26, NULL, NULL, '2026-05-19 09:36:49'),
(61, 26, 17, 1, 10.04, NULL, NULL, '2026-05-19 09:36:50'),
(62, 26, 18, 1, 12.58, NULL, NULL, '2026-05-19 09:36:50'),
(63, 26, 19, 1, 8.00, NULL, NULL, '2026-05-19 09:36:50'),
(64, 26, 20, 1, 8.42, NULL, NULL, '2026-05-19 09:36:50'),
(65, 26, 21, 1, 10.38, NULL, NULL, '2026-05-19 09:36:50'),
(66, 26, NULL, 1, 10.88, 8, 'Passable', '2026-05-19 09:36:56'),
(67, 27, 12, 1, 11.68, NULL, NULL, '2026-05-19 09:36:50'),
(68, 27, 13, 1, 15.19, NULL, NULL, '2026-05-19 09:36:50'),
(69, 27, 14, 1, 15.04, NULL, NULL, '2026-05-19 09:36:50'),
(70, 27, 15, 1, 15.62, NULL, NULL, '2026-05-19 09:36:50'),
(71, 27, 16, 1, 13.39, NULL, NULL, '2026-05-19 09:36:50'),
(72, 27, 17, 1, 16.21, NULL, NULL, '2026-05-19 09:36:50'),
(73, 27, 18, 1, 12.89, NULL, NULL, '2026-05-19 09:36:50'),
(74, 27, 19, 1, 11.91, NULL, NULL, '2026-05-19 09:36:50'),
(75, 27, 20, 1, 13.65, NULL, NULL, '2026-05-19 09:36:50'),
(76, 27, 21, 1, 10.27, NULL, NULL, '2026-05-19 09:36:50'),
(77, 27, NULL, 1, 13.73, 5, 'Assez Bien', '2026-05-19 09:36:56'),
(78, 28, 12, 1, 11.99, NULL, NULL, '2026-05-19 09:36:50'),
(79, 28, 13, 1, 9.94, NULL, NULL, '2026-05-19 09:36:50'),
(80, 28, 14, 1, 11.34, NULL, NULL, '2026-05-19 09:36:50'),
(81, 28, 15, 1, 7.89, NULL, NULL, '2026-05-19 09:36:50'),
(82, 28, 16, 1, 11.42, NULL, NULL, '2026-05-19 09:36:50'),
(83, 28, 17, 1, 9.26, NULL, NULL, '2026-05-19 09:36:50'),
(84, 28, 18, 1, 11.22, NULL, NULL, '2026-05-19 09:36:50'),
(85, 28, 19, 1, 9.82, NULL, NULL, '2026-05-19 09:36:50'),
(86, 28, 20, 1, 10.83, NULL, NULL, '2026-05-19 09:36:50'),
(87, 28, 21, 1, 12.64, NULL, NULL, '2026-05-19 09:36:50'),
(88, 28, NULL, 1, 10.60, 10, 'Passable', '2026-05-19 09:36:56'),
(89, 29, 12, 1, 9.37, NULL, NULL, '2026-05-19 09:36:50'),
(90, 29, 13, 1, 8.92, NULL, NULL, '2026-05-19 09:36:51'),
(91, 29, 14, 1, 10.21, NULL, NULL, '2026-05-19 09:36:51'),
(92, 29, 15, 1, 6.87, NULL, NULL, '2026-05-19 09:36:51'),
(93, 29, 16, 1, 12.45, NULL, NULL, '2026-05-19 09:36:51'),
(94, 29, 17, 1, 10.39, NULL, NULL, '2026-05-19 09:36:51'),
(95, 29, 18, 1, 10.35, NULL, NULL, '2026-05-19 09:36:51'),
(96, 29, 19, 1, 9.63, NULL, NULL, '2026-05-19 09:36:51'),
(97, 29, 20, 1, 8.36, NULL, NULL, '2026-05-19 09:36:51'),
(98, 29, 21, 1, 11.62, NULL, NULL, '2026-05-19 09:36:51'),
(99, 29, NULL, 1, 9.52, 13, 'Insuffisant', '2026-05-19 09:36:56'),
(100, 30, 12, 1, 4.89, NULL, NULL, '2026-05-19 09:36:51'),
(101, 30, 13, 1, 3.81, NULL, NULL, '2026-05-19 09:36:51'),
(102, 30, 14, 1, 2.68, NULL, NULL, '2026-05-19 09:36:51'),
(103, 30, 15, 1, 5.21, NULL, NULL, '2026-05-19 09:36:51'),
(104, 30, 16, 1, 4.76, NULL, NULL, '2026-05-19 09:36:51'),
(105, 30, 17, 1, 7.34, NULL, NULL, '2026-05-19 09:36:51'),
(106, 30, 18, 1, 5.98, NULL, NULL, '2026-05-19 09:36:51'),
(107, 30, 19, 1, 5.33, NULL, NULL, '2026-05-19 09:36:51'),
(108, 30, 20, 1, 5.06, NULL, NULL, '2026-05-19 09:36:51'),
(109, 30, 21, 1, 8.21, NULL, NULL, '2026-05-19 09:36:51'),
(110, 30, NULL, 1, 4.79, 18, 'Insuffisant', '2026-05-19 09:36:56'),
(111, 31, 12, 1, 10.61, NULL, NULL, '2026-05-19 09:36:51'),
(112, 31, 13, 1, 9.82, NULL, NULL, '2026-05-19 09:36:51'),
(113, 31, 14, 1, 8.57, NULL, NULL, '2026-05-19 09:36:51'),
(114, 31, 15, 1, 12.50, NULL, NULL, '2026-05-19 09:36:51'),
(115, 31, 16, 1, 10.49, NULL, NULL, '2026-05-19 09:36:51'),
(116, 31, 17, 1, 9.86, NULL, NULL, '2026-05-19 09:36:51'),
(117, 31, 18, 1, 10.84, NULL, NULL, '2026-05-19 09:36:51'),
(118, 31, 19, 1, 12.03, NULL, NULL, '2026-05-19 09:36:52'),
(119, 31, 20, 1, 12.69, NULL, NULL, '2026-05-19 09:36:52'),
(120, 31, 21, 1, 9.25, NULL, NULL, '2026-05-19 09:36:52'),
(121, 31, NULL, 1, 10.63, 9, 'Passable', '2026-05-19 09:36:56'),
(122, 32, 12, 1, 8.39, NULL, NULL, '2026-05-19 09:36:52'),
(123, 32, 13, 1, 9.10, NULL, NULL, '2026-05-19 09:36:52'),
(124, 32, 14, 1, 10.26, NULL, NULL, '2026-05-19 09:36:52'),
(125, 32, 15, 1, 9.82, NULL, NULL, '2026-05-19 09:36:52'),
(126, 32, 16, 1, 10.71, NULL, NULL, '2026-05-19 09:36:52'),
(127, 32, 17, 1, 10.92, NULL, NULL, '2026-05-19 09:36:52'),
(128, 32, 18, 1, 10.56, NULL, NULL, '2026-05-19 09:36:52'),
(129, 32, 19, 1, 8.03, NULL, NULL, '2026-05-19 09:36:52'),
(130, 32, 20, 1, 10.56, NULL, NULL, '2026-05-19 09:36:52'),
(131, 32, 21, 1, 8.58, NULL, NULL, '2026-05-19 09:36:52'),
(132, 32, NULL, 1, 9.55, 12, 'Insuffisant', '2026-05-19 09:36:56'),
(133, 33, 12, 1, 15.42, NULL, NULL, '2026-05-19 09:36:52'),
(134, 33, 13, 1, 16.44, NULL, NULL, '2026-05-19 09:36:52'),
(135, 33, 14, 1, 13.57, NULL, NULL, '2026-05-19 09:36:52'),
(136, 33, 15, 1, 12.59, NULL, NULL, '2026-05-19 09:36:52'),
(137, 33, 16, 1, 15.17, NULL, NULL, '2026-05-19 09:36:52'),
(138, 33, 17, 1, 15.32, NULL, NULL, '2026-05-19 09:36:52'),
(139, 33, 18, 1, 16.10, NULL, NULL, '2026-05-19 09:36:52'),
(140, 33, 19, 1, 12.89, NULL, NULL, '2026-05-19 09:36:52'),
(141, 33, 20, 1, 15.74, NULL, NULL, '2026-05-19 09:36:52'),
(142, 33, 21, 1, 15.69, NULL, NULL, '2026-05-19 09:36:52'),
(143, 33, NULL, 1, 14.72, 1, 'Bien', '2026-05-19 09:36:56'),
(144, 34, 12, 1, 6.86, NULL, NULL, '2026-05-19 09:36:52'),
(145, 34, 13, 1, 9.05, NULL, NULL, '2026-05-19 09:36:52'),
(146, 34, 14, 1, 12.04, NULL, NULL, '2026-05-19 09:36:52'),
(147, 34, 15, 1, 7.88, NULL, NULL, '2026-05-19 09:36:53'),
(148, 34, 16, 1, 11.84, NULL, NULL, '2026-05-19 09:36:53'),
(149, 34, 17, 1, 9.09, NULL, NULL, '2026-05-19 09:36:53'),
(150, 34, 18, 1, 12.13, NULL, NULL, '2026-05-19 09:36:53'),
(151, 34, 19, 1, 9.85, NULL, NULL, '2026-05-19 09:36:53'),
(152, 34, 20, 1, 7.36, NULL, NULL, '2026-05-19 09:36:53'),
(153, 34, 21, 1, 9.55, NULL, NULL, '2026-05-19 09:36:53'),
(154, 34, NULL, 1, 9.32, 15, 'Insuffisant', '2026-05-19 09:36:56'),
(155, 35, 12, 1, 6.19, NULL, NULL, '2026-05-19 09:36:53'),
(156, 35, 13, 1, 5.89, NULL, NULL, '2026-05-19 09:36:53'),
(157, 35, 14, 1, 4.66, NULL, NULL, '2026-05-19 09:36:53'),
(158, 35, 15, 1, 2.38, NULL, NULL, '2026-05-19 09:36:53'),
(159, 35, 16, 1, 2.54, NULL, NULL, '2026-05-19 09:36:53'),
(160, 35, 17, 1, 5.39, NULL, NULL, '2026-05-19 09:36:53'),
(161, 35, 18, 1, 4.70, NULL, NULL, '2026-05-19 09:36:53'),
(162, 35, 19, 1, 3.14, NULL, NULL, '2026-05-19 09:36:53'),
(163, 35, 20, 1, 7.93, NULL, NULL, '2026-05-19 09:36:53'),
(164, 35, 21, 1, 2.01, NULL, NULL, '2026-05-19 09:36:53'),
(165, 35, NULL, 1, 4.63, 19, 'Insuffisant', '2026-05-19 09:36:57'),
(166, 36, 12, 1, 12.90, NULL, NULL, '2026-05-19 09:36:54'),
(167, 36, 13, 1, 11.44, NULL, NULL, '2026-05-19 09:36:54'),
(168, 36, 14, 1, 16.10, NULL, NULL, '2026-05-19 09:36:54'),
(169, 36, 15, 1, 16.60, NULL, NULL, '2026-05-19 09:36:54'),
(170, 36, 16, 1, 12.85, NULL, NULL, '2026-05-19 09:36:54'),
(171, 36, 17, 1, 12.36, NULL, NULL, '2026-05-19 09:36:54'),
(172, 36, 18, 1, 16.42, NULL, NULL, '2026-05-19 09:36:54'),
(173, 36, 19, 1, 14.30, NULL, NULL, '2026-05-19 09:36:54'),
(174, 36, 20, 1, 13.45, NULL, NULL, '2026-05-19 09:36:54'),
(175, 36, 21, 1, 13.45, NULL, NULL, '2026-05-19 09:36:54'),
(176, 36, NULL, 1, 14.10, 3, 'Bien', '2026-05-19 09:36:56'),
(177, 37, 12, 1, 9.32, NULL, NULL, '2026-05-19 09:36:54'),
(178, 37, 13, 1, 11.21, NULL, NULL, '2026-05-19 09:36:54'),
(179, 37, 14, 1, 11.57, NULL, NULL, '2026-05-19 09:36:54'),
(180, 37, 15, 1, 7.78, NULL, NULL, '2026-05-19 09:36:54'),
(181, 37, 16, 1, 9.66, NULL, NULL, '2026-05-19 09:36:54'),
(182, 37, 17, 1, 6.57, NULL, NULL, '2026-05-19 09:36:54'),
(183, 37, 18, 1, 8.17, NULL, NULL, '2026-05-19 09:36:54'),
(184, 37, 19, 1, 9.74, NULL, NULL, '2026-05-19 09:36:54'),
(185, 37, 20, 1, 6.64, NULL, NULL, '2026-05-19 09:36:54'),
(186, 37, 21, 1, 12.95, NULL, NULL, '2026-05-19 09:36:54'),
(187, 37, NULL, 1, 9.46, 14, 'Insuffisant', '2026-05-19 09:36:56'),
(188, 38, 12, 1, 10.93, NULL, NULL, '2026-05-19 09:36:54'),
(189, 38, 13, 1, 8.44, NULL, NULL, '2026-05-19 09:36:55'),
(190, 38, 14, 1, 7.54, NULL, NULL, '2026-05-19 09:36:55'),
(191, 38, 15, 1, 7.97, NULL, NULL, '2026-05-19 09:36:55'),
(192, 38, 16, 1, 6.77, NULL, NULL, '2026-05-19 09:36:55'),
(193, 38, 17, 1, 10.37, NULL, NULL, '2026-05-19 09:36:55'),
(194, 38, 18, 1, 7.38, NULL, NULL, '2026-05-19 09:36:55'),
(195, 38, 19, 1, 8.95, NULL, NULL, '2026-05-19 09:36:55'),
(196, 38, 20, 1, 6.92, NULL, NULL, '2026-05-19 09:36:55'),
(197, 38, 21, 1, 10.95, NULL, NULL, '2026-05-19 09:36:55'),
(198, 38, NULL, 1, 8.66, 16, 'Insuffisant', '2026-05-19 09:36:56'),
(199, 39, 12, 1, 13.56, NULL, NULL, '2026-05-19 09:36:55'),
(200, 39, 13, 1, 12.48, NULL, NULL, '2026-05-19 09:36:55'),
(201, 39, 14, 1, 14.76, NULL, NULL, '2026-05-19 09:36:55'),
(202, 39, 15, 1, 15.20, NULL, NULL, '2026-05-19 09:36:55'),
(203, 39, 16, 1, 13.50, NULL, NULL, '2026-05-19 09:36:55'),
(204, 39, 17, 1, 14.99, NULL, NULL, '2026-05-19 09:36:55'),
(205, 39, 18, 1, 13.05, NULL, NULL, '2026-05-19 09:36:55'),
(206, 39, 19, 1, 13.80, NULL, NULL, '2026-05-19 09:36:55'),
(207, 39, 20, 1, 14.54, NULL, NULL, '2026-05-19 09:36:55'),
(208, 39, 21, 1, 16.29, NULL, NULL, '2026-05-19 09:36:55'),
(209, 39, NULL, 1, 14.00, 4, 'Bien', '2026-05-19 09:36:56'),
(210, 40, 12, 1, 5.58, NULL, NULL, '2026-05-19 09:36:55'),
(211, 40, 13, 1, 1.70, NULL, NULL, '2026-05-19 09:36:55'),
(212, 40, 14, 1, 3.68, NULL, NULL, '2026-05-19 09:36:55'),
(213, 40, 15, 1, 4.74, NULL, NULL, '2026-05-19 09:36:55'),
(214, 40, 16, 1, 5.89, NULL, NULL, '2026-05-19 09:36:55'),
(215, 40, 17, 1, 3.73, NULL, NULL, '2026-05-19 09:36:55'),
(216, 40, 18, 1, 5.69, NULL, NULL, '2026-05-19 09:36:56'),
(217, 40, 19, 1, 3.07, NULL, NULL, '2026-05-19 09:36:56'),
(218, 40, 20, 1, 4.36, NULL, NULL, '2026-05-19 09:36:56'),
(219, 40, 21, 1, 5.60, NULL, NULL, '2026-05-19 09:36:56'),
(220, 40, NULL, 1, 4.41, 20, 'Insuffisant', '2026-05-19 09:36:57');

-- --------------------------------------------------------

--
-- Structure de la table `niveaux`
--

DROP TABLE IF EXISTS `niveaux`;
CREATE TABLE IF NOT EXISTS `niveaux` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cycle_id` int UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abreviation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordre` tinyint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cycle_id` (`cycle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `niveaux`
--

INSERT INTO `niveaux` (`id`, `cycle_id`, `nom`, `abreviation`, `ordre`) VALUES
(1, 1, 'Seconde', 'Sec', 1),
(2, 1, 'Première', 'Pre', 2),
(3, 1, 'Terminale', 'Ter', 3);

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `evaluation_id` int UNSIGNED NOT NULL,
  `eleve_id` int UNSIGNED NOT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `statut` enum('present','absent','dispense') COLLATE utf8mb4_unicode_ci DEFAULT 'present',
  `observation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saisie_par` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_note` (`evaluation_id`,`eleve_id`),
  KEY `eleve_id` (`eleve_id`),
  KEY `saisie_par` (`saisie_par`)
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id`, `evaluation_id`, `eleve_id`, `note`, `statut`, `observation`, `saisie_par`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10.30, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(2, 2, 1, 6.08, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(3, 3, 1, 12.78, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(4, 1, 2, 8.88, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(5, 2, 2, 10.77, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(6, 3, 2, 5.37, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(7, 1, 3, 11.61, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(8, 2, 3, 9.30, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(9, 3, 3, 14.07, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(10, 1, 4, 5.81, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(11, 2, 4, 7.86, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(12, 3, 4, 10.40, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(13, 1, 5, 9.31, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(14, 2, 5, 0.64, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(15, 3, 5, 0.47, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(16, 1, 6, 10.76, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(17, 2, 6, 14.81, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(18, 3, 6, 18.78, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(19, 1, 7, 14.16, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(20, 2, 7, 14.33, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(21, 3, 7, 11.93, 'present', NULL, 6, '2026-05-18 20:57:38', NULL),
(22, 1, 8, 8.45, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(23, 2, 8, 5.80, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(24, 3, 8, 5.77, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(25, 1, 9, 17.55, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(26, 2, 9, 14.03, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(27, 3, 9, 15.71, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(28, 1, 10, 7.82, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(29, 2, 10, 4.41, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(30, 3, 10, 1.35, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(31, 1, 11, 10.88, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(32, 2, 11, 13.91, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(33, 3, 11, 10.20, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(34, 1, 12, 17.03, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(35, 2, 12, 16.34, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(36, 3, 12, 13.02, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(37, 1, 13, 7.16, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(38, 2, 13, 10.33, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(39, 3, 13, 10.98, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(40, 1, 14, 10.84, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(41, 2, 14, 9.87, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(42, 3, 14, 12.29, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(43, 1, 15, 7.33, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(44, 2, 15, 4.71, 'present', NULL, 6, '2026-05-18 20:57:39', NULL),
(45, 3, 15, 5.72, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(46, 1, 16, 8.51, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(47, 2, 16, 10.72, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(48, 3, 16, 6.80, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(49, 1, 17, 7.23, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(50, 2, 17, 9.44, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(51, 3, 17, 5.07, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(52, 1, 18, 12.88, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(53, 2, 18, 14.71, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(54, 3, 18, 15.40, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(55, 1, 19, NULL, 'absent', NULL, 6, '2026-05-18 20:57:40', NULL),
(56, 2, 19, 12.58, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(57, 3, 19, 10.88, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(58, 1, 20, 5.55, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(59, 2, 20, 6.25, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(60, 3, 20, 2.82, 'present', NULL, 6, '2026-05-18 20:57:40', NULL),
(61, 4, 1, 13.22, 'present', NULL, 7, '2026-05-18 20:57:40', NULL),
(62, 5, 1, 6.32, 'present', NULL, 7, '2026-05-18 20:57:40', NULL),
(63, 6, 1, 7.53, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(64, 4, 2, 7.73, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(65, 5, 2, 13.62, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(66, 6, 2, 11.41, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(67, 4, 3, 14.44, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(68, 5, 3, 10.13, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(69, 6, 3, 13.92, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(70, 4, 4, 11.49, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(71, 5, 4, 10.55, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(72, 6, 4, 12.37, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(73, 4, 5, 3.45, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(74, 5, 5, 9.75, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(75, 6, 5, 1.44, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(76, 4, 6, 9.03, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(77, 5, 6, 11.58, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(78, 6, 6, 16.94, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(79, 4, 7, 5.30, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(80, 5, 7, 12.00, 'present', NULL, 7, '2026-05-18 20:57:41', NULL),
(81, 6, 7, 6.33, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(82, 4, 8, 14.59, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(83, 5, 8, 14.14, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(84, 6, 8, 8.14, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(85, 4, 9, 15.73, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(86, 5, 9, 14.42, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(87, 6, 9, 14.17, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(88, 4, 10, 7.11, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(89, 5, 10, 5.91, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(90, 6, 10, 2.94, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(91, 4, 11, 9.12, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(92, 5, 11, NULL, 'absent', NULL, 7, '2026-05-18 20:57:42', NULL),
(93, 6, 11, NULL, 'absent', NULL, 7, '2026-05-18 20:57:42', NULL),
(94, 4, 12, 10.55, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(95, 5, 12, 12.22, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(96, 6, 12, 15.74, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(97, 4, 13, 10.53, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(98, 5, 13, 14.58, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(99, 6, 13, 14.96, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(100, 4, 14, 9.27, 'present', NULL, 7, '2026-05-18 20:57:42', NULL),
(101, 5, 14, 7.16, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(102, 6, 14, 5.87, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(103, 4, 15, 8.72, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(104, 5, 15, 7.98, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(105, 6, 15, 5.21, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(106, 4, 16, 13.31, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(107, 5, 16, 5.71, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(108, 6, 16, 10.91, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(109, 4, 17, 6.32, 'present', NULL, 7, '2026-05-18 20:57:43', NULL),
(110, 5, 17, 6.46, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(111, 6, 17, 7.34, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(112, 4, 18, NULL, 'absent', NULL, 7, '2026-05-18 20:57:44', NULL),
(113, 5, 18, 18.32, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(114, 6, 18, 16.99, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(115, 4, 19, 10.93, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(116, 5, 19, 12.58, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(117, 6, 19, NULL, 'absent', NULL, 7, '2026-05-18 20:57:44', NULL),
(118, 4, 20, 7.24, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(119, 5, 20, NULL, 'absent', NULL, 7, '2026-05-18 20:57:44', NULL),
(120, 6, 20, 4.42, 'present', NULL, 7, '2026-05-18 20:57:44', NULL),
(121, 7, 1, 10.59, 'present', NULL, 8, '2026-05-18 20:57:44', NULL),
(122, 8, 1, 6.36, 'present', NULL, 8, '2026-05-18 20:57:44', NULL),
(123, 9, 1, 10.03, 'present', NULL, 8, '2026-05-18 20:57:44', NULL),
(124, 7, 2, 12.11, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(125, 8, 2, 12.16, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(126, 9, 2, 7.96, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(127, 7, 3, 11.05, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(128, 8, 3, 17.17, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(129, 9, 3, 18.79, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(130, 7, 4, 13.61, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(131, 8, 4, 13.55, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(132, 9, 4, 11.56, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(133, 7, 5, 0.51, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(134, 8, 5, NULL, 'absent', NULL, 8, '2026-05-18 20:57:45', NULL),
(135, 9, 5, 9.38, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(136, 7, 6, NULL, 'absent', NULL, 8, '2026-05-18 20:57:45', NULL),
(137, 8, 6, 14.06, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(138, 9, 6, 18.61, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(139, 7, 7, 13.40, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(140, 8, 7, 14.55, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(141, 9, 7, 11.11, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(142, 7, 8, 14.41, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(143, 8, 8, 11.73, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(144, 9, 8, 6.37, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(145, 7, 9, 14.24, 'present', NULL, 8, '2026-05-18 20:57:45', NULL),
(146, 8, 9, NULL, 'absent', NULL, 8, '2026-05-18 20:57:46', NULL),
(147, 9, 9, 11.94, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(148, 7, 10, 8.56, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(149, 8, 10, 7.43, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(150, 9, 10, 9.90, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(151, 7, 11, NULL, 'absent', NULL, 8, '2026-05-18 20:57:46', NULL),
(152, 8, 11, 8.99, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(153, 9, 11, 10.48, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(154, 7, 12, 9.28, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(155, 8, 12, 10.43, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(156, 9, 12, 13.99, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(157, 7, 13, 14.39, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(158, 8, 13, 13.61, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(159, 9, 13, 9.05, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(160, 7, 14, NULL, 'absent', NULL, 8, '2026-05-18 20:57:46', NULL),
(161, 8, 14, 6.48, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(162, 9, 14, 5.52, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(163, 7, 15, 0.12, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(164, 8, 15, 0.82, 'present', NULL, 8, '2026-05-18 20:57:46', NULL),
(165, 9, 15, 2.06, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(166, 7, 16, 6.12, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(167, 8, 16, 5.62, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(168, 9, 16, NULL, 'absent', NULL, 8, '2026-05-18 20:57:47', NULL),
(169, 7, 17, 11.15, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(170, 8, 17, 11.97, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(171, 9, 17, 14.61, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(172, 7, 18, 10.72, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(173, 8, 18, 18.85, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(174, 9, 18, 16.97, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(175, 7, 19, 11.36, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(176, 8, 19, 9.41, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(177, 9, 19, 12.22, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(178, 7, 20, 5.86, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(179, 8, 20, 5.63, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(180, 9, 20, 8.12, 'present', NULL, 8, '2026-05-18 20:57:47', NULL),
(181, 10, 1, 6.20, 'present', NULL, 9, '2026-05-18 20:57:47', NULL),
(182, 11, 1, 7.49, 'present', NULL, 9, '2026-05-18 20:57:47', NULL),
(183, 12, 1, 8.25, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(184, 10, 2, 7.18, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(185, 11, 2, 9.30, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(186, 12, 2, 5.04, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(187, 10, 3, 11.69, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(188, 11, 3, 12.47, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(189, 12, 3, 11.31, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(190, 10, 4, 12.17, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(191, 11, 4, 9.21, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(192, 12, 4, 14.00, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(193, 10, 5, 5.27, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(194, 11, 5, 8.55, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(195, 12, 5, 0.66, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(196, 10, 6, 14.75, 'present', NULL, 9, '2026-05-18 20:57:48', NULL),
(197, 11, 6, NULL, 'absent', NULL, 9, '2026-05-18 20:57:49', NULL),
(198, 12, 6, 18.93, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(199, 10, 7, 10.34, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(200, 11, 7, 5.46, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(201, 12, 7, 8.04, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(202, 10, 8, 11.34, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(203, 11, 8, 6.44, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(204, 12, 8, 8.95, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(205, 10, 9, 14.42, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(206, 11, 9, 10.36, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(207, 12, 9, 14.85, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(208, 10, 10, 5.41, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(209, 11, 10, 0.54, 'present', NULL, 9, '2026-05-18 20:57:49', NULL),
(210, 12, 10, 6.14, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(211, 10, 11, 5.35, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(212, 11, 11, NULL, 'absent', NULL, 9, '2026-05-18 20:57:50', NULL),
(213, 12, 11, 14.46, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(214, 10, 12, 15.59, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(215, 11, 12, 13.28, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(216, 12, 12, 16.04, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(217, 10, 13, 7.39, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(218, 11, 13, 7.90, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(219, 12, 13, 6.26, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(220, 10, 14, NULL, 'absent', NULL, 9, '2026-05-18 20:57:50', NULL),
(221, 11, 14, NULL, 'absent', NULL, 9, '2026-05-18 20:57:50', NULL),
(222, 12, 14, 10.92, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(223, 10, 15, 6.76, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(224, 11, 15, 7.35, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(225, 12, 15, 4.99, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(226, 10, 16, 7.79, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(227, 11, 16, 7.12, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(228, 12, 16, 11.16, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(229, 10, 17, 10.70, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(230, 11, 17, 6.01, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(231, 12, 17, 7.34, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(232, 10, 18, 9.03, 'present', NULL, 9, '2026-05-18 20:57:50', NULL),
(233, 11, 18, 9.59, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(234, 12, 18, 16.55, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(235, 10, 19, 10.47, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(236, 11, 19, 5.16, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(237, 12, 19, 5.05, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(238, 10, 20, 5.51, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(239, 11, 20, 7.63, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(240, 12, 20, 5.80, 'present', NULL, 9, '2026-05-18 20:57:51', NULL),
(241, 13, 1, 10.12, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(242, 14, 1, 10.19, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(243, 15, 1, 7.20, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(244, 13, 2, 5.72, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(245, 14, 2, 9.85, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(246, 15, 2, 6.65, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(247, 13, 3, 18.37, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(248, 14, 3, NULL, 'absent', NULL, 10, '2026-05-18 20:57:51', NULL),
(249, 15, 3, 14.37, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(250, 13, 4, 14.95, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(251, 14, 4, 9.31, 'present', NULL, 10, '2026-05-18 20:57:51', NULL),
(252, 15, 4, 9.24, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(253, 13, 5, 8.59, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(254, 14, 5, 4.11, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(255, 15, 5, 8.49, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(256, 13, 6, 15.95, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(257, 14, 6, 10.79, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(258, 15, 6, 15.50, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(259, 13, 7, 13.80, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(260, 14, 7, 11.16, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(261, 15, 7, 8.52, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(262, 13, 8, 9.89, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(263, 14, 8, 10.28, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(264, 15, 8, 6.77, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(265, 13, 9, 13.02, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(266, 14, 9, 16.95, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(267, 15, 9, 13.92, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(268, 13, 10, 4.24, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(269, 14, 10, 2.83, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(270, 15, 10, 1.54, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(271, 13, 11, 6.85, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(272, 14, 11, 8.60, 'present', NULL, 10, '2026-05-18 20:57:52', NULL),
(273, 15, 11, 9.79, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(274, 13, 12, 9.51, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(275, 14, 12, 15.57, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(276, 15, 12, 11.96, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(277, 13, 13, 11.05, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(278, 14, 13, 5.10, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(279, 15, 13, 8.01, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(280, 13, 14, 12.60, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(281, 14, 14, 13.11, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(282, 15, 14, 6.07, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(283, 13, 15, 6.43, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(284, 14, 15, 6.92, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(285, 15, 15, 7.19, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(286, 13, 16, NULL, 'absent', NULL, 10, '2026-05-18 20:57:53', NULL),
(287, 14, 16, 11.24, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(288, 15, 16, 9.68, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(289, 13, 17, NULL, 'absent', NULL, 10, '2026-05-18 20:57:53', NULL),
(290, 14, 17, 8.25, 'present', NULL, 10, '2026-05-18 20:57:53', NULL),
(291, 15, 17, NULL, 'absent', NULL, 10, '2026-05-18 20:57:53', NULL),
(292, 13, 18, 12.49, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(293, 14, 18, NULL, 'absent', NULL, 10, '2026-05-18 20:57:54', NULL),
(294, 15, 18, 18.89, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(295, 13, 19, 9.46, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(296, 14, 19, 11.64, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(297, 15, 19, 6.83, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(298, 13, 20, 8.22, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(299, 14, 20, 4.46, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(300, 15, 20, 9.48, 'present', NULL, 10, '2026-05-18 20:57:54', NULL),
(301, 16, 1, 7.91, 'present', NULL, 3, '2026-05-18 20:57:54', NULL),
(302, 17, 1, 10.53, 'present', NULL, 3, '2026-05-18 20:57:54', NULL),
(303, 18, 1, 11.84, 'present', NULL, 3, '2026-05-18 20:57:54', NULL),
(304, 16, 2, 8.70, 'present', NULL, 3, '2026-05-18 20:57:54', NULL),
(305, 17, 2, 5.78, 'present', NULL, 3, '2026-05-18 20:57:54', NULL),
(306, 18, 2, 9.24, 'present', NULL, 3, '2026-05-18 20:57:54', NULL),
(307, 16, 3, 13.19, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(308, 17, 3, 9.49, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(309, 18, 3, 15.38, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(310, 16, 4, 14.88, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(311, 17, 4, 11.36, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(312, 18, 4, 13.01, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(313, 16, 5, 9.92, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(314, 17, 5, 1.21, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(315, 18, 5, 5.81, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(316, 16, 6, 9.10, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(317, 17, 6, 10.55, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(318, 18, 6, 9.07, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(319, 16, 7, 13.30, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(320, 17, 7, 7.89, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(321, 18, 7, 13.71, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(322, 16, 8, 12.48, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(323, 17, 8, 5.32, 'present', NULL, 3, '2026-05-18 20:57:55', NULL),
(324, 18, 8, 12.67, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(325, 16, 9, 11.44, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(326, 17, 9, 12.09, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(327, 18, 9, 11.13, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(328, 16, 10, NULL, 'absent', NULL, 3, '2026-05-18 20:57:56', NULL),
(329, 17, 10, 2.17, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(330, 18, 10, 4.40, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(331, 16, 11, 7.59, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(332, 17, 11, NULL, 'absent', NULL, 3, '2026-05-18 20:57:56', NULL),
(333, 18, 11, 6.11, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(334, 16, 12, 14.97, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(335, 17, 12, 16.04, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(336, 18, 12, 12.28, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(337, 16, 13, 5.15, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(338, 17, 13, 13.80, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(339, 18, 13, 6.50, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(340, 16, 14, NULL, 'absent', NULL, 3, '2026-05-18 20:57:56', NULL),
(341, 17, 14, 6.18, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(342, 18, 14, 8.67, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(343, 16, 15, NULL, 'absent', NULL, 3, '2026-05-18 20:57:56', NULL),
(344, 17, 15, 8.58, 'present', NULL, 3, '2026-05-18 20:57:56', NULL),
(345, 18, 15, 1.86, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(346, 16, 16, 9.04, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(347, 17, 16, 5.29, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(348, 18, 16, NULL, 'absent', NULL, 3, '2026-05-18 20:57:57', NULL),
(349, 16, 17, 8.60, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(350, 17, 17, 10.69, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(351, 18, 17, 10.11, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(352, 16, 18, 10.11, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(353, 17, 18, 13.60, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(354, 18, 18, 11.33, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(355, 16, 19, 7.16, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(356, 17, 19, 9.51, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(357, 18, 19, 14.27, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(358, 16, 20, 8.75, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(359, 17, 20, 8.95, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(360, 18, 20, 4.15, 'present', NULL, 3, '2026-05-18 20:57:57', NULL),
(361, 19, 1, 5.64, 'present', NULL, 11, '2026-05-18 20:57:57', NULL),
(362, 20, 1, 6.72, 'present', NULL, 11, '2026-05-18 20:57:57', NULL),
(363, 21, 1, 7.74, 'present', NULL, 11, '2026-05-18 20:57:57', NULL),
(364, 19, 2, 8.73, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(365, 20, 2, 8.18, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(366, 21, 2, 11.02, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(367, 19, 3, 15.70, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(368, 20, 3, 18.86, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(369, 21, 3, NULL, 'absent', NULL, 11, '2026-05-18 20:57:58', NULL),
(370, 19, 4, 14.45, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(371, 20, 4, 10.95, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(372, 21, 4, 6.02, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(373, 19, 5, 9.34, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(374, 20, 5, 7.64, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(375, 21, 5, 7.09, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(376, 19, 6, 11.94, 'present', NULL, 11, '2026-05-18 20:57:58', NULL),
(377, 20, 6, 12.29, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(378, 21, 6, 11.42, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(379, 19, 7, 13.80, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(380, 20, 7, 12.12, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(381, 21, 7, 13.80, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(382, 19, 8, 6.84, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(383, 20, 8, 8.51, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(384, 21, 8, 10.04, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(385, 19, 9, 11.24, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(386, 20, 9, 16.98, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(387, 21, 9, NULL, 'absent', NULL, 11, '2026-05-18 20:57:59', NULL),
(388, 19, 10, 8.58, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(389, 20, 10, 4.43, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(390, 21, 10, 3.49, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(391, 19, 11, 10.32, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(392, 20, 11, NULL, 'absent', NULL, 11, '2026-05-18 20:57:59', NULL),
(393, 21, 11, 9.31, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(394, 19, 12, 10.15, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(395, 20, 12, 13.96, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(396, 21, 12, 16.62, 'present', NULL, 11, '2026-05-18 20:57:59', NULL),
(397, 19, 13, 14.08, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(398, 20, 13, 9.92, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(399, 21, 13, 7.37, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(400, 19, 14, 8.38, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(401, 20, 14, 10.20, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(402, 21, 14, 8.84, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(403, 19, 15, 0.61, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(404, 20, 15, 1.08, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(405, 21, 15, 2.97, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(406, 19, 16, 14.00, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(407, 20, 16, 8.73, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(408, 21, 16, 6.22, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(409, 19, 17, 12.80, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(410, 20, 17, 5.72, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(411, 21, 17, 14.15, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(412, 19, 18, 12.07, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(413, 20, 18, 12.28, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(414, 21, 18, 14.25, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(415, 19, 19, 10.14, 'present', NULL, 11, '2026-05-18 20:58:00', NULL),
(416, 20, 19, 8.33, 'present', NULL, 11, '2026-05-18 20:58:01', NULL),
(417, 21, 19, 8.80, 'present', NULL, 11, '2026-05-18 20:58:01', NULL),
(418, 19, 20, 2.50, 'present', NULL, 11, '2026-05-18 20:58:01', NULL),
(419, 20, 20, 7.13, 'present', NULL, 11, '2026-05-18 20:58:01', NULL),
(420, 21, 20, 7.54, 'present', NULL, 11, '2026-05-18 20:58:01', NULL),
(421, 22, 1, 5.46, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(422, 23, 1, 8.89, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(423, 24, 1, 8.27, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(424, 22, 2, 10.69, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(425, 23, 2, 11.49, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(426, 24, 2, 13.62, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(427, 22, 3, 10.60, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(428, 23, 3, 10.91, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(429, 24, 3, 17.36, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(430, 22, 4, 5.38, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(431, 23, 4, 5.80, 'present', NULL, 12, '2026-05-18 20:58:01', NULL),
(432, 24, 4, 9.03, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(433, 22, 5, 8.11, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(434, 23, 5, 6.72, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(435, 24, 5, 0.19, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(436, 22, 6, 15.98, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(437, 23, 6, 14.42, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(438, 24, 6, 14.66, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(439, 22, 7, 6.11, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(440, 23, 7, 13.87, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(441, 24, 7, 12.01, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(442, 22, 8, 6.52, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(443, 23, 8, 7.69, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(444, 24, 8, 8.96, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(445, 22, 9, 18.26, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(446, 23, 9, 15.67, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(447, 24, 9, 15.30, 'present', NULL, 12, '2026-05-18 20:58:02', NULL),
(448, 22, 10, 7.57, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(449, 23, 10, 3.03, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(450, 24, 10, 1.71, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(451, 22, 11, 7.81, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(452, 23, 11, 7.94, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(453, 24, 11, 6.41, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(454, 22, 12, 17.60, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(455, 23, 12, 16.10, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(456, 24, 12, NULL, 'absent', NULL, 12, '2026-05-18 20:58:03', NULL),
(457, 22, 13, 14.59, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(458, 23, 13, 5.36, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(459, 24, 13, 11.87, 'present', NULL, 12, '2026-05-18 20:58:03', NULL),
(460, 22, 14, 13.34, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(461, 23, 14, 8.62, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(462, 24, 14, 10.06, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(463, 22, 15, 1.70, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(464, 23, 15, 0.15, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(465, 24, 15, 4.39, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(466, 22, 16, 13.98, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(467, 23, 16, 14.39, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(468, 24, 16, 10.77, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(469, 22, 17, 12.77, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(470, 23, 17, 9.18, 'present', NULL, 12, '2026-05-18 20:58:04', NULL),
(471, 24, 17, 10.05, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(472, 22, 18, 10.61, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(473, 23, 18, 12.94, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(474, 24, 18, 9.61, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(475, 22, 19, 9.79, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(476, 23, 19, 8.91, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(477, 24, 19, 14.64, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(478, 22, 20, 1.65, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(479, 23, 20, 1.95, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(480, 24, 20, 7.67, 'present', NULL, 12, '2026-05-18 20:58:05', NULL),
(481, 25, 1, 13.82, 'present', NULL, 14, '2026-05-18 20:58:05', NULL),
(482, 26, 1, 6.15, 'present', NULL, 14, '2026-05-18 20:58:05', NULL),
(483, 27, 1, 12.04, 'present', NULL, 14, '2026-05-18 20:58:05', NULL),
(484, 25, 2, 6.27, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(485, 26, 2, 11.42, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(486, 27, 2, 8.60, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(487, 25, 3, 11.35, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(488, 26, 3, 16.67, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(489, 27, 3, 12.06, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(490, 25, 4, 10.25, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(491, 26, 4, 6.84, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(492, 27, 4, 13.23, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(493, 25, 5, 6.21, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(494, 26, 5, 5.55, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(495, 27, 5, 1.87, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(496, 25, 6, 13.31, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(497, 26, 6, 14.87, 'present', NULL, 14, '2026-05-18 20:58:06', NULL),
(498, 27, 6, NULL, 'absent', NULL, 14, '2026-05-18 20:58:07', NULL),
(499, 25, 7, 8.37, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(500, 26, 7, 14.48, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(501, 27, 7, 7.09, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(502, 25, 8, 5.21, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(503, 26, 8, 7.41, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(504, 27, 8, 13.68, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(505, 25, 9, 13.56, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(506, 26, 9, 12.11, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(507, 27, 9, 9.66, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(508, 25, 10, 4.06, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(509, 26, 10, 6.38, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(510, 27, 10, 7.78, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(511, 25, 11, 10.24, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(512, 26, 11, NULL, 'absent', NULL, 14, '2026-05-18 20:58:07', NULL),
(513, 27, 11, 6.34, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(514, 25, 12, 14.72, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(515, 26, 12, 12.62, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(516, 27, 12, 18.19, 'present', NULL, 14, '2026-05-18 20:58:07', NULL),
(517, 25, 13, 11.47, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(518, 26, 13, 6.41, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(519, 27, 13, 8.37, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(520, 25, 14, 14.97, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(521, 26, 14, 8.29, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(522, 27, 14, 13.75, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(523, 25, 15, 9.94, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(524, 26, 15, 1.90, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(525, 27, 15, 9.49, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(526, 25, 16, 10.60, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(527, 26, 16, 12.27, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(528, 27, 16, NULL, 'absent', NULL, 14, '2026-05-18 20:58:08', NULL),
(529, 25, 17, 5.56, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(530, 26, 17, 12.51, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(531, 27, 17, 7.77, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(532, 25, 18, 18.85, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(533, 26, 18, 9.83, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(534, 27, 18, 15.46, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(535, 25, 19, 9.50, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(536, 26, 19, 12.37, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(537, 27, 19, 13.65, 'present', NULL, 14, '2026-05-18 20:58:08', NULL),
(538, 25, 20, 0.41, 'present', NULL, 14, '2026-05-18 20:58:09', NULL),
(539, 26, 20, 8.67, 'present', NULL, 14, '2026-05-18 20:58:09', NULL),
(540, 27, 20, 5.84, 'present', NULL, 14, '2026-05-18 20:58:09', NULL),
(541, 28, 1, 9.31, 'present', NULL, 15, '2026-05-18 20:58:09', NULL),
(542, 29, 1, 5.66, 'present', NULL, 15, '2026-05-18 20:58:09', NULL),
(543, 30, 1, 15.00, 'present', NULL, 15, '2026-05-18 20:58:09', NULL),
(544, 28, 2, 9.65, 'present', NULL, 15, '2026-05-18 20:58:09', NULL),
(545, 29, 2, 8.32, 'present', NULL, 15, '2026-05-18 20:58:09', NULL),
(546, 30, 2, 6.33, 'present', NULL, 15, '2026-05-18 20:58:09', NULL),
(547, 28, 3, 12.05, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(548, 29, 3, 9.25, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(549, 30, 3, 14.91, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(550, 28, 4, 13.82, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(551, 29, 4, 5.82, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(552, 30, 4, 6.25, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(553, 28, 5, 6.25, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(554, 29, 5, 0.56, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(555, 30, 5, 4.84, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(556, 28, 6, 18.29, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(557, 29, 6, 11.22, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(558, 30, 6, 17.58, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(559, 28, 7, 9.22, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(560, 29, 7, 10.33, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(561, 30, 7, NULL, 'absent', NULL, 15, '2026-05-18 20:58:10', NULL),
(562, 28, 8, 8.37, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(563, 29, 8, 10.94, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(564, 30, 8, 7.48, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(565, 28, 9, 13.46, 'present', NULL, 15, '2026-05-18 20:58:10', NULL),
(566, 29, 9, NULL, 'absent', NULL, 15, '2026-05-18 20:58:10', NULL),
(567, 30, 9, NULL, 'absent', NULL, 15, '2026-05-18 20:58:11', NULL),
(568, 28, 10, 1.58, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(569, 29, 10, 6.43, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(570, 30, 10, 9.80, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(571, 28, 11, 7.78, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(572, 29, 11, 8.23, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(573, 30, 11, 14.83, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(574, 28, 12, 13.15, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(575, 29, 12, 9.37, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(576, 30, 12, 9.11, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(577, 28, 13, 10.74, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(578, 29, 13, 14.73, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(579, 30, 13, 7.33, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(580, 28, 14, 8.80, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(581, 29, 14, 7.63, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(582, 30, 14, 9.40, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(583, 28, 15, 9.40, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(584, 29, 15, 1.28, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(585, 30, 15, 1.93, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(586, 28, 16, 12.33, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(587, 29, 16, 5.69, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(588, 30, 16, 11.25, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(589, 28, 17, 7.67, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(590, 29, 17, 13.43, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(591, 30, 17, 8.07, 'present', NULL, 15, '2026-05-18 20:58:11', NULL),
(592, 28, 18, 16.21, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(593, 29, 18, 18.84, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(594, 30, 18, 10.71, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(595, 28, 19, 13.64, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(596, 29, 19, 8.76, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(597, 30, 19, 6.95, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(598, 28, 20, 9.66, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(599, 29, 20, 9.04, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(600, 30, 20, 6.04, 'present', NULL, 15, '2026-05-18 20:58:12', NULL),
(601, 31, 1, 10.73, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(602, 32, 1, 11.05, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(603, 33, 1, 14.53, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(604, 31, 2, 10.79, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(605, 32, 2, 5.21, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(606, 33, 2, 9.43, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(607, 31, 3, 15.29, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(608, 32, 3, 18.84, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(609, 33, 3, 16.54, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(610, 31, 4, 6.07, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(611, 32, 4, 5.95, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(612, 33, 4, 13.21, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(613, 31, 5, 1.17, 'present', NULL, 3, '2026-05-18 20:58:12', NULL),
(614, 32, 5, 2.68, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(615, 33, 5, 9.54, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(616, 31, 6, 14.67, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(617, 32, 6, 12.47, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(618, 33, 6, 14.98, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(619, 31, 7, 6.00, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(620, 32, 7, 12.06, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(621, 33, 7, 9.30, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(622, 31, 8, 11.43, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(623, 32, 8, 5.18, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(624, 33, 8, 7.66, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(625, 31, 9, 10.85, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(626, 32, 9, NULL, 'absent', NULL, 3, '2026-05-18 20:58:13', NULL),
(627, 33, 9, 14.37, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(628, 31, 10, 5.29, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(629, 32, 10, 7.01, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(630, 33, 10, 4.12, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(631, 31, 11, 10.54, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(632, 32, 11, 12.29, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(633, 33, 11, 9.61, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(634, 31, 12, 12.76, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(635, 32, 12, 18.63, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(636, 33, 12, 9.18, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(637, 31, 13, 13.46, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(638, 32, 13, 13.31, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(639, 33, 13, 8.20, 'present', NULL, 3, '2026-05-18 20:58:13', NULL),
(640, 31, 14, 9.29, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(641, 32, 14, 9.89, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(642, 33, 14, 8.14, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(643, 31, 15, 0.15, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(644, 32, 15, 0.48, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(645, 33, 15, 2.06, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(646, 31, 16, 14.09, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(647, 32, 16, 9.00, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(648, 33, 16, 7.14, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(649, 31, 17, 5.36, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(650, 32, 17, 8.82, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(651, 33, 17, 6.72, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(652, 31, 18, 10.72, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(653, 32, 18, NULL, 'absent', NULL, 3, '2026-05-18 20:58:14', NULL),
(654, 33, 18, 18.54, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(655, 31, 19, 6.35, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(656, 32, 19, 12.17, 'present', NULL, 3, '2026-05-18 20:58:14', NULL),
(657, 33, 19, 7.79, 'present', NULL, 3, '2026-05-18 20:58:15', NULL),
(658, 31, 20, 5.43, 'present', NULL, 3, '2026-05-18 20:58:15', NULL),
(659, 32, 20, 2.92, 'present', NULL, 3, '2026-05-18 20:58:15', NULL),
(660, 33, 20, 2.93, 'present', NULL, 3, '2026-05-18 20:58:15', NULL),
(661, 34, 21, 15.10, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(662, 35, 21, 14.32, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(663, 36, 21, 17.18, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(664, 34, 22, 13.14, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(665, 35, 22, 8.71, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(666, 36, 22, 12.66, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(667, 34, 23, 10.87, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(668, 35, 23, 9.60, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(669, 36, 23, 6.90, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(670, 34, 24, 9.58, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(671, 35, 24, 11.94, 'present', NULL, 6, '2026-05-18 20:58:15', NULL),
(672, 36, 24, 9.53, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(673, 34, 25, 6.82, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(674, 35, 25, 7.87, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(675, 36, 25, 8.86, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(676, 34, 26, 9.06, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(677, 35, 26, 13.28, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(678, 36, 26, 14.07, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(679, 34, 27, 10.38, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(680, 35, 27, 9.22, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(681, 36, 27, 15.45, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(682, 34, 28, 13.13, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(683, 35, 28, 10.84, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(684, 36, 28, NULL, 'absent', NULL, 6, '2026-05-18 20:58:16', NULL),
(685, 34, 29, 8.00, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(686, 35, 29, 11.03, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(687, 36, 29, 9.08, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(688, 34, 30, 4.87, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(689, 35, 30, 5.53, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(690, 36, 30, 4.26, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(691, 34, 31, 11.60, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(692, 35, 31, 12.16, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(693, 36, 31, 8.06, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(694, 34, 32, 13.06, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(695, 35, 32, 6.87, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(696, 36, 32, 5.23, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(697, 34, 33, 17.58, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(698, 35, 33, 10.91, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(699, 36, 33, 17.78, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(700, 34, 34, 10.34, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(701, 35, 34, 5.18, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(702, 36, 34, 5.05, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(703, 34, 35, 3.04, 'present', NULL, 6, '2026-05-18 20:58:16', NULL),
(704, 35, 35, 9.48, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(705, 36, 35, 6.04, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(706, 34, 36, 12.33, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(707, 35, 36, 13.47, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(708, 36, 36, NULL, 'absent', NULL, 6, '2026-05-18 20:58:17', NULL),
(709, 34, 37, 12.17, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(710, 35, 37, 6.46, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(711, 36, 37, NULL, 'absent', NULL, 6, '2026-05-18 20:58:17', NULL),
(712, 34, 38, 11.43, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(713, 35, 38, 14.13, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(714, 36, 38, 7.22, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(715, 34, 39, 14.72, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(716, 35, 39, 15.65, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(717, 36, 39, 10.31, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(718, 34, 40, 6.79, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(719, 35, 40, 1.52, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(720, 36, 40, 8.43, 'present', NULL, 6, '2026-05-18 20:58:17', NULL),
(721, 37, 21, 15.39, 'present', NULL, 7, '2026-05-18 20:58:17', NULL),
(722, 38, 21, 11.38, 'present', NULL, 7, '2026-05-18 20:58:17', NULL),
(723, 39, 21, 18.27, 'present', NULL, 7, '2026-05-18 20:58:17', NULL),
(724, 37, 22, 13.48, 'present', NULL, 7, '2026-05-18 20:58:17', NULL),
(725, 38, 22, 11.06, 'present', NULL, 7, '2026-05-18 20:58:17', NULL),
(726, 39, 22, NULL, 'absent', NULL, 7, '2026-05-18 20:58:17', NULL),
(727, 37, 23, 12.50, 'present', NULL, 7, '2026-05-18 20:58:17', NULL),
(728, 38, 23, 11.46, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(729, 39, 23, 14.51, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(730, 37, 24, 9.16, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(731, 38, 24, 15.06, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(732, 39, 24, 9.81, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(733, 37, 25, 5.28, 'present', NULL, 7, '2026-05-18 20:58:18', NULL);
INSERT INTO `notes` (`id`, `evaluation_id`, `eleve_id`, `note`, `statut`, `observation`, `saisie_par`, `created_at`, `updated_at`) VALUES
(734, 38, 25, 9.87, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(735, 39, 25, 2.50, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(736, 37, 26, 5.56, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(737, 38, 26, 14.09, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(738, 39, 26, 6.48, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(739, 37, 27, 16.61, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(740, 38, 27, 16.38, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(741, 39, 27, 12.59, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(742, 37, 28, 12.45, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(743, 38, 28, 12.33, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(744, 39, 28, 5.03, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(745, 37, 29, 8.28, 'present', NULL, 7, '2026-05-18 20:58:18', NULL),
(746, 38, 29, 11.85, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(747, 39, 29, 6.63, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(748, 37, 30, 1.41, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(749, 38, 30, 2.47, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(750, 39, 30, 7.54, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(751, 37, 31, 8.91, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(752, 38, 31, 11.15, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(753, 39, 31, 9.39, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(754, 37, 32, 10.76, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(755, 38, 32, NULL, 'absent', NULL, 7, '2026-05-18 20:58:19', NULL),
(756, 39, 32, 7.44, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(757, 37, 33, 18.87, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(758, 38, 33, 18.17, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(759, 39, 33, 12.27, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(760, 37, 34, 11.49, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(761, 38, 34, 5.03, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(762, 39, 34, 10.62, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(763, 37, 35, 3.79, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(764, 38, 35, 9.72, 'present', NULL, 7, '2026-05-18 20:58:19', NULL),
(765, 39, 35, 4.16, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(766, 37, 36, 10.46, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(767, 38, 36, 11.72, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(768, 39, 36, 12.14, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(769, 37, 37, 14.73, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(770, 38, 37, 6.92, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(771, 39, 37, 11.97, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(772, 37, 38, 10.96, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(773, 38, 38, 8.04, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(774, 39, 38, 6.31, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(775, 37, 39, 9.58, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(776, 38, 39, 12.15, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(777, 39, 39, 15.70, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(778, 37, 40, 0.15, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(779, 38, 40, 3.50, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(780, 39, 40, 1.44, 'present', NULL, 7, '2026-05-18 20:58:20', NULL),
(781, 40, 21, 9.97, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(782, 41, 21, 9.94, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(783, 42, 21, 14.50, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(784, 40, 22, 12.78, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(785, 41, 22, 9.94, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(786, 42, 22, 8.21, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(787, 40, 23, 5.84, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(788, 41, 23, 13.36, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(789, 42, 23, 14.84, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(790, 40, 24, 9.72, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(791, 41, 24, 14.88, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(792, 42, 24, 17.69, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(793, 40, 25, 8.97, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(794, 41, 25, 5.95, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(795, 42, 25, 9.46, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(796, 40, 26, 6.41, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(797, 41, 26, 13.38, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(798, 42, 26, 11.82, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(799, 40, 27, 9.70, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(800, 41, 27, 18.33, 'present', NULL, 8, '2026-05-18 20:58:21', NULL),
(801, 42, 27, 17.09, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(802, 40, 28, 12.94, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(803, 41, 28, 11.34, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(804, 42, 28, 9.73, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(805, 40, 29, 8.64, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(806, 41, 29, NULL, 'absent', NULL, 8, '2026-05-18 20:58:22', NULL),
(807, 42, 29, 11.78, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(808, 40, 30, 3.60, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(809, 41, 30, 1.99, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(810, 42, 30, 2.46, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(811, 40, 31, 6.75, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(812, 41, 31, NULL, 'absent', NULL, 8, '2026-05-18 20:58:22', NULL),
(813, 42, 31, 10.39, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(814, 40, 32, 8.47, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(815, 41, 32, 14.53, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(816, 42, 32, 7.77, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(817, 40, 33, 9.44, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(818, 41, 33, 13.58, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(819, 42, 33, 17.69, 'present', NULL, 8, '2026-05-18 20:58:22', NULL),
(820, 40, 34, 12.75, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(821, 41, 34, 11.59, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(822, 42, 34, 11.78, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(823, 40, 35, 2.04, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(824, 41, 35, 6.35, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(825, 42, 35, 5.58, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(826, 40, 36, 14.25, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(827, 41, 36, 17.79, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(828, 42, 36, 16.26, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(829, 40, 37, 14.35, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(830, 41, 37, 12.56, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(831, 42, 37, 7.80, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(832, 40, 38, 11.13, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(833, 41, 38, 5.31, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(834, 42, 38, 6.17, 'present', NULL, 8, '2026-05-18 20:58:23', NULL),
(835, 40, 39, 9.57, 'present', NULL, 8, '2026-05-18 20:58:24', NULL),
(836, 41, 39, 17.02, 'present', NULL, 8, '2026-05-18 20:58:24', NULL),
(837, 42, 39, 17.68, 'present', NULL, 8, '2026-05-18 20:58:24', NULL),
(838, 40, 40, 3.87, 'present', NULL, 8, '2026-05-18 20:58:24', NULL),
(839, 41, 40, 3.88, 'present', NULL, 8, '2026-05-18 20:58:24', NULL),
(840, 42, 40, 3.29, 'present', NULL, 8, '2026-05-18 20:58:24', NULL),
(841, 43, 21, 17.19, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(842, 44, 21, 9.19, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(843, 45, 21, 15.21, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(844, 43, 22, 13.32, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(845, 44, 22, 5.77, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(846, 45, 22, 6.88, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(847, 43, 23, 10.17, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(848, 44, 23, 9.52, 'present', NULL, 9, '2026-05-18 20:58:24', NULL),
(849, 45, 23, 14.59, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(850, 43, 24, 13.66, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(851, 44, 24, 16.43, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(852, 45, 24, 13.54, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(853, 43, 25, 7.07, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(854, 44, 25, NULL, 'absent', NULL, 9, '2026-05-18 20:58:25', NULL),
(855, 45, 25, 3.01, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(856, 43, 26, 9.93, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(857, 44, 26, 11.41, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(858, 45, 26, 14.76, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(859, 43, 27, 16.14, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(860, 44, 27, 14.40, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(861, 45, 27, 16.31, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(862, 43, 28, 9.59, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(863, 44, 28, 6.57, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(864, 45, 28, 7.52, 'present', NULL, 9, '2026-05-18 20:58:25', NULL),
(865, 43, 29, 6.70, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(866, 44, 29, 5.14, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(867, 45, 29, 8.77, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(868, 43, 30, 0.25, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(869, 44, 30, 5.59, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(870, 45, 30, 9.78, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(871, 43, 31, 9.59, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(872, 44, 31, 14.88, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(873, 45, 31, 13.02, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(874, 43, 32, 10.43, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(875, 44, 32, NULL, 'absent', NULL, 9, '2026-05-18 20:58:26', NULL),
(876, 45, 32, 9.21, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(877, 43, 33, 13.22, 'present', NULL, 9, '2026-05-18 20:58:26', NULL),
(878, 44, 33, 13.64, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(879, 45, 33, 10.91, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(880, 43, 34, 5.16, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(881, 44, 34, 12.04, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(882, 45, 34, 6.44, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(883, 43, 35, 3.79, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(884, 44, 35, 1.19, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(885, 45, 35, 2.17, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(886, 43, 36, 16.33, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(887, 44, 36, 14.76, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(888, 45, 36, 18.71, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(889, 43, 37, 7.06, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(890, 44, 37, 10.70, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(891, 45, 37, 5.59, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(892, 43, 38, 11.28, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(893, 44, 38, 6.84, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(894, 45, 38, 5.79, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(895, 43, 39, 15.32, 'present', NULL, 9, '2026-05-18 20:58:27', NULL),
(896, 44, 39, 13.69, 'present', NULL, 9, '2026-05-18 20:58:28', NULL),
(897, 45, 39, 16.60, 'present', NULL, 9, '2026-05-18 20:58:28', NULL),
(898, 43, 40, 4.50, 'present', NULL, 9, '2026-05-18 20:58:28', NULL),
(899, 44, 40, 5.27, 'present', NULL, 9, '2026-05-18 20:58:28', NULL),
(900, 45, 40, 4.45, 'present', NULL, 9, '2026-05-18 20:58:28', NULL),
(901, 46, 21, NULL, 'absent', NULL, 10, '2026-05-18 20:58:28', NULL),
(902, 47, 21, 17.64, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(903, 48, 21, 17.60, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(904, 46, 22, NULL, 'absent', NULL, 10, '2026-05-18 20:58:28', NULL),
(905, 47, 22, 7.76, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(906, 48, 22, 7.85, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(907, 46, 23, 14.76, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(908, 47, 23, 14.76, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(909, 48, 23, 7.02, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(910, 46, 24, 18.66, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(911, 47, 24, NULL, 'absent', NULL, 10, '2026-05-18 20:58:28', NULL),
(912, 48, 24, 10.13, 'present', NULL, 10, '2026-05-18 20:58:28', NULL),
(913, 46, 25, 8.23, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(914, 47, 25, 7.25, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(915, 48, 25, 9.71, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(916, 46, 26, 14.32, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(917, 47, 26, 14.08, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(918, 48, 26, 8.39, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(919, 46, 27, 13.75, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(920, 47, 27, 11.58, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(921, 48, 27, 14.85, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(922, 46, 28, 10.71, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(923, 47, 28, NULL, 'absent', NULL, 10, '2026-05-18 20:58:29', NULL),
(924, 48, 28, 12.13, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(925, 46, 29, 14.59, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(926, 47, 29, 8.13, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(927, 48, 29, 14.62, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(928, 46, 30, 6.11, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(929, 47, 30, 7.18, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(930, 48, 30, 1.00, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(931, 46, 31, 10.50, 'present', NULL, 10, '2026-05-18 20:58:29', NULL),
(932, 47, 31, 11.56, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(933, 48, 31, 9.41, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(934, 46, 32, 14.30, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(935, 47, 32, 9.25, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(936, 48, 32, 8.59, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(937, 46, 33, 17.19, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(938, 47, 33, 18.58, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(939, 48, 33, 9.73, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(940, 46, 34, 13.78, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(941, 47, 34, 13.20, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(942, 48, 34, 8.55, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(943, 46, 35, 1.72, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(944, 47, 35, 2.09, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(945, 48, 35, 3.80, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(946, 46, 36, 18.31, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(947, 47, 36, 10.87, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(948, 48, 36, 9.38, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(949, 46, 37, 8.28, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(950, 47, 37, 8.70, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(951, 48, 37, 11.99, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(952, 46, 38, 5.57, 'present', NULL, 10, '2026-05-18 20:58:30', NULL),
(953, 47, 38, 6.23, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(954, 48, 38, 8.51, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(955, 46, 39, 13.08, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(956, 47, 39, 12.54, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(957, 48, 39, 14.88, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(958, 46, 40, 4.97, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(959, 47, 40, 8.39, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(960, 48, 40, 4.32, 'present', NULL, 10, '2026-05-18 20:58:31', NULL),
(961, 49, 21, 13.04, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(962, 50, 21, 16.03, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(963, 51, 21, 15.50, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(964, 49, 22, 5.70, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(965, 50, 22, 14.81, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(966, 51, 22, 6.88, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(967, 49, 23, 6.32, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(968, 50, 23, 10.25, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(969, 51, 23, 7.95, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(970, 49, 24, 17.43, 'present', NULL, 3, '2026-05-18 20:58:31', NULL),
(971, 50, 24, 14.88, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(972, 51, 24, 18.81, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(973, 49, 25, 5.65, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(974, 50, 25, 0.43, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(975, 51, 25, 5.93, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(976, 49, 26, 14.84, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(977, 50, 26, 8.03, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(978, 51, 26, 7.25, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(979, 49, 27, 13.19, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(980, 50, 27, 16.83, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(981, 51, 27, 18.61, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(982, 49, 28, 11.74, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(983, 50, 28, 7.93, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(984, 51, 28, 8.12, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(985, 49, 29, 13.00, 'present', NULL, 3, '2026-05-18 20:58:32', NULL),
(986, 50, 29, 9.16, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(987, 51, 29, 9.00, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(988, 49, 30, 5.12, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(989, 50, 30, 9.05, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(990, 51, 30, 7.86, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(991, 49, 31, NULL, 'absent', NULL, 3, '2026-05-18 20:58:33', NULL),
(992, 50, 31, 12.09, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(993, 51, 31, 7.63, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(994, 49, 32, 9.15, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(995, 50, 32, 13.72, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(996, 51, 32, 9.88, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(997, 49, 33, 17.37, 'present', NULL, 3, '2026-05-18 20:58:33', NULL),
(998, 50, 33, 10.27, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(999, 51, 33, 18.33, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1000, 49, 34, 5.25, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1001, 50, 34, 14.06, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1002, 51, 34, 7.97, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1003, 49, 35, 9.88, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1004, 50, 35, NULL, 'absent', NULL, 3, '2026-05-18 20:58:34', NULL),
(1005, 51, 35, 0.90, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1006, 49, 36, 9.85, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1007, 50, 36, 15.90, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1008, 51, 36, 11.34, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1009, 49, 37, 5.46, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1010, 50, 37, 5.30, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1011, 51, 37, 8.95, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1012, 49, 38, 8.76, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1013, 50, 38, 7.85, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1014, 51, 38, 14.49, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1015, 49, 39, 14.42, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1016, 50, 39, 14.55, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1017, 51, 39, 16.00, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1018, 49, 40, 8.27, 'present', NULL, 3, '2026-05-18 20:58:34', NULL),
(1019, 50, 40, 2.90, 'present', NULL, 3, '2026-05-18 20:58:35', NULL),
(1020, 51, 40, 0.03, 'present', NULL, 3, '2026-05-18 20:58:35', NULL),
(1021, 52, 21, 17.91, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1022, 53, 21, 14.40, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1023, 54, 21, 13.10, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1024, 52, 22, 6.65, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1025, 53, 22, 10.75, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1026, 54, 22, 6.13, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1027, 52, 23, 13.72, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1028, 53, 23, 6.54, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1029, 54, 23, 6.67, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1030, 52, 24, 16.65, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1031, 53, 24, 9.93, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1032, 54, 24, 16.27, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1033, 52, 25, 8.78, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1034, 53, 25, 2.48, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1035, 54, 25, 5.03, 'present', NULL, 11, '2026-05-18 20:58:35', NULL),
(1036, 52, 26, 13.42, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1037, 53, 26, 12.99, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1038, 54, 26, 11.34, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1039, 52, 27, 11.62, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1040, 53, 27, 17.19, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1041, 54, 27, 9.85, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1042, 52, 28, 12.66, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1043, 53, 28, 10.91, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1044, 54, 28, 10.08, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1045, 52, 29, 9.24, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1046, 53, 29, 9.78, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1047, 54, 29, 12.02, 'present', NULL, 11, '2026-05-18 20:58:36', NULL),
(1048, 52, 30, NULL, 'absent', NULL, 11, '2026-05-18 20:58:37', NULL),
(1049, 53, 30, 9.20, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1050, 54, 30, 2.76, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1051, 52, 31, 9.79, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1052, 53, 31, 12.98, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1053, 54, 31, 9.76, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1054, 52, 32, 10.52, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1055, 53, 32, 11.54, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1056, 54, 32, 9.63, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1057, 52, 33, 15.48, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1058, 53, 33, 14.99, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1059, 54, 33, 17.83, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1060, 52, 34, 9.74, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1061, 53, 34, 14.51, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1062, 54, 34, NULL, 'absent', NULL, 11, '2026-05-18 20:58:37', NULL),
(1063, 52, 35, 3.29, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1064, 53, 35, 7.31, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1065, 54, 35, 3.49, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1066, 52, 36, 18.21, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1067, 53, 36, 15.63, 'present', NULL, 11, '2026-05-18 20:58:37', NULL),
(1068, 54, 36, 15.42, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1069, 52, 37, 5.42, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1070, 53, 37, 12.23, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1071, 54, 37, 6.85, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1072, 52, 38, 11.18, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1073, 53, 38, 5.25, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1074, 54, 38, 5.72, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1075, 52, 39, 18.46, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1076, 53, 39, 10.82, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1077, 54, 39, 9.87, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1078, 52, 40, 2.97, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1079, 53, 40, 9.98, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1080, 54, 40, 4.13, 'present', NULL, 11, '2026-05-18 20:58:38', NULL),
(1081, 55, 21, 11.94, 'present', NULL, 12, '2026-05-18 20:58:38', NULL),
(1082, 56, 21, 16.35, 'present', NULL, 12, '2026-05-18 20:58:38', NULL),
(1083, 57, 21, 17.20, 'present', NULL, 12, '2026-05-18 20:58:38', NULL),
(1084, 55, 22, 8.40, 'present', NULL, 12, '2026-05-18 20:58:38', NULL),
(1085, 56, 22, 7.88, 'present', NULL, 12, '2026-05-18 20:58:38', NULL),
(1086, 57, 22, 8.10, 'present', NULL, 12, '2026-05-18 20:58:38', NULL),
(1087, 55, 23, 11.19, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1088, 56, 23, 14.64, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1089, 57, 23, 13.48, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1090, 55, 24, 11.41, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1091, 56, 24, 17.99, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1092, 57, 24, 11.29, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1093, 55, 25, 9.90, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1094, 56, 25, 7.27, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1095, 57, 25, 4.57, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1096, 55, 26, 6.51, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1097, 56, 26, 9.48, 'present', NULL, 12, '2026-05-18 20:58:39', NULL),
(1098, 57, 26, NULL, 'absent', NULL, 12, '2026-05-18 20:58:40', NULL),
(1099, 55, 27, 10.41, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1100, 56, 27, 10.64, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1101, 57, 27, 14.67, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1102, 55, 28, 10.14, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1103, 56, 28, 13.53, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1104, 57, 28, 5.78, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1105, 55, 29, 8.03, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1106, 56, 29, 11.24, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1107, 57, 29, 9.63, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1108, 55, 30, 2.26, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1109, 56, 30, 6.37, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1110, 57, 30, 7.37, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1111, 55, 31, 13.84, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1112, 56, 31, 10.55, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1113, 57, 31, 11.70, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1114, 55, 32, 9.33, 'present', NULL, 12, '2026-05-18 20:58:40', NULL),
(1115, 56, 32, 6.45, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1116, 57, 32, 8.31, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1117, 55, 33, 12.07, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1118, 56, 33, 15.55, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1119, 57, 33, 11.06, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1120, 55, 34, 11.56, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1121, 56, 34, 5.22, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1122, 57, 34, 12.76, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1123, 55, 35, 4.27, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1124, 56, 35, 1.04, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1125, 57, 35, 4.10, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1126, 55, 36, NULL, 'absent', NULL, 12, '2026-05-18 20:58:41', NULL),
(1127, 56, 36, 16.90, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1128, 57, 36, 11.69, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1129, 55, 37, 10.83, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1130, 56, 37, 8.65, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1131, 57, 37, NULL, 'absent', NULL, 12, '2026-05-18 20:58:41', NULL),
(1132, 55, 38, 11.10, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1133, 56, 38, 5.13, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1134, 57, 38, 10.62, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1135, 55, 39, 12.14, 'present', NULL, 12, '2026-05-18 20:58:41', NULL),
(1136, 56, 39, 15.46, 'present', NULL, 12, '2026-05-18 20:58:42', NULL),
(1137, 57, 39, NULL, 'absent', NULL, 12, '2026-05-18 20:58:42', NULL),
(1138, 55, 40, 0.40, 'present', NULL, 12, '2026-05-18 20:58:42', NULL),
(1139, 56, 40, 0.23, 'present', NULL, 12, '2026-05-18 20:58:42', NULL),
(1140, 57, 40, 8.57, 'present', NULL, 12, '2026-05-18 20:58:42', NULL),
(1141, 58, 21, 18.59, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1142, 59, 21, 10.27, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1143, 60, 21, 15.87, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1144, 58, 22, 10.39, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1145, 59, 22, 5.03, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1146, 60, 22, 7.91, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1147, 58, 23, 12.01, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1148, 59, 23, 9.55, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1149, 60, 23, 13.85, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1150, 58, 24, NULL, 'absent', NULL, 14, '2026-05-18 20:58:42', NULL),
(1151, 59, 24, 15.64, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1152, 60, 24, 13.57, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1153, 58, 25, 6.34, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1154, 59, 25, 5.80, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1155, 60, 25, 7.24, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1156, 58, 26, 10.61, 'present', NULL, 14, '2026-05-18 20:58:42', NULL),
(1157, 59, 26, 9.09, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1158, 60, 26, 5.55, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1159, 58, 27, 16.09, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1160, 59, 27, 12.64, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1161, 60, 27, 12.22, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1162, 58, 28, 12.06, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1163, 59, 28, 9.46, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1164, 60, 28, 10.97, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1165, 58, 29, 12.18, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1166, 59, 29, 6.87, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1167, 60, 29, 6.02, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1168, 58, 30, 4.89, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1169, 59, 30, NULL, 'absent', NULL, 14, '2026-05-18 20:58:43', NULL),
(1170, 60, 30, 5.22, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1171, 58, 31, 13.99, 'present', NULL, 14, '2026-05-18 20:58:43', NULL),
(1172, 59, 31, 11.78, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1173, 60, 31, 12.31, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1174, 58, 32, 13.52, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1175, 59, 32, 7.13, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1176, 60, 32, 11.02, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1177, 58, 33, 11.18, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1178, 59, 33, 18.87, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1179, 60, 33, 17.16, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1180, 58, 34, 5.92, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1181, 59, 34, 8.56, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1182, 60, 34, 7.60, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1183, 58, 35, 8.06, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1184, 59, 35, 5.76, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1185, 60, 35, 9.98, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1186, 58, 36, 17.24, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1187, 59, 36, 13.65, 'present', NULL, 14, '2026-05-18 20:58:44', NULL),
(1188, 60, 36, 9.46, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1189, 58, 37, 7.80, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1190, 59, 37, 5.24, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1191, 60, 37, 6.88, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1192, 58, 38, 9.18, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1193, 59, 38, 6.46, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1194, 60, 38, 5.13, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1195, 58, 39, 16.89, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1196, 59, 39, 12.59, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1197, 60, 39, 14.14, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1198, 58, 40, 0.40, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1199, 59, 40, 5.25, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1200, 60, 40, 7.43, 'present', NULL, 14, '2026-05-18 20:58:45', NULL),
(1201, 61, 21, 17.03, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1202, 62, 21, 10.57, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1203, 63, 21, 18.89, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1204, 61, 22, 5.50, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1205, 62, 22, 9.25, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1206, 63, 22, 8.28, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1207, 61, 23, 9.22, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1208, 62, 23, 11.76, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1209, 63, 23, 13.68, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1210, 61, 24, 14.56, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1211, 62, 24, 17.78, 'present', NULL, 15, '2026-05-18 20:58:46', NULL),
(1212, 63, 24, NULL, 'absent', NULL, 15, '2026-05-18 20:58:46', NULL),
(1213, 61, 25, 2.78, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1214, 62, 25, 9.30, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1215, 63, 25, 0.74, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1216, 61, 26, 11.35, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1217, 62, 26, 10.86, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1218, 63, 26, 8.92, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1219, 61, 27, 9.64, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1220, 62, 27, 9.57, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1221, 63, 27, 11.61, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1222, 61, 28, 9.22, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1223, 62, 28, 13.93, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1224, 63, 28, 14.78, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1225, 61, 29, 13.53, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1226, 62, 29, 12.11, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1227, 63, 29, 9.21, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1228, 61, 30, 8.09, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1229, 62, 30, 8.33, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1230, 63, 30, NULL, 'absent', NULL, 15, '2026-05-18 20:58:47', NULL),
(1231, 61, 31, 8.45, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1232, 62, 31, 12.11, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1233, 63, 31, 7.20, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1234, 61, 32, 7.25, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1235, 62, 32, 10.36, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1236, 63, 32, 8.12, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1237, 61, 33, 17.89, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1238, 62, 33, 12.79, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1239, 63, 33, 16.38, 'present', NULL, 15, '2026-05-18 20:58:47', NULL),
(1240, 61, 34, NULL, 'absent', NULL, 15, '2026-05-18 20:58:48', NULL),
(1241, 62, 34, 10.38, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1242, 63, 34, 8.71, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1243, 61, 35, 0.98, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1244, 62, 35, NULL, 'absent', NULL, 15, '2026-05-18 20:58:48', NULL),
(1245, 63, 35, 3.04, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1246, 61, 36, 9.03, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1247, 62, 36, 15.08, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1248, 63, 36, 16.25, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1249, 61, 37, 11.57, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1250, 62, 37, NULL, 'absent', NULL, 15, '2026-05-18 20:58:48', NULL),
(1251, 63, 37, 14.32, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1252, 61, 38, 6.88, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1253, 62, 38, 13.75, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1254, 63, 38, 12.23, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1255, 61, 39, 13.77, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1256, 62, 39, 18.01, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1257, 63, 39, 17.09, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1258, 61, 40, 1.89, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1259, 62, 40, 4.96, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1260, 63, 40, 9.94, 'present', NULL, 15, '2026-05-18 20:58:48', NULL),
(1261, 64, 41, 8.45, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1262, 65, 41, 9.90, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1263, 66, 41, 13.24, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1264, 64, 42, 9.85, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1265, 65, 42, 16.41, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1266, 66, 42, 16.24, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1267, 64, 43, 5.79, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1268, 65, 43, 11.71, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1269, 66, 43, 7.84, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1270, 64, 44, 6.48, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1271, 65, 44, 10.15, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1272, 66, 44, 5.67, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1273, 64, 45, 9.48, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1274, 65, 45, 7.17, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1275, 66, 45, 7.01, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1276, 64, 46, 6.99, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1277, 65, 46, 5.06, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1278, 66, 46, 13.54, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1279, 64, 47, 12.58, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1280, 65, 47, 8.21, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1281, 66, 47, 10.99, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1282, 64, 48, 9.25, 'present', NULL, 6, '2026-05-18 20:58:49', NULL),
(1283, 65, 48, NULL, 'absent', NULL, 6, '2026-05-18 20:58:49', NULL),
(1284, 66, 48, 14.29, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1285, 64, 49, 11.68, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1286, 65, 49, 13.84, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1287, 66, 49, 12.86, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1288, 64, 50, 6.20, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1289, 65, 50, 5.63, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1290, 66, 50, 1.35, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1291, 64, 51, 9.77, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1292, 65, 51, 18.11, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1293, 66, 51, 14.75, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1294, 64, 52, 10.63, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1295, 65, 52, 13.09, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1296, 66, 52, 5.15, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1297, 64, 53, 10.55, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1298, 65, 53, 13.24, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1299, 66, 53, 8.24, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1300, 64, 54, 11.95, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1301, 65, 54, 13.18, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1302, 66, 54, 17.54, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1303, 64, 55, 1.56, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1304, 65, 55, 3.86, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1305, 66, 55, 1.32, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1306, 64, 56, 6.55, 'present', NULL, 6, '2026-05-18 20:58:50', NULL),
(1307, 65, 56, 6.99, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1308, 66, 56, 14.52, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1309, 64, 57, 12.12, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1310, 65, 57, 16.14, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1311, 66, 57, 12.23, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1312, 64, 58, 10.93, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1313, 65, 58, 7.01, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1314, 66, 58, 8.36, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1315, 64, 59, 6.90, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1316, 65, 59, 11.16, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1317, 66, 59, 10.77, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1318, 64, 60, 7.30, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1319, 65, 60, 0.02, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1320, 66, 60, 3.89, 'present', NULL, 6, '2026-05-18 20:58:51', NULL),
(1321, 67, 41, 14.20, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1322, 68, 41, 9.69, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1323, 69, 41, 6.01, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1324, 67, 42, 14.14, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1325, 68, 42, 10.67, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1326, 69, 42, 10.99, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1327, 67, 43, 7.85, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1328, 68, 43, 11.47, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1329, 69, 43, 12.90, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1330, 67, 44, NULL, 'absent', NULL, 7, '2026-05-18 20:58:51', NULL),
(1331, 68, 44, 9.04, 'present', NULL, 7, '2026-05-18 20:58:51', NULL),
(1332, 69, 44, 9.12, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1333, 67, 45, 9.55, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1334, 68, 45, 7.23, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1335, 69, 45, 7.94, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1336, 67, 46, 6.23, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1337, 68, 46, 6.03, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1338, 69, 46, 14.97, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1339, 67, 47, 5.88, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1340, 68, 47, 11.62, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1341, 69, 47, 7.61, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1342, 67, 48, 10.77, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1343, 68, 48, 14.65, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1344, 69, 48, 18.71, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1345, 67, 49, 9.41, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1346, 68, 49, 11.36, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1347, 69, 49, 6.18, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1348, 67, 50, 3.47, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1349, 68, 50, 1.14, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1350, 69, 50, 7.63, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1351, 67, 51, 13.57, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1352, 68, 51, 12.81, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1353, 69, 51, 16.27, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1354, 67, 52, 10.75, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1355, 68, 52, 12.43, 'present', NULL, 7, '2026-05-18 20:58:52', NULL),
(1356, 69, 52, 13.25, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1357, 67, 53, 12.50, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1358, 68, 53, 8.45, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1359, 69, 53, 6.14, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1360, 67, 54, 14.70, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1361, 68, 54, 13.72, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1362, 69, 54, 11.85, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1363, 67, 55, 7.18, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1364, 68, 55, 7.35, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1365, 69, 55, 0.75, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1366, 67, 56, 6.94, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1367, 68, 56, 12.11, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1368, 69, 56, 12.46, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1369, 67, 57, 11.57, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1370, 68, 57, 12.60, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1371, 69, 57, 12.06, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1372, 67, 58, 13.98, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1373, 68, 58, 9.05, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1374, 69, 58, 12.42, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1375, 67, 59, 7.23, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1376, 68, 59, 5.19, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1377, 69, 59, 11.31, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1378, 67, 60, 1.23, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1379, 68, 60, 4.19, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1380, 69, 60, 7.50, 'present', NULL, 7, '2026-05-18 20:58:53', NULL),
(1381, 70, 41, 11.93, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1382, 71, 41, 14.46, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1383, 72, 41, 12.36, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1384, 70, 42, 11.61, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1385, 71, 42, 10.61, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1386, 72, 42, 15.67, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1387, 70, 43, 7.60, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1388, 71, 43, 5.96, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1389, 72, 43, 8.29, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1390, 70, 44, 14.34, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1391, 71, 44, 5.01, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1392, 72, 44, 7.50, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1393, 70, 45, 7.28, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1394, 71, 45, 5.92, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1395, 72, 45, 8.06, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1396, 70, 46, NULL, 'absent', NULL, 8, '2026-05-18 20:58:54', NULL),
(1397, 71, 46, 9.47, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1398, 72, 46, 9.06, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1399, 70, 47, 8.37, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1400, 71, 47, 5.65, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1401, 72, 47, 13.16, 'present', NULL, 8, '2026-05-18 20:58:54', NULL),
(1402, 70, 48, 17.35, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1403, 71, 48, 10.18, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1404, 72, 48, 10.19, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1405, 70, 49, 6.89, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1406, 71, 49, 10.48, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1407, 72, 49, 8.58, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1408, 70, 50, 7.47, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1409, 71, 50, 0.51, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1410, 72, 50, 8.37, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1411, 70, 51, 13.73, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1412, 71, 51, NULL, 'absent', NULL, 8, '2026-05-18 20:58:55', NULL),
(1413, 72, 51, 16.27, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1414, 70, 52, NULL, 'absent', NULL, 8, '2026-05-18 20:58:55', NULL),
(1415, 71, 52, 12.12, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1416, 72, 52, 13.55, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1417, 70, 53, 11.68, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1418, 71, 53, 14.28, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1419, 72, 53, 11.98, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1420, 70, 54, 15.42, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1421, 71, 54, 9.19, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1422, 72, 54, 13.29, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1423, 70, 55, 6.88, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1424, 71, 55, 9.93, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1425, 72, 55, 9.33, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1426, 70, 56, 8.29, 'present', NULL, 8, '2026-05-18 20:58:55', NULL),
(1427, 71, 56, 13.19, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1428, 72, 56, 13.77, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1429, 70, 57, 14.58, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1430, 71, 57, 16.14, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1431, 72, 57, 10.52, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1432, 70, 58, 6.54, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1433, 71, 58, 12.00, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1434, 72, 58, NULL, 'absent', NULL, 8, '2026-05-18 20:58:56', NULL),
(1435, 70, 59, 7.02, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1436, 71, 59, 13.30, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1437, 72, 59, NULL, 'absent', NULL, 8, '2026-05-18 20:58:56', NULL),
(1438, 70, 60, 0.15, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1439, 71, 60, 6.10, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1440, 72, 60, 5.62, 'present', NULL, 8, '2026-05-18 20:58:56', NULL),
(1441, 73, 41, 13.47, 'present', NULL, 9, '2026-05-18 20:58:56', NULL),
(1442, 74, 41, 7.17, 'present', NULL, 9, '2026-05-18 20:58:56', NULL),
(1443, 75, 41, 7.30, 'present', NULL, 9, '2026-05-18 20:58:56', NULL),
(1444, 73, 42, 16.59, 'present', NULL, 9, '2026-05-18 20:58:56', NULL),
(1445, 74, 42, 12.02, 'present', NULL, 9, '2026-05-18 20:58:57', NULL),
(1446, 75, 42, 14.51, 'present', NULL, 9, '2026-05-18 20:58:58', NULL),
(1447, 73, 43, 14.78, 'present', NULL, 9, '2026-05-18 20:58:58', NULL),
(1448, 74, 43, 5.12, 'present', NULL, 9, '2026-05-18 20:58:58', NULL),
(1449, 75, 43, 6.73, 'present', NULL, 9, '2026-05-18 20:58:58', NULL),
(1450, 73, 44, 14.07, 'present', NULL, 9, '2026-05-18 20:58:58', NULL);
INSERT INTO `notes` (`id`, `evaluation_id`, `eleve_id`, `note`, `statut`, `observation`, `saisie_par`, `created_at`, `updated_at`) VALUES
(1451, 74, 44, 14.41, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1452, 75, 44, 12.17, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1453, 73, 45, 2.11, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1454, 74, 45, NULL, 'absent', NULL, 9, '2026-05-18 20:58:59', NULL),
(1455, 75, 45, 5.69, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1456, 73, 46, 9.53, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1457, 74, 46, 13.76, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1458, 75, 46, 11.15, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1459, 73, 47, 9.54, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1460, 74, 47, 7.20, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1461, 75, 47, 14.27, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1462, 73, 48, 13.38, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1463, 74, 48, 17.31, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1464, 75, 48, 17.65, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1465, 73, 49, 9.54, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1466, 74, 49, 7.54, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1467, 75, 49, 10.38, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1468, 73, 50, 3.45, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1469, 74, 50, 0.79, 'present', NULL, 9, '2026-05-18 20:58:59', NULL),
(1470, 75, 50, 1.16, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1471, 73, 51, 17.23, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1472, 74, 51, 16.02, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1473, 75, 51, 10.09, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1474, 73, 52, 8.20, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1475, 74, 52, 14.35, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1476, 75, 52, 8.47, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1477, 73, 53, 12.56, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1478, 74, 53, 8.98, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1479, 75, 53, 7.40, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1480, 73, 54, 14.97, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1481, 74, 54, 17.38, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1482, 75, 54, 12.80, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1483, 73, 55, 4.68, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1484, 74, 55, 6.90, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1485, 75, 55, 7.03, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1486, 73, 56, 5.77, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1487, 74, 56, 7.81, 'present', NULL, 9, '2026-05-18 20:59:00', NULL),
(1488, 75, 56, 5.61, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1489, 73, 57, 16.62, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1490, 74, 57, 11.41, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1491, 75, 57, 18.81, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1492, 73, 58, 14.18, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1493, 74, 58, 9.77, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1494, 75, 58, 10.05, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1495, 73, 59, 11.33, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1496, 74, 59, 12.25, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1497, 75, 59, 13.50, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1498, 73, 60, 9.53, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1499, 74, 60, 1.93, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1500, 75, 60, 8.35, 'present', NULL, 9, '2026-05-18 20:59:01', NULL),
(1501, 76, 41, 14.31, 'present', NULL, 10, '2026-05-18 20:59:01', NULL),
(1502, 77, 41, 5.37, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1503, 78, 41, 10.08, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1504, 76, 42, 10.66, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1505, 77, 42, 15.31, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1506, 78, 42, 16.60, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1507, 76, 43, 5.63, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1508, 77, 43, 14.98, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1509, 78, 43, 5.16, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1510, 76, 44, 13.60, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1511, 77, 44, 5.43, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1512, 78, 44, 6.28, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1513, 76, 45, 5.36, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1514, 77, 45, 9.62, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1515, 78, 45, 7.61, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1516, 76, 46, 5.43, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1517, 77, 46, 11.94, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1518, 78, 46, 5.48, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1519, 76, 47, 10.56, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1520, 77, 47, 12.39, 'present', NULL, 10, '2026-05-18 20:59:02', NULL),
(1521, 78, 47, 6.81, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1522, 76, 48, 15.59, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1523, 77, 48, 10.05, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1524, 78, 48, 15.96, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1525, 76, 49, 10.81, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1526, 77, 49, 6.20, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1527, 78, 49, 9.95, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1528, 76, 50, 1.89, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1529, 77, 50, 2.84, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1530, 78, 50, 0.39, 'present', NULL, 10, '2026-05-18 20:59:03', NULL),
(1531, 76, 51, NULL, 'absent', NULL, 10, '2026-05-18 20:59:04', NULL),
(1532, 77, 51, 16.48, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1533, 78, 51, 12.87, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1534, 76, 52, 8.07, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1535, 77, 52, 11.34, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1536, 78, 52, 8.32, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1537, 76, 53, 12.91, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1538, 77, 53, 10.35, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1539, 78, 53, 14.36, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1540, 76, 54, 9.76, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1541, 77, 54, 16.18, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1542, 78, 54, 18.22, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1543, 76, 55, 1.07, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1544, 77, 55, 8.57, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1545, 78, 55, 3.25, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1546, 76, 56, 8.51, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1547, 77, 56, 8.84, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1548, 78, 56, 12.22, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1549, 76, 57, 9.77, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1550, 77, 57, 9.87, 'present', NULL, 10, '2026-05-18 20:59:04', NULL),
(1551, 78, 57, 18.31, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1552, 76, 58, 13.66, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1553, 77, 58, 5.26, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1554, 78, 58, 14.51, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1555, 76, 59, NULL, 'absent', NULL, 10, '2026-05-18 20:59:05', NULL),
(1556, 77, 59, 13.33, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1557, 78, 59, 10.60, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1558, 76, 60, 9.82, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1559, 77, 60, 0.80, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1560, 78, 60, 0.28, 'present', NULL, 10, '2026-05-18 20:59:05', NULL),
(1561, 79, 41, 14.72, 'present', NULL, 11, '2026-05-18 20:59:05', NULL),
(1562, 80, 41, 6.36, 'present', NULL, 11, '2026-05-18 20:59:05', NULL),
(1563, 81, 41, NULL, 'absent', NULL, 11, '2026-05-18 20:59:05', NULL),
(1564, 79, 42, 12.94, 'present', NULL, 11, '2026-05-18 20:59:05', NULL),
(1565, 80, 42, 15.33, 'present', NULL, 11, '2026-05-18 20:59:05', NULL),
(1566, 81, 42, 9.56, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1567, 79, 43, 10.42, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1568, 80, 43, 8.36, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1569, 81, 43, 5.58, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1570, 79, 44, 10.16, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1571, 80, 44, 11.04, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1572, 81, 44, 11.88, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1573, 79, 45, 6.00, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1574, 80, 45, 3.55, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1575, 81, 45, NULL, 'absent', NULL, 11, '2026-05-18 20:59:06', NULL),
(1576, 79, 46, 12.41, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1577, 80, 46, 12.36, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1578, 81, 46, 12.69, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1579, 79, 47, 9.50, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1580, 80, 47, 11.03, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1581, 81, 47, 13.89, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1582, 79, 48, 12.54, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1583, 80, 48, NULL, 'absent', NULL, 11, '2026-05-18 20:59:06', NULL),
(1584, 81, 48, 16.00, 'present', NULL, 11, '2026-05-18 20:59:06', NULL),
(1585, 79, 49, 6.92, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1586, 80, 49, 13.92, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1587, 81, 49, 11.80, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1588, 79, 50, 3.73, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1589, 80, 50, 6.57, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1590, 81, 50, 7.22, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1591, 79, 51, 13.85, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1592, 80, 51, 13.79, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1593, 81, 51, 13.91, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1594, 79, 52, 8.95, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1595, 80, 52, 13.30, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1596, 81, 52, 8.62, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1597, 79, 53, 10.06, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1598, 80, 53, 12.67, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1599, 81, 53, 5.94, 'present', NULL, 11, '2026-05-18 20:59:07', NULL),
(1600, 79, 54, 15.64, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1601, 80, 54, 18.79, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1602, 81, 54, 11.05, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1603, 79, 55, 2.03, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1604, 80, 55, 0.16, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1605, 81, 55, 6.64, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1606, 79, 56, 7.79, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1607, 80, 56, 10.66, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1608, 81, 56, 8.36, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1609, 79, 57, 9.07, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1610, 80, 57, 13.46, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1611, 81, 57, 14.37, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1612, 79, 58, 11.53, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1613, 80, 58, 13.74, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1614, 81, 58, 11.94, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1615, 79, 59, 12.78, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1616, 80, 59, 11.47, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1617, 81, 59, 8.88, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1618, 79, 60, 7.80, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1619, 80, 60, 1.78, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1620, 81, 60, 6.96, 'present', NULL, 11, '2026-05-18 20:59:08', NULL),
(1621, 82, 41, 14.77, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1622, 83, 41, 11.71, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1623, 84, 41, 8.67, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1624, 82, 42, 9.51, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1625, 83, 42, 12.18, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1626, 84, 42, 14.54, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1627, 82, 43, 11.82, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1628, 83, 43, 10.86, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1629, 84, 43, 10.82, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1630, 82, 44, 10.33, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1631, 83, 44, 14.55, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1632, 84, 44, 7.34, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1633, 82, 45, 2.43, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1634, 83, 45, 3.34, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1635, 84, 45, 9.60, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1636, 82, 46, 7.80, 'present', NULL, 12, '2026-05-18 20:59:09', NULL),
(1637, 83, 46, NULL, 'absent', NULL, 12, '2026-05-18 20:59:09', NULL),
(1638, 84, 46, 13.27, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1639, 82, 47, 6.45, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1640, 83, 47, 7.59, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1641, 84, 47, 5.13, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1642, 82, 48, 18.16, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1643, 83, 48, 12.91, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1644, 84, 48, 10.40, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1645, 82, 49, 12.10, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1646, 83, 49, 10.12, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1647, 84, 49, 10.81, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1648, 82, 50, 8.05, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1649, 83, 50, 8.62, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1650, 84, 50, 9.26, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1651, 82, 51, NULL, 'absent', NULL, 12, '2026-05-18 20:59:10', NULL),
(1652, 83, 51, 15.01, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1653, 84, 51, 9.21, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1654, 82, 52, 6.97, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1655, 83, 52, 8.54, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1656, 84, 52, 10.83, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1657, 82, 53, 10.83, 'present', NULL, 12, '2026-05-18 20:59:10', NULL),
(1658, 83, 53, 5.50, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1659, 84, 53, NULL, 'absent', NULL, 12, '2026-05-18 20:59:11', NULL),
(1660, 82, 54, 15.36, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1661, 83, 54, 17.21, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1662, 84, 54, 12.46, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1663, 82, 55, 2.33, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1664, 83, 55, NULL, 'absent', NULL, 12, '2026-05-18 20:59:11', NULL),
(1665, 84, 55, 9.00, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1666, 82, 56, 11.99, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1667, 83, 56, 10.84, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1668, 84, 56, 6.82, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1669, 82, 57, 9.51, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1670, 83, 57, 9.58, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1671, 84, 57, 11.08, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1672, 82, 58, 7.89, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1673, 83, 58, 8.62, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1674, 84, 58, 10.79, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1675, 82, 59, 8.29, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1676, 83, 59, 8.49, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1677, 84, 59, 9.83, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1678, 82, 60, 5.07, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1679, 83, 60, 3.82, 'present', NULL, 12, '2026-05-18 20:59:11', NULL),
(1680, 84, 60, 6.08, 'present', NULL, 12, '2026-05-18 20:59:12', NULL),
(1681, 85, 41, 9.83, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1682, 86, 41, 8.59, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1683, 87, 41, 11.21, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1684, 85, 42, 16.30, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1685, 86, 42, NULL, 'absent', NULL, 13, '2026-05-18 20:59:12', NULL),
(1686, 87, 42, 14.79, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1687, 85, 43, 8.08, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1688, 86, 43, 5.31, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1689, 87, 43, 6.12, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1690, 85, 44, 6.56, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1691, 86, 44, 10.35, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1692, 87, 44, 13.71, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1693, 85, 45, 4.88, 'present', NULL, 13, '2026-05-18 20:59:12', NULL),
(1694, 86, 45, 3.81, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1695, 87, 45, 3.38, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1696, 85, 46, 7.97, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1697, 86, 46, 10.66, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1698, 87, 46, 10.65, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1699, 85, 47, 6.98, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1700, 86, 47, 7.59, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1701, 87, 47, NULL, 'absent', NULL, 13, '2026-05-18 20:59:13', NULL),
(1702, 85, 48, 9.90, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1703, 86, 48, 15.26, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1704, 87, 48, 14.44, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1705, 85, 49, 7.14, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1706, 86, 49, 7.71, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1707, 87, 49, 14.01, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1708, 85, 50, 4.88, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1709, 86, 50, 8.63, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1710, 87, 50, 4.70, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1711, 85, 51, 13.79, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1712, 86, 51, 16.92, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1713, 87, 51, 14.51, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1714, 85, 52, 6.84, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1715, 86, 52, 12.60, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1716, 87, 52, 8.71, 'present', NULL, 13, '2026-05-18 20:59:13', NULL),
(1717, 85, 53, 7.33, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1718, 86, 53, 6.08, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1719, 87, 53, 9.48, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1720, 85, 54, 15.47, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1721, 86, 54, 15.99, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1722, 87, 54, 15.17, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1723, 85, 55, 4.63, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1724, 86, 55, 4.15, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1725, 87, 55, 1.03, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1726, 85, 56, 8.67, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1727, 86, 56, 7.56, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1728, 87, 56, 14.66, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1729, 85, 57, 17.39, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1730, 86, 57, 9.74, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1731, 87, 57, 12.19, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1732, 85, 58, 6.70, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1733, 86, 58, 9.36, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1734, 87, 58, NULL, 'absent', NULL, 13, '2026-05-18 20:59:14', NULL),
(1735, 85, 59, 7.26, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1736, 86, 59, 14.54, 'present', NULL, 13, '2026-05-18 20:59:14', NULL),
(1737, 87, 59, 12.99, 'present', NULL, 13, '2026-05-18 20:59:15', NULL),
(1738, 85, 60, 3.21, 'present', NULL, 13, '2026-05-18 20:59:15', NULL),
(1739, 86, 60, 2.86, 'present', NULL, 13, '2026-05-18 20:59:15', NULL),
(1740, 87, 60, 4.19, 'present', NULL, 13, '2026-05-18 20:59:15', NULL),
(1741, 88, 41, 9.05, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1742, 89, 41, 10.56, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1743, 90, 41, 13.25, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1744, 88, 42, 11.08, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1745, 89, 42, 10.03, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1746, 90, 42, 18.30, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1747, 88, 43, 11.68, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1748, 89, 43, 14.00, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1749, 90, 43, 11.87, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1750, 88, 44, 13.45, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1751, 89, 44, 8.79, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1752, 90, 44, 9.33, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1753, 88, 45, 7.07, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1754, 89, 45, 9.62, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1755, 90, 45, 6.69, 'present', NULL, 14, '2026-05-18 20:59:15', NULL),
(1756, 88, 46, NULL, 'absent', NULL, 14, '2026-05-18 20:59:16', NULL),
(1757, 89, 46, 8.06, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1758, 90, 46, 7.54, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1759, 88, 47, 6.59, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1760, 89, 47, 12.82, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1761, 90, 47, 8.62, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1762, 88, 48, 12.93, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1763, 89, 48, 13.69, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1764, 90, 48, 9.75, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1765, 88, 49, 12.34, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1766, 89, 49, 12.76, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1767, 90, 49, 13.09, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1768, 88, 50, 2.89, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1769, 89, 50, NULL, 'absent', NULL, 14, '2026-05-18 20:59:16', NULL),
(1770, 90, 50, 7.87, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1771, 88, 51, 12.15, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1772, 89, 51, 12.40, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1773, 90, 51, 9.81, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1774, 88, 52, 12.95, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1775, 89, 52, 5.01, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1776, 90, 52, 11.66, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1777, 88, 53, 9.50, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1778, 89, 53, 13.09, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1779, 90, 53, 5.35, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1780, 88, 54, 12.95, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1781, 89, 54, 15.74, 'present', NULL, 14, '2026-05-18 20:59:16', NULL),
(1782, 90, 54, 11.42, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1783, 88, 55, 9.79, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1784, 89, 55, 0.97, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1785, 90, 55, 3.90, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1786, 88, 56, 8.96, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1787, 89, 56, 9.66, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1788, 90, 56, 5.82, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1789, 88, 57, 14.16, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1790, 89, 57, 13.18, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1791, 90, 57, 9.96, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1792, 88, 58, 12.48, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1793, 89, 58, 13.99, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1794, 90, 58, 12.11, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1795, 88, 59, 9.97, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1796, 89, 59, 7.15, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1797, 90, 59, 13.32, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1798, 88, 60, 9.58, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1799, 89, 60, 2.75, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1800, 90, 60, 5.14, 'present', NULL, 14, '2026-05-18 20:59:17', NULL),
(1801, 91, 41, 11.80, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1802, 92, 41, 14.87, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1803, 93, 41, 8.84, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1804, 91, 42, 17.61, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1805, 92, 42, 14.79, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1806, 93, 42, 16.96, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1807, 91, 43, 11.16, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1808, 92, 43, 10.94, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1809, 93, 43, 12.21, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1810, 91, 44, 12.32, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1811, 92, 44, 14.40, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1812, 93, 44, 11.34, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1813, 91, 45, 4.41, 'present', NULL, 15, '2026-05-18 20:59:18', NULL),
(1814, 92, 45, 9.25, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1815, 93, 45, 3.70, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1816, 91, 46, 7.79, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1817, 92, 46, 14.26, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1818, 93, 46, 6.44, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1819, 91, 47, 8.58, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1820, 92, 47, NULL, 'absent', NULL, 15, '2026-05-18 20:59:19', NULL),
(1821, 93, 47, 13.94, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1822, 91, 48, 10.66, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1823, 92, 48, 18.12, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1824, 93, 48, 14.32, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1825, 91, 49, 11.93, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1826, 92, 49, 9.10, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1827, 93, 49, 7.29, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1828, 91, 50, 7.45, 'present', NULL, 15, '2026-05-18 20:59:19', NULL),
(1829, 92, 50, 0.11, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1830, 93, 50, 6.46, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1831, 91, 51, 9.43, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1832, 92, 51, 16.99, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1833, 93, 51, 11.59, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1834, 91, 52, 12.98, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1835, 92, 52, 12.45, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1836, 93, 52, 8.49, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1837, 91, 53, NULL, 'absent', NULL, 15, '2026-05-18 20:59:20', NULL),
(1838, 92, 53, 12.41, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1839, 93, 53, 12.23, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1840, 91, 54, 17.44, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1841, 92, 54, 14.20, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1842, 93, 54, 13.66, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1843, 91, 55, 5.28, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1844, 92, 55, NULL, 'absent', NULL, 15, '2026-05-18 20:59:20', NULL),
(1845, 93, 55, 1.74, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1846, 91, 56, 6.46, 'present', NULL, 15, '2026-05-18 20:59:20', NULL),
(1847, 92, 56, 9.70, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1848, 93, 56, 8.74, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1849, 91, 57, 17.23, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1850, 92, 57, 16.19, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1851, 93, 57, 13.11, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1852, 91, 58, 6.16, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1853, 92, 58, 5.82, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1854, 93, 58, 6.09, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1855, 91, 59, 14.34, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1856, 92, 59, 14.07, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1857, 93, 59, 9.22, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1858, 91, 60, 1.66, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1859, 92, 60, 3.87, 'present', NULL, 15, '2026-05-18 20:59:21', NULL),
(1860, 93, 60, 1.71, 'present', NULL, 15, '2026-05-18 20:59:21', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenu` text COLLATE utf8mb4_unicode_ci,
  `lien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_lu` (`user_id`,`lu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `dossier_paiement_id` int UNSIGNED NOT NULL,
  `tranche_id` int UNSIGNED DEFAULT NULL,
  `numero_recu` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(12,2) NOT NULL,
  `mode_paiement` enum('espece','cheque','virement','mobile_money','carte') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_transaction` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_paiement` date NOT NULL,
  `encaisse_par` int UNSIGNED NOT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `annule` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_recu` (`numero_recu`),
  KEY `dossier_paiement_id` (`dossier_paiement_id`),
  KEY `tranche_id` (`tranche_id`),
  KEY `encaisse_par` (`encaisse_par`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id`, `dossier_paiement_id`, `tranche_id`, `numero_recu`, `montant`, `mode_paiement`, `reference_transaction`, `date_paiement`, `encaisse_par`, `observation`, `annule`, `created_at`) VALUES
(1, 1, 1, 'LMA-2025-000001', 280000.00, 'virement', NULL, '2025-10-10', 3, NULL, 0, '2026-05-18 20:57:17'),
(2, 2, 4, 'LMA-2025-000002', 280000.00, 'espece', NULL, '2025-11-06', 3, NULL, 0, '2026-05-18 20:57:17'),
(3, 3, 7, 'LMA-2025-000003', 196000.00, 'espece', NULL, '2025-11-16', 3, NULL, 0, '2026-05-18 20:57:17'),
(4, 4, 10, 'LMA-2025-000004', 84000.00, 'espece', NULL, '2025-10-27', 3, NULL, 0, '2026-05-18 20:57:18'),
(5, 5, 13, 'LMA-2025-000005', 140000.00, 'virement', NULL, '2025-10-29', 3, NULL, 0, '2026-05-18 20:57:18'),
(6, 6, 16, 'LMA-2025-000006', 168000.00, 'espece', NULL, '2025-09-26', 3, NULL, 0, '2026-05-18 20:57:18'),
(7, 10, 28, 'LMA-2025-000010', 280000.00, 'espece', NULL, '2025-11-07', 3, NULL, 0, '2026-05-18 20:57:19'),
(8, 11, 31, 'LMA-2025-000011', 280000.00, 'mobile_money', NULL, '2025-10-30', 3, NULL, 0, '2026-05-18 20:57:20'),
(9, 12, 34, 'LMA-2025-000012', 280000.00, 'mobile_money', NULL, '2025-09-29', 3, NULL, 0, '2026-05-18 20:57:20'),
(10, 13, 37, 'LMA-2025-000013', 196000.00, 'mobile_money', NULL, '2025-11-17', 3, NULL, 0, '2026-05-18 20:57:20'),
(11, 14, 40, 'LMA-2025-000014', 84000.00, 'espece', NULL, '2025-09-14', 3, NULL, 0, '2026-05-18 20:57:21'),
(12, 15, 43, 'LMA-2025-000015', 140000.00, 'virement', NULL, '2025-11-04', 3, NULL, 0, '2026-05-18 20:57:21'),
(13, 16, 46, 'LMA-2025-000016', 168000.00, 'mobile_money', NULL, '2025-11-28', 3, NULL, 0, '2026-05-18 20:57:22'),
(14, 20, 58, 'LMA-2025-000020', 280000.00, 'mobile_money', NULL, '2025-11-21', 3, NULL, 0, '2026-05-18 20:57:23'),
(15, 21, 61, 'LMA-2025-000021', 300000.00, 'espece', NULL, '2025-09-29', 3, NULL, 0, '2026-05-18 20:57:24'),
(16, 22, 64, 'LMA-2025-000022', 300000.00, 'virement', NULL, '2025-11-25', 3, NULL, 0, '2026-05-18 20:57:24'),
(17, 23, 67, 'LMA-2025-000023', 210000.00, 'espece', NULL, '2025-10-10', 3, NULL, 0, '2026-05-18 20:57:25'),
(18, 24, 70, 'LMA-2025-000024', 90000.00, 'espece', NULL, '2025-11-13', 3, NULL, 0, '2026-05-18 20:57:25'),
(19, 25, 73, 'LMA-2025-000025', 150000.00, 'virement', NULL, '2025-11-20', 3, NULL, 0, '2026-05-18 20:57:26'),
(20, 26, 76, 'LMA-2025-000026', 180000.00, 'mobile_money', NULL, '2025-09-06', 3, NULL, 0, '2026-05-18 20:57:26'),
(21, 30, 88, 'LMA-2025-000030', 300000.00, 'virement', NULL, '2025-09-17', 3, NULL, 0, '2026-05-18 20:57:27'),
(22, 31, 91, 'LMA-2025-000031', 300000.00, 'mobile_money', NULL, '2025-09-17', 3, NULL, 0, '2026-05-18 20:57:28'),
(23, 32, 94, 'LMA-2025-000032', 300000.00, 'mobile_money', NULL, '2025-11-29', 3, NULL, 0, '2026-05-18 20:57:29'),
(24, 33, 97, 'LMA-2025-000033', 210000.00, 'virement', NULL, '2025-11-21', 3, NULL, 0, '2026-05-18 20:57:29'),
(25, 34, 100, 'LMA-2025-000034', 90000.00, 'virement', NULL, '2025-10-17', 3, NULL, 0, '2026-05-18 20:57:30'),
(26, 35, 103, 'LMA-2025-000035', 150000.00, 'espece', NULL, '2025-09-23', 3, NULL, 0, '2026-05-18 20:57:30'),
(27, 36, 106, 'LMA-2025-000036', 180000.00, 'mobile_money', NULL, '2025-09-10', 3, NULL, 0, '2026-05-18 20:57:30'),
(28, 40, 118, 'LMA-2025-000040', 300000.00, 'espece', NULL, '2025-09-19', 3, NULL, 0, '2026-05-18 20:57:31'),
(29, 41, 121, 'LMA-2025-000041', 320000.00, 'virement', NULL, '2025-09-08', 3, NULL, 0, '2026-05-18 20:57:32'),
(30, 42, 124, 'LMA-2025-000042', 320000.00, 'virement', NULL, '2025-09-14', 3, NULL, 0, '2026-05-18 20:57:32'),
(31, 43, 127, 'LMA-2025-000043', 224000.00, 'virement', NULL, '2025-09-27', 3, NULL, 0, '2026-05-18 20:57:32'),
(32, 44, 130, 'LMA-2025-000044', 96000.00, 'espece', NULL, '2025-10-06', 3, NULL, 0, '2026-05-18 20:57:33'),
(33, 45, 133, 'LMA-2025-000045', 160000.00, 'virement', NULL, '2025-09-07', 3, NULL, 0, '2026-05-18 20:57:33'),
(34, 46, 136, 'LMA-2025-000046', 192000.00, 'virement', NULL, '2025-10-19', 3, NULL, 0, '2026-05-18 20:57:33'),
(35, 50, 148, 'LMA-2025-000050', 320000.00, 'mobile_money', NULL, '2025-11-22', 3, NULL, 0, '2026-05-18 20:57:34'),
(36, 51, 151, 'LMA-2025-000051', 320000.00, 'espece', NULL, '2025-09-03', 3, NULL, 0, '2026-05-18 20:57:35'),
(37, 52, 154, 'LMA-2025-000052', 320000.00, 'virement', NULL, '2025-10-24', 3, NULL, 0, '2026-05-18 20:57:35'),
(38, 53, 157, 'LMA-2025-000053', 224000.00, 'espece', NULL, '2025-09-18', 3, NULL, 0, '2026-05-18 20:57:35'),
(39, 54, 160, 'LMA-2025-000054', 96000.00, 'espece', NULL, '2025-09-26', 3, NULL, 0, '2026-05-18 20:57:36'),
(40, 55, 163, 'LMA-2025-000055', 160000.00, 'virement', NULL, '2025-10-02', 3, NULL, 0, '2026-05-18 20:57:36'),
(41, 56, 166, 'LMA-2025-000056', 192000.00, 'espece', NULL, '2025-10-26', 3, NULL, 0, '2026-05-18 20:57:36'),
(42, 60, 178, 'LMA-2025-000060', 320000.00, 'mobile_money', NULL, '2025-10-29', 3, NULL, 0, '2026-05-18 20:57:37');

-- --------------------------------------------------------

--
-- Structure de la table `parent_eleve`
--

DROP TABLE IF EXISTS `parent_eleve`;
CREATE TABLE IF NOT EXISTS `parent_eleve` (
  `user_id` int UNSIGNED NOT NULL,
  `eleve_id` int UNSIGNED NOT NULL,
  `lien` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'parent',
  PRIMARY KEY (`user_id`,`eleve_id`),
  KEY `eleve_id` (`eleve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `periodes`
--

DROP TABLE IF EXISTS `periodes`;
CREATE TABLE IF NOT EXISTS `periodes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('trimestre','semestre','annuel') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `ordre` tinyint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `periodes`
--

INSERT INTO `periodes` (`id`, `annee_scolaire_id`, `nom`, `type`, `date_debut`, `date_fin`, `ordre`) VALUES
(1, 1, 'Trimestre 1', 'trimestre', '2025-09-02', '2025-12-20', 1),
(2, 1, 'Trimestre 2', 'trimestre', '2026-01-05', '2026-03-27', 2),
(3, 1, 'Trimestre 3', 'trimestre', '2026-04-06', '2026-07-10', 3);

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_perm` (`module`,`action`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `action`, `label`) VALUES
(1, 'dashboard', 'voir', 'Voir le tableau de bord'),
(2, 'etablissements', 'voir', 'Voir les établissements'),
(3, 'etablissements', 'creer', 'Créer un établissement'),
(4, 'etablissements', 'modifier', 'Modifier un établissement'),
(5, 'etablissements', 'supprimer', 'Supprimer un établissement'),
(6, 'eleves', 'voir', 'Voir les élèves'),
(7, 'eleves', 'creer', 'Inscrire un élève'),
(8, 'eleves', 'modifier', 'Modifier un élève'),
(9, 'eleves', 'supprimer', 'Supprimer un élève'),
(10, 'eleves', 'exporter', 'Exporter la liste des élèves'),
(11, 'personnel', 'voir', 'Voir le personnel'),
(12, 'personnel', 'creer', 'Créer un membre du personnel'),
(13, 'personnel', 'modifier', 'Modifier un membre du personnel'),
(14, 'personnel', 'supprimer', 'Supprimer un membre du personnel'),
(15, 'classes', 'voir', 'Voir les classes'),
(16, 'classes', 'creer', 'Créer une classe'),
(17, 'classes', 'modifier', 'Modifier une classe'),
(18, 'classes', 'supprimer', 'Supprimer une classe'),
(19, 'notes', 'voir', 'Voir les notes'),
(20, 'notes', 'saisir', 'Saisir des notes'),
(21, 'notes', 'modifier', 'Modifier des notes'),
(22, 'notes', 'valider', 'Valider/fermer une période'),
(23, 'bulletins', 'voir', 'Voir les bulletins'),
(24, 'bulletins', 'generer', 'Générer les bulletins PDF'),
(25, 'presences', 'voir', 'Voir les présences'),
(26, 'presences', 'saisir', 'Faire l\'appel'),
(27, 'presences', 'modifier', 'Modifier une présence'),
(28, 'paiements', 'voir', 'Voir les paiements'),
(29, 'paiements', 'encaisser', 'Encaisser un paiement'),
(30, 'paiements', 'annuler', 'Annuler un paiement'),
(31, 'paiements', 'exporter', 'Exporter les paiements'),
(32, 'comptabilite', 'voir', 'Voir la comptabilité'),
(33, 'comptabilite', 'creer', 'Enregistrer une transaction'),
(34, 'comptabilite', 'exporter', 'Exporter les rapports financiers'),
(35, 'examens', 'voir', 'Voir les examens'),
(36, 'examens', 'creer', 'Créer un examen'),
(37, 'examens', 'modifier', 'Modifier un examen'),
(38, 'bibliotheque', 'voir', 'Voir la bibliothèque'),
(39, 'bibliotheque', 'gerer', 'Gérer les emprunts'),
(40, 'emploi_temps', 'voir', 'Voir l\'emploi du temps'),
(41, 'emploi_temps', 'modifier', 'Modifier l\'emploi du temps'),
(42, 'communication', 'voir', 'Voir les annonces'),
(43, 'communication', 'creer', 'Publier une annonce'),
(44, 'rapports', 'voir', 'Voir les rapports'),
(45, 'rapports', 'exporter', 'Exporter les rapports'),
(46, 'parametres', 'voir', 'Voir les paramètres'),
(47, 'parametres', 'modifier', 'Modifier les paramètres'),
(48, 'users', 'voir', 'Voir les utilisateurs'),
(49, 'users', 'creer', 'Créer un utilisateur'),
(50, 'users', 'modifier', 'Modifier un utilisateur'),
(51, 'users', 'supprimer', 'Supprimer un utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

DROP TABLE IF EXISTS `personnel`;
CREATE TABLE IF NOT EXISTS `personnel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `etablissement_id` int UNSIGNED NOT NULL,
  `matricule` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('enseignant','administratif','direction','surveillant','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialite` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diplome` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_embauche` date DEFAULT NULL,
  `statut_contrat` enum('permanent','contractuel','vacataire','stagiaire') COLLATE utf8mb4_unicode_ci DEFAULT 'permanent',
  `salaire_base` decimal(12,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `matricule` (`matricule`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id`, `user_id`, `etablissement_id`, `matricule`, `type`, `specialite`, `diplome`, `date_embauche`, `statut_contrat`, `salaire_base`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, 2, 'PERS0006', 'enseignant', 'Mathématiques et Physique', NULL, '2018-01-20', 'contractuel', NULL, '2026-05-18 20:57:10', NULL, NULL),
(2, 7, 2, 'PERS0007', 'enseignant', 'Lettres modernes', NULL, '2019-11-23', 'vacataire', NULL, '2026-05-18 20:57:11', NULL, NULL),
(3, 8, 2, 'PERS0008', 'enseignant', 'Sciences Physiques', NULL, '2022-07-07', 'contractuel', NULL, '2026-05-18 20:57:11', NULL, NULL),
(4, 9, 2, 'PERS0009', 'enseignant', 'Sciences de la Vie', NULL, '2021-01-26', 'contractuel', NULL, '2026-05-18 20:57:11', NULL, NULL),
(5, 10, 2, 'PERS0010', 'enseignant', 'Anglais Langue Étrangère', NULL, '2017-04-13', 'contractuel', NULL, '2026-05-18 20:57:12', NULL, NULL),
(6, 11, 2, 'PERS0011', 'enseignant', 'Histoire-Géographie', NULL, '2019-01-09', 'contractuel', NULL, '2026-05-18 20:57:12', NULL, NULL),
(7, 12, 2, 'PERS0012', 'enseignant', 'Philosophie', NULL, '2017-07-20', 'vacataire', NULL, '2026-05-18 20:57:12', NULL, NULL),
(8, 13, 2, 'PERS0013', 'enseignant', 'Économie et Sciences Sociales', NULL, '2022-09-08', 'vacataire', NULL, '2026-05-18 20:57:13', NULL, NULL),
(9, 14, 2, 'PERS0014', 'enseignant', 'Éducation Physique', NULL, '2016-09-12', 'permanent', NULL, '2026-05-18 20:57:13', NULL, NULL),
(10, 15, 2, 'PERS0015', 'enseignant', 'Informatique', NULL, '2016-04-26', 'permanent', NULL, '2026-05-18 20:57:13', NULL, NULL),
(11, 3, 2, 'PERS-ADM-001', 'administratif', 'Administration', NULL, '2020-09-01', 'permanent', NULL, '2026-05-18 20:57:13', NULL, NULL),
(12, 4, 2, 'PERS-DIR-001', 'direction', 'Direction générale', NULL, '2020-09-01', 'permanent', NULL, '2026-05-18 20:57:13', NULL, NULL),
(13, 5, 2, 'PERS-COM-001', 'administratif', 'Comptabilité', NULL, '2020-09-01', 'permanent', NULL, '2026-05-18 20:57:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `planning_examens`
--

DROP TABLE IF EXISTS `planning_examens`;
CREATE TABLE IF NOT EXISTS `planning_examens` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `examen_id` int UNSIGNED NOT NULL,
  `matiere_id` int UNSIGNED NOT NULL,
  `classe_id` int UNSIGNED DEFAULT NULL,
  `salle_id` int UNSIGNED DEFAULT NULL,
  `date_epreuve` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `duree_minutes` smallint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `examen_id` (`examen_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `classe_id` (`classe_id`),
  KEY `salle_id` (`salle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `planning_examens`
--

INSERT INTO `planning_examens` (`id`, `examen_id`, `matiere_id`, `classe_id`, `salle_id`, `date_epreuve`, `heure_debut`, `heure_fin`, `duree_minutes`) VALUES
(1, 1, 1, 3, 1, '2025-12-08', '07:30:00', '10:30:00', NULL),
(2, 1, 8, 3, 1, '2025-12-09', '07:30:00', '10:30:00', NULL),
(3, 1, 7, 3, 1, '2025-12-10', '07:30:00', '10:30:00', NULL),
(4, 1, 2, 3, 1, '2025-12-11', '07:30:00', '11:30:00', NULL),
(5, 1, 4, 3, 1, '2025-12-12', '07:30:00', '10:30:00', NULL),
(6, 1, 5, 3, 1, '2025-12-15', '07:30:00', '09:30:00', NULL),
(7, 1, 3, 3, 1, '2025-12-16', '07:30:00', '11:30:00', NULL),
(8, 1, 9, 3, 1, '2025-12-17', '07:30:00', '10:00:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `seance_id` int UNSIGNED NOT NULL,
  `eleve_id` int UNSIGNED NOT NULL,
  `statut` enum('present','absent','retard','excuse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifie` tinyint(1) DEFAULT '0',
  `heure_arrivee` time DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_presence` (`seance_id`,`eleve_id`),
  KEY `eleve_id` (`eleve_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `presences`
--

INSERT INTO `presences` (`id`, `seance_id`, `eleve_id`, `statut`, `motif`, `notifie`, `heure_arrivee`, `created_at`) VALUES
(1, 1, 1, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:21'),
(2, 1, 2, 'present', NULL, 0, NULL, '2026-05-18 20:59:21'),
(3, 1, 3, 'present', NULL, 0, NULL, '2026-05-18 20:59:21'),
(4, 1, 4, 'retard', NULL, 0, NULL, '2026-05-18 20:59:21'),
(5, 1, 5, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(6, 1, 6, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(7, 1, 7, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(8, 1, 8, 'retard', NULL, 0, NULL, '2026-05-18 20:59:22'),
(9, 1, 9, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(10, 1, 10, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:22'),
(11, 1, 11, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(12, 1, 12, 'absent', NULL, 0, NULL, '2026-05-18 20:59:22'),
(13, 1, 13, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(14, 1, 14, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(15, 1, 15, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:22'),
(16, 1, 16, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(17, 1, 17, 'absent', NULL, 0, NULL, '2026-05-18 20:59:22'),
(18, 1, 18, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(19, 1, 19, 'absent', NULL, 0, NULL, '2026-05-18 20:59:22'),
(20, 1, 20, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(21, 2, 1, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(22, 2, 2, 'present', NULL, 0, NULL, '2026-05-18 20:59:22'),
(23, 2, 3, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(24, 2, 4, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:23'),
(25, 2, 5, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(26, 2, 6, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(27, 2, 7, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(28, 2, 8, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(29, 2, 9, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(30, 2, 10, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(31, 2, 11, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(32, 2, 12, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:23'),
(33, 2, 13, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(34, 2, 14, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:23'),
(35, 2, 15, 'retard', NULL, 0, NULL, '2026-05-18 20:59:23'),
(36, 2, 16, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(37, 2, 17, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(38, 2, 18, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(39, 2, 19, 'present', NULL, 0, NULL, '2026-05-18 20:59:23'),
(40, 2, 20, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(41, 3, 1, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(42, 3, 2, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(43, 3, 3, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(44, 3, 4, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(45, 3, 5, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(46, 3, 6, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(47, 3, 7, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(48, 3, 8, 'absent', NULL, 0, NULL, '2026-05-18 20:59:24'),
(49, 3, 9, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:24'),
(50, 3, 10, 'present', NULL, 0, NULL, '2026-05-18 20:59:24'),
(51, 3, 11, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:25'),
(52, 3, 12, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(53, 3, 13, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(54, 3, 14, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(55, 3, 15, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(56, 3, 16, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(57, 3, 17, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:25'),
(58, 3, 18, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(59, 3, 19, 'present', NULL, 0, NULL, '2026-05-18 20:59:25'),
(60, 3, 20, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:26'),
(61, 4, 1, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(62, 4, 2, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(63, 4, 3, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(64, 4, 4, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(65, 4, 5, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:26'),
(66, 4, 6, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(67, 4, 7, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(68, 4, 8, 'retard', NULL, 0, NULL, '2026-05-18 20:59:26'),
(69, 4, 9, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(70, 4, 10, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(71, 4, 11, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(72, 4, 12, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:26'),
(73, 4, 13, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(74, 4, 14, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(75, 4, 15, 'present', NULL, 0, NULL, '2026-05-18 20:59:26'),
(76, 4, 16, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(77, 4, 17, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(78, 4, 18, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(79, 4, 19, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(80, 4, 20, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(81, 5, 1, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:27'),
(82, 5, 2, 'retard', NULL, 0, NULL, '2026-05-18 20:59:27'),
(83, 5, 3, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(84, 5, 4, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(85, 5, 5, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(86, 5, 6, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(87, 5, 7, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(88, 5, 8, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(89, 5, 9, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(90, 5, 10, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(91, 5, 11, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:27'),
(92, 5, 12, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(93, 5, 13, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(94, 5, 14, 'present', NULL, 0, NULL, '2026-05-18 20:59:27'),
(95, 5, 15, 'retard', NULL, 0, NULL, '2026-05-18 20:59:27'),
(96, 5, 16, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:28'),
(97, 5, 17, 'retard', NULL, 0, NULL, '2026-05-18 20:59:28'),
(98, 5, 18, 'present', NULL, 0, NULL, '2026-05-18 20:59:28'),
(99, 5, 19, 'excuse', NULL, 0, NULL, '2026-05-18 20:59:28'),
(100, 5, 20, 'present', NULL, 0, NULL, '2026-05-18 20:59:28');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `niveau` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `nom`, `label`, `niveau`, `created_at`) VALUES
(1, 'super_admin', 'Super Administrateur', 100, '2026-05-18 17:17:13'),
(2, 'admin', 'Administrateur Établissement', 80, '2026-05-18 17:17:13'),
(3, 'directeur', 'Directeur', 70, '2026-05-18 17:17:13'),
(4, 'enseignant', 'Enseignant', 40, '2026-05-18 17:17:13'),
(5, 'parent', 'Parent / Tuteur', 20, '2026-05-18 17:17:13'),
(6, 'eleve', 'Élève / Étudiant', 10, '2026-05-18 17:17:13');

-- --------------------------------------------------------

--
-- Structure de la table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` tinyint UNSIGNED NOT NULL,
  `permission_id` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(1, 2),
(2, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 6),
(3, 6),
(1, 7),
(2, 7),
(1, 8),
(2, 8),
(1, 9),
(2, 9),
(1, 10),
(2, 10),
(3, 10),
(1, 11),
(2, 11),
(3, 11),
(1, 12),
(2, 12),
(1, 13),
(2, 13),
(1, 14),
(2, 14),
(1, 15),
(2, 15),
(3, 15),
(1, 16),
(2, 16),
(1, 17),
(2, 17),
(1, 18),
(2, 18),
(1, 19),
(2, 19),
(3, 19),
(4, 19),
(6, 19),
(1, 20),
(2, 20),
(4, 20),
(1, 21),
(2, 21),
(4, 21),
(1, 22),
(2, 22),
(1, 23),
(2, 23),
(3, 23),
(4, 23),
(5, 23),
(6, 23),
(1, 24),
(2, 24),
(3, 24),
(1, 25),
(2, 25),
(3, 25),
(4, 25),
(5, 25),
(1, 26),
(2, 26),
(4, 26),
(1, 27),
(2, 27),
(1, 28),
(2, 28),
(3, 28),
(5, 28),
(1, 29),
(2, 29),
(1, 30),
(2, 30),
(1, 31),
(2, 31),
(3, 31),
(1, 32),
(2, 32),
(1, 33),
(2, 33),
(1, 34),
(2, 34),
(1, 35),
(2, 35),
(3, 35),
(1, 36),
(2, 36),
(1, 37),
(2, 37),
(1, 38),
(2, 38),
(3, 38),
(1, 39),
(2, 39),
(1, 40),
(2, 40),
(3, 40),
(4, 40),
(6, 40),
(1, 41),
(2, 41),
(1, 42),
(2, 42),
(3, 42),
(4, 42),
(5, 42),
(6, 42),
(1, 43),
(2, 43),
(1, 44),
(2, 44),
(3, 44),
(1, 45),
(2, 45),
(3, 45),
(1, 46),
(2, 46),
(1, 47),
(2, 47),
(1, 48),
(2, 48),
(1, 49),
(2, 49),
(1, 50),
(2, 50),
(1, 51),
(2, 51);

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

DROP TABLE IF EXISTS `salles`;
CREATE TABLE IF NOT EXISTS `salles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite` smallint UNSIGNED DEFAULT NULL,
  `type` enum('cours','labo','informatique','sport','amphi') COLLATE utf8mb4_unicode_ci DEFAULT 'cours',
  `disponible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `etablissement_id`, `nom`, `capacite`, `type`, `disponible`) VALUES
(1, 2, 'Salle A101', 40, 'cours', 1),
(2, 2, 'Salle A102', 40, 'cours', 1),
(3, 2, 'Salle B201', 40, 'cours', 1),
(4, 2, 'Salle B202', 40, 'cours', 1),
(5, 2, 'Laboratoire', 25, 'labo', 1),
(6, 2, 'Salle Informatique', 25, 'informatique', 1);

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

DROP TABLE IF EXISTS `seances`;
CREATE TABLE IF NOT EXISTS `seances` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `emploi_du_temps_id` int UNSIGNED DEFAULT NULL,
  `affectation_id` int UNSIGNED NOT NULL,
  `date_seance` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `salle_id` int UNSIGNED DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `appel_fait` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `emploi_du_temps_id` (`emploi_du_temps_id`),
  KEY `affectation_id` (`affectation_id`),
  KEY `salle_id` (`salle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `seances`
--

INSERT INTO `seances` (`id`, `emploi_du_temps_id`, `affectation_id`, `date_seance`, `heure_debut`, `heure_fin`, `salle_id`, `observation`, `appel_fait`, `created_at`) VALUES
(1, NULL, 1, '2025-10-06', '07:30:00', '08:30:00', 1, NULL, 1, '2026-05-18 20:59:21'),
(2, NULL, 1, '2025-10-13', '07:30:00', '08:30:00', 1, NULL, 1, '2026-05-18 20:59:22'),
(3, NULL, 1, '2025-10-20', '07:30:00', '08:30:00', 1, NULL, 1, '2026-05-18 20:59:24'),
(4, NULL, 1, '2025-11-03', '07:30:00', '08:30:00', 1, NULL, 1, '2026-05-18 20:59:26'),
(5, NULL, 1, '2025-11-10', '07:30:00', '08:30:00', 1, NULL, 1, '2026-05-18 20:59:27'),
(6, NULL, 16, '2026-05-19', '08:00:00', '09:00:00', NULL, NULL, 0, '2026-05-19 11:08:10');

-- --------------------------------------------------------

--
-- Structure de la table `surveillances_examen`
--

DROP TABLE IF EXISTS `surveillances_examen`;
CREATE TABLE IF NOT EXISTS `surveillances_examen` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `planning_examen_id` int UNSIGNED NOT NULL,
  `personnel_id` int UNSIGNED NOT NULL,
  `role_surveillance` enum('surveillant','chef_salle','correcteur') COLLATE utf8mb4_unicode_ci DEFAULT 'surveillant',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_surv` (`planning_examen_id`,`personnel_id`),
  KEY `personnel_id` (`personnel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

DROP TABLE IF EXISTS `tarifs`;
CREATE TABLE IF NOT EXISTS `tarifs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `niveau_id` int UNSIGNED DEFAULT NULL,
  `type_frais_id` int UNSIGNED NOT NULL,
  `montant` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tarif` (`etablissement_id`,`annee_scolaire_id`,`niveau_id`,`type_frais_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`),
  KEY `niveau_id` (`niveau_id`),
  KEY `type_frais_id` (`type_frais_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tranches_paiement`
--

DROP TABLE IF EXISTS `tranches_paiement`;
CREATE TABLE IF NOT EXISTS `tranches_paiement` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `dossier_paiement_id` int UNSIGNED NOT NULL,
  `type_frais_id` int UNSIGNED NOT NULL,
  `numero_tranche` tinyint UNSIGNED NOT NULL,
  `montant_attendu` decimal(12,2) NOT NULL,
  `date_echeance` date NOT NULL,
  `statut` enum('en_attente','paye','en_retard') COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  PRIMARY KEY (`id`),
  KEY `dossier_paiement_id` (`dossier_paiement_id`),
  KEY `type_frais_id` (`type_frais_id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tranches_paiement`
--

INSERT INTO `tranches_paiement` (`id`, `dossier_paiement_id`, `type_frais_id`, `numero_tranche`, `montant_attendu`, `date_echeance`, `statut`) VALUES
(1, 1, 1, 1, 112000.00, '2025-10-31', 'paye'),
(2, 1, 1, 2, 84000.00, '2026-01-31', 'paye'),
(3, 1, 1, 3, 84000.00, '2026-04-30', 'paye'),
(4, 2, 1, 1, 112000.00, '2025-10-31', 'paye'),
(5, 2, 1, 2, 84000.00, '2026-01-31', 'paye'),
(6, 2, 1, 3, 84000.00, '2026-04-30', 'paye'),
(7, 3, 1, 1, 112000.00, '2025-10-31', 'paye'),
(8, 3, 1, 2, 84000.00, '2026-01-31', 'paye'),
(9, 3, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(10, 4, 1, 1, 112000.00, '2025-10-31', 'paye'),
(11, 4, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(12, 4, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(13, 5, 1, 1, 112000.00, '2025-10-31', 'paye'),
(14, 5, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(15, 5, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(16, 6, 1, 1, 112000.00, '2025-10-31', 'paye'),
(17, 6, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(18, 6, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(19, 7, 1, 1, 112000.00, '2025-10-31', 'en_attente'),
(20, 7, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(21, 7, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(22, 8, 1, 1, 112000.00, '2025-10-31', 'en_attente'),
(23, 8, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(24, 8, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(25, 9, 1, 1, 112000.00, '2025-10-31', 'en_attente'),
(26, 9, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(27, 9, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(28, 10, 1, 1, 112000.00, '2025-10-31', 'paye'),
(29, 10, 1, 2, 84000.00, '2026-01-31', 'paye'),
(30, 10, 1, 3, 84000.00, '2026-04-30', 'paye'),
(31, 11, 1, 1, 112000.00, '2025-10-31', 'paye'),
(32, 11, 1, 2, 84000.00, '2026-01-31', 'paye'),
(33, 11, 1, 3, 84000.00, '2026-04-30', 'paye'),
(34, 12, 1, 1, 112000.00, '2025-10-31', 'paye'),
(35, 12, 1, 2, 84000.00, '2026-01-31', 'paye'),
(36, 12, 1, 3, 84000.00, '2026-04-30', 'paye'),
(37, 13, 1, 1, 112000.00, '2025-10-31', 'paye'),
(38, 13, 1, 2, 84000.00, '2026-01-31', 'paye'),
(39, 13, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(40, 14, 1, 1, 112000.00, '2025-10-31', 'paye'),
(41, 14, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(42, 14, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(43, 15, 1, 1, 112000.00, '2025-10-31', 'paye'),
(44, 15, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(45, 15, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(46, 16, 1, 1, 112000.00, '2025-10-31', 'paye'),
(47, 16, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(48, 16, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(49, 17, 1, 1, 112000.00, '2025-10-31', 'en_attente'),
(50, 17, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(51, 17, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(52, 18, 1, 1, 112000.00, '2025-10-31', 'en_attente'),
(53, 18, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(54, 18, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(55, 19, 1, 1, 112000.00, '2025-10-31', 'en_attente'),
(56, 19, 1, 2, 84000.00, '2026-01-31', 'en_attente'),
(57, 19, 1, 3, 84000.00, '2026-04-30', 'en_attente'),
(58, 20, 1, 1, 112000.00, '2025-10-31', 'paye'),
(59, 20, 1, 2, 84000.00, '2026-01-31', 'paye'),
(60, 20, 1, 3, 84000.00, '2026-04-30', 'paye'),
(61, 21, 1, 1, 120000.00, '2025-10-31', 'paye'),
(62, 21, 1, 2, 90000.00, '2026-01-31', 'paye'),
(63, 21, 1, 3, 90000.00, '2026-04-30', 'paye'),
(64, 22, 1, 1, 120000.00, '2025-10-31', 'paye'),
(65, 22, 1, 2, 90000.00, '2026-01-31', 'paye'),
(66, 22, 1, 3, 90000.00, '2026-04-30', 'paye'),
(67, 23, 1, 1, 120000.00, '2025-10-31', 'paye'),
(68, 23, 1, 2, 90000.00, '2026-01-31', 'paye'),
(69, 23, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(70, 24, 1, 1, 120000.00, '2025-10-31', 'paye'),
(71, 24, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(72, 24, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(73, 25, 1, 1, 120000.00, '2025-10-31', 'paye'),
(74, 25, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(75, 25, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(76, 26, 1, 1, 120000.00, '2025-10-31', 'paye'),
(77, 26, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(78, 26, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(79, 27, 1, 1, 120000.00, '2025-10-31', 'en_attente'),
(80, 27, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(81, 27, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(82, 28, 1, 1, 120000.00, '2025-10-31', 'en_attente'),
(83, 28, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(84, 28, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(85, 29, 1, 1, 120000.00, '2025-10-31', 'en_attente'),
(86, 29, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(87, 29, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(88, 30, 1, 1, 120000.00, '2025-10-31', 'paye'),
(89, 30, 1, 2, 90000.00, '2026-01-31', 'paye'),
(90, 30, 1, 3, 90000.00, '2026-04-30', 'paye'),
(91, 31, 1, 1, 120000.00, '2025-10-31', 'paye'),
(92, 31, 1, 2, 90000.00, '2026-01-31', 'paye'),
(93, 31, 1, 3, 90000.00, '2026-04-30', 'paye'),
(94, 32, 1, 1, 120000.00, '2025-10-31', 'paye'),
(95, 32, 1, 2, 90000.00, '2026-01-31', 'paye'),
(96, 32, 1, 3, 90000.00, '2026-04-30', 'paye'),
(97, 33, 1, 1, 120000.00, '2025-10-31', 'paye'),
(98, 33, 1, 2, 90000.00, '2026-01-31', 'paye'),
(99, 33, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(100, 34, 1, 1, 120000.00, '2025-10-31', 'paye'),
(101, 34, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(102, 34, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(103, 35, 1, 1, 120000.00, '2025-10-31', 'paye'),
(104, 35, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(105, 35, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(106, 36, 1, 1, 120000.00, '2025-10-31', 'paye'),
(107, 36, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(108, 36, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(109, 37, 1, 1, 120000.00, '2025-10-31', 'en_attente'),
(110, 37, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(111, 37, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(112, 38, 1, 1, 120000.00, '2025-10-31', 'en_attente'),
(113, 38, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(114, 38, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(115, 39, 1, 1, 120000.00, '2025-10-31', 'en_attente'),
(116, 39, 1, 2, 90000.00, '2026-01-31', 'en_attente'),
(117, 39, 1, 3, 90000.00, '2026-04-30', 'en_attente'),
(118, 40, 1, 1, 120000.00, '2025-10-31', 'paye'),
(119, 40, 1, 2, 90000.00, '2026-01-31', 'paye'),
(120, 40, 1, 3, 90000.00, '2026-04-30', 'paye'),
(121, 41, 1, 1, 128000.00, '2025-10-31', 'paye'),
(122, 41, 1, 2, 96000.00, '2026-01-31', 'paye'),
(123, 41, 1, 3, 96000.00, '2026-04-30', 'paye'),
(124, 42, 1, 1, 128000.00, '2025-10-31', 'paye'),
(125, 42, 1, 2, 96000.00, '2026-01-31', 'paye'),
(126, 42, 1, 3, 96000.00, '2026-04-30', 'paye'),
(127, 43, 1, 1, 128000.00, '2025-10-31', 'paye'),
(128, 43, 1, 2, 96000.00, '2026-01-31', 'paye'),
(129, 43, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(130, 44, 1, 1, 128000.00, '2025-10-31', 'paye'),
(131, 44, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(132, 44, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(133, 45, 1, 1, 128000.00, '2025-10-31', 'paye'),
(134, 45, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(135, 45, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(136, 46, 1, 1, 128000.00, '2025-10-31', 'paye'),
(137, 46, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(138, 46, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(139, 47, 1, 1, 128000.00, '2025-10-31', 'en_attente'),
(140, 47, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(141, 47, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(142, 48, 1, 1, 128000.00, '2025-10-31', 'en_attente'),
(143, 48, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(144, 48, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(145, 49, 1, 1, 128000.00, '2025-10-31', 'en_attente'),
(146, 49, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(147, 49, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(148, 50, 1, 1, 128000.00, '2025-10-31', 'paye'),
(149, 50, 1, 2, 96000.00, '2026-01-31', 'paye'),
(150, 50, 1, 3, 96000.00, '2026-04-30', 'paye'),
(151, 51, 1, 1, 128000.00, '2025-10-31', 'paye'),
(152, 51, 1, 2, 96000.00, '2026-01-31', 'paye'),
(153, 51, 1, 3, 96000.00, '2026-04-30', 'paye'),
(154, 52, 1, 1, 128000.00, '2025-10-31', 'paye'),
(155, 52, 1, 2, 96000.00, '2026-01-31', 'paye'),
(156, 52, 1, 3, 96000.00, '2026-04-30', 'paye'),
(157, 53, 1, 1, 128000.00, '2025-10-31', 'paye'),
(158, 53, 1, 2, 96000.00, '2026-01-31', 'paye'),
(159, 53, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(160, 54, 1, 1, 128000.00, '2025-10-31', 'paye'),
(161, 54, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(162, 54, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(163, 55, 1, 1, 128000.00, '2025-10-31', 'paye'),
(164, 55, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(165, 55, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(166, 56, 1, 1, 128000.00, '2025-10-31', 'paye'),
(167, 56, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(168, 56, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(169, 57, 1, 1, 128000.00, '2025-10-31', 'en_attente'),
(170, 57, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(171, 57, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(172, 58, 1, 1, 128000.00, '2025-10-31', 'en_attente'),
(173, 58, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(174, 58, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(175, 59, 1, 1, 128000.00, '2025-10-31', 'en_attente'),
(176, 59, 1, 2, 96000.00, '2026-01-31', 'en_attente'),
(177, 59, 1, 3, 96000.00, '2026-04-30', 'en_attente'),
(178, 60, 1, 1, 128000.00, '2025-10-31', 'paye'),
(179, 60, 1, 2, 96000.00, '2026-01-31', 'paye'),
(180, 60, 1, 3, 96000.00, '2026-04-30', 'paye');

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `annee_scolaire_id` int UNSIGNED NOT NULL,
  `categorie_id` int UNSIGNED NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(12,2) NOT NULL,
  `type` enum('recette','depense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_transaction` date NOT NULL,
  `reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_jointe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paiement_id` int UNSIGNED DEFAULT NULL,
  `saisi_par` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`),
  KEY `categorie_id` (`categorie_id`),
  KEY `paiement_id` (`paiement_id`),
  KEY `saisi_par` (`saisi_par`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `etablissement_id`, `annee_scolaire_id`, `categorie_id`, `libelle`, `montant`, `type`, `date_transaction`, `reference`, `piece_jointe`, `paiement_id`, `saisi_par`, `created_at`) VALUES
(1, 2, 1, 1, 'Scolarité Koffi KEÏTA', 280000.00, 'recette', '2025-09-14', NULL, NULL, NULL, 3, '2026-05-18 20:57:17'),
(2, 2, 1, 1, 'Scolarité Aminata COULIBALY', 280000.00, 'recette', '2025-11-11', NULL, NULL, NULL, 3, '2026-05-18 20:57:17'),
(3, 2, 1, 1, 'Scolarité Ibrahim DOUMBIA', 196000.00, 'recette', '2025-09-10', NULL, NULL, NULL, 3, '2026-05-18 20:57:17'),
(4, 2, 1, 1, 'Scolarité Aïssatou COULIBALY', 84000.00, 'recette', '2025-10-20', NULL, NULL, NULL, 3, '2026-05-18 20:57:18'),
(5, 2, 1, 1, 'Scolarité Adama CISSÉ', 140000.00, 'recette', '2025-11-05', NULL, NULL, NULL, 3, '2026-05-18 20:57:18'),
(6, 2, 1, 1, 'Scolarité Mariam CAMARA', 168000.00, 'recette', '2025-10-26', NULL, NULL, NULL, 3, '2026-05-18 20:57:18'),
(7, 2, 1, 1, 'Scolarité Dieneba OUATTARA', 280000.00, 'recette', '2025-10-14', NULL, NULL, NULL, 3, '2026-05-18 20:57:19'),
(8, 2, 1, 1, 'Scolarité Lamine COULIBALY', 280000.00, 'recette', '2025-09-25', NULL, NULL, NULL, 3, '2026-05-18 20:57:20'),
(9, 2, 1, 1, 'Scolarité Rokia SANOGO', 280000.00, 'recette', '2025-10-19', NULL, NULL, NULL, 3, '2026-05-18 20:57:20'),
(10, 2, 1, 1, 'Scolarité Oumar KEÏTA', 196000.00, 'recette', '2025-09-12', NULL, NULL, NULL, 3, '2026-05-18 20:57:20'),
(11, 2, 1, 1, 'Scolarité Assiatou BARRY', 84000.00, 'recette', '2025-09-22', NULL, NULL, NULL, 3, '2026-05-18 20:57:21'),
(12, 2, 1, 1, 'Scolarité Tiéba SYLLA', 140000.00, 'recette', '2025-11-09', NULL, NULL, NULL, 3, '2026-05-18 20:57:21'),
(13, 2, 1, 1, 'Scolarité Fanta SOW', 168000.00, 'recette', '2025-11-22', NULL, NULL, NULL, 3, '2026-05-18 20:57:22'),
(14, 2, 1, 1, 'Scolarité Mawa BALDÉ', 280000.00, 'recette', '2025-10-24', NULL, NULL, NULL, 3, '2026-05-18 20:57:23'),
(15, 2, 1, 1, 'Scolarité Koffi COULIBALY', 300000.00, 'recette', '2025-10-22', NULL, NULL, NULL, 3, '2026-05-18 20:57:24'),
(16, 2, 1, 1, 'Scolarité Aminata OUATTARA', 300000.00, 'recette', '2025-11-28', NULL, NULL, NULL, 3, '2026-05-18 20:57:24'),
(17, 2, 1, 1, 'Scolarité Ibrahim SOW', 210000.00, 'recette', '2025-11-25', NULL, NULL, NULL, 3, '2026-05-18 20:57:25'),
(18, 2, 1, 1, 'Scolarité Aïssatou CAMARA', 90000.00, 'recette', '2025-11-16', NULL, NULL, NULL, 3, '2026-05-18 20:57:25'),
(19, 2, 1, 1, 'Scolarité Adama COULIBALY', 150000.00, 'recette', '2025-09-04', NULL, NULL, NULL, 3, '2026-05-18 20:57:26'),
(20, 2, 1, 1, 'Scolarité Mariam SANOGO', 180000.00, 'recette', '2025-09-12', NULL, NULL, NULL, 3, '2026-05-18 20:57:26'),
(21, 2, 1, 1, 'Scolarité Dieneba SOW', 300000.00, 'recette', '2025-09-05', NULL, NULL, NULL, 3, '2026-05-18 20:57:27'),
(22, 2, 1, 1, 'Scolarité Lamine SANOGO', 300000.00, 'recette', '2025-10-08', NULL, NULL, NULL, 3, '2026-05-18 20:57:28'),
(23, 2, 1, 1, 'Scolarité Rokia SANOGO', 300000.00, 'recette', '2025-10-02', NULL, NULL, NULL, 3, '2026-05-18 20:57:29'),
(24, 2, 1, 1, 'Scolarité Oumar TOURÉ', 210000.00, 'recette', '2025-10-22', NULL, NULL, NULL, 3, '2026-05-18 20:57:29'),
(25, 2, 1, 1, 'Scolarité Assiatou DIABATÉ', 90000.00, 'recette', '2025-11-26', NULL, NULL, NULL, 3, '2026-05-18 20:57:30'),
(26, 2, 1, 1, 'Scolarité Tiéba CISSÉ', 150000.00, 'recette', '2025-11-01', NULL, NULL, NULL, 3, '2026-05-18 20:57:30'),
(27, 2, 1, 1, 'Scolarité Fanta SOW', 180000.00, 'recette', '2025-11-17', NULL, NULL, NULL, 3, '2026-05-18 20:57:30'),
(28, 2, 1, 1, 'Scolarité Mawa OUATTARA', 300000.00, 'recette', '2025-09-15', NULL, NULL, NULL, 3, '2026-05-18 20:57:31'),
(29, 2, 1, 1, 'Scolarité Koffi BAMBA', 320000.00, 'recette', '2025-09-08', NULL, NULL, NULL, 3, '2026-05-18 20:57:32'),
(30, 2, 1, 1, 'Scolarité Aminata DOUMBIA', 320000.00, 'recette', '2025-09-03', NULL, NULL, NULL, 3, '2026-05-18 20:57:32'),
(31, 2, 1, 1, 'Scolarité Ibrahim KEÏTA', 224000.00, 'recette', '2025-11-01', NULL, NULL, NULL, 3, '2026-05-18 20:57:32'),
(32, 2, 1, 1, 'Scolarité Aïssatou CISSÉ', 96000.00, 'recette', '2025-09-25', NULL, NULL, NULL, 3, '2026-05-18 20:57:33'),
(33, 2, 1, 1, 'Scolarité Adama KEÏTA', 160000.00, 'recette', '2025-09-19', NULL, NULL, NULL, 3, '2026-05-18 20:57:33'),
(34, 2, 1, 1, 'Scolarité Mariam KOUYATÉ', 192000.00, 'recette', '2025-09-22', NULL, NULL, NULL, 3, '2026-05-18 20:57:33'),
(35, 2, 1, 1, 'Scolarité Dieneba SYLLA', 320000.00, 'recette', '2025-09-17', NULL, NULL, NULL, 3, '2026-05-18 20:57:34'),
(36, 2, 1, 1, 'Scolarité Lamine SOW', 320000.00, 'recette', '2025-10-13', NULL, NULL, NULL, 3, '2026-05-18 20:57:35'),
(37, 2, 1, 1, 'Scolarité Rokia KONÉ', 320000.00, 'recette', '2025-09-02', NULL, NULL, NULL, 3, '2026-05-18 20:57:35'),
(38, 2, 1, 1, 'Scolarité Oumar SANOGO', 224000.00, 'recette', '2025-11-12', NULL, NULL, NULL, 3, '2026-05-18 20:57:36'),
(39, 2, 1, 1, 'Scolarité Assiatou BAMBA', 96000.00, 'recette', '2025-09-07', NULL, NULL, NULL, 3, '2026-05-18 20:57:36'),
(40, 2, 1, 1, 'Scolarité Tiéba DIALLO', 160000.00, 'recette', '2025-11-24', NULL, NULL, NULL, 3, '2026-05-18 20:57:36'),
(41, 2, 1, 1, 'Scolarité Fanta FOFANA', 192000.00, 'recette', '2025-11-01', NULL, NULL, NULL, 3, '2026-05-18 20:57:36'),
(42, 2, 1, 1, 'Scolarité Mawa KONÉ', 320000.00, 'recette', '2025-09-14', NULL, NULL, NULL, 3, '2026-05-18 20:57:37'),
(43, 2, 1, 3, 'Salaires octobre 2025', 850000.00, 'depense', '2025-10-31', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(44, 2, 1, 3, 'Salaires novembre 2025', 850000.00, 'depense', '2025-11-28', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(45, 2, 1, 4, 'Achat fournitures bureau', 45000.00, 'depense', '2025-10-05', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(46, 2, 1, 4, 'Papier et cartouches imprimante', 28000.00, 'depense', '2025-10-15', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(47, 2, 1, 5, 'Entretien climatiseurs', 75000.00, 'depense', '2025-10-20', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(48, 2, 1, 5, 'Nettoyage locaux — octobre', 30000.00, 'depense', '2025-10-31', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(49, 2, 1, 3, 'Salaires décembre 2025', 850000.00, 'depense', '2025-12-30', NULL, NULL, NULL, 3, '2026-05-18 20:59:28'),
(50, 2, 1, 4, 'Achat livres bibliothèque', 95000.00, 'depense', '2025-11-10', NULL, NULL, NULL, 3, '2026-05-18 20:59:28');

-- --------------------------------------------------------

--
-- Structure de la table `transferts`
--

DROP TABLE IF EXISTS `transferts`;
CREATE TABLE IF NOT EXISTS `transferts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `eleve_id` int UNSIGNED NOT NULL,
  `etablissement_depart_id` int UNSIGNED DEFAULT NULL,
  `etablissement_arrivee_id` int UNSIGNED DEFAULT NULL,
  `date_transfert` date NOT NULL,
  `motif` text COLLATE utf8mb4_unicode_ci,
  `numero_ordre_depart` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `eleve_id` (`eleve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `types_evaluation`
--

DROP TABLE IF EXISTS `types_evaluation`;
CREATE TABLE IF NOT EXISTS `types_evaluation` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coefficient` decimal(4,2) DEFAULT '1.00',
  `sur` decimal(5,2) DEFAULT '20.00',
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_evaluation`
--

INSERT INTO `types_evaluation` (`id`, `etablissement_id`, `nom`, `coefficient`, `sur`) VALUES
(1, 2, 'Devoir Surveillé', 1.00, 20.00),
(2, 2, 'Composition', 2.00, 20.00),
(3, 2, 'Interrogation', 1.00, 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `types_frais`
--

DROP TABLE IF EXISTS `types_frais`;
CREATE TABLE IF NOT EXISTS `types_frais` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant_defaut` decimal(12,2) DEFAULT '0.00',
  `obligatoire` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_frais`
--

INSERT INTO `types_frais` (`id`, `etablissement_id`, `nom`, `montant_defaut`, `obligatoire`) VALUES
(1, 2, 'Frais de scolarité', 250000.00, 1),
(2, 2, 'Frais d\'inscription', 50000.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `etablissement_id` int UNSIGNED DEFAULT NULL,
  `role_id` tinyint UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenoms` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) DEFAULT '1',
  `email_verifie` tinyint(1) DEFAULT '0',
  `token_reset` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL,
  `derniere_connexion` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `role_id` (`role_id`),
  KEY `idx_login` (`login`),
  KEY `idx_etablissement` (`etablissement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `etablissement_id`, `role_id`, `nom`, `prenoms`, `email`, `telephone`, `login`, `password`, `photo`, `actif`, `email_verifie`, `token_reset`, `token_expire`, `derniere_connexion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'Super', 'Admin', 'admin@erp-scolaire.local', NULL, 'superadmin', '$2y$12$71F4ktM6N12ro8YzX1805u3vc7dTJhmbPGlOMaIF8wPXXzllWqz4W', NULL, 1, 0, NULL, NULL, '2026-05-19 11:02:07', '2026-05-18 17:17:14', '2026-05-19 11:02:07', NULL),
(3, 2, 2, 'KONATÉ', 'Mamadou', 'admin@lma-abidjan.ci', '+225 07 00 00 01', 'admin.lma', '$2y$12$ogZeEmAomM9f9RgPXyqqk.FYRMy2kWveGDxrmoiuRJwSTiR4.xSya', NULL, 1, 0, NULL, NULL, '2026-05-19 10:53:04', '2026-05-18 20:57:08', '2026-05-19 10:53:04', NULL),
(4, 2, 3, 'DIALLO', 'Ibrahim', 'directeur@lma-abidjan.ci', '+225 07 00 00 02', 'directeur.lma', '$2y$12$gmLOxZQoW7/y2th8qXBkmO08MMUI/.aaAUlUv5jOpKBBnItH/AhTC', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:08', NULL, NULL),
(5, 2, 2, 'COULIBALY', 'Aïssatou', 'compta@lma-abidjan.ci', '+225 07 00 00 03', 'compta.lma', '$2y$12$NB12vHRd9Q0mRiBu/QFOd.EOtutOagOiRNLnhE1cWoIZ5wsfnZUIS', NULL, 1, 0, NULL, NULL, '2026-05-19 11:06:54', '2026-05-18 20:57:08', '2026-05-19 11:06:54', NULL),
(6, 2, 4, 'TOURÉ', 'Fatou', 'prof.toure@lma-abidjan.ci', '+225 07 11 22 33', 'prof.toure', '$2y$12$BxwPH1HaIQDfoR6n6SdGjOn4pUx9ct4n4N7hkD7RsZ71kyBWfQoye', NULL, 1, 0, NULL, NULL, '2026-05-19 10:59:07', '2026-05-18 20:57:10', '2026-05-19 10:59:07', NULL),
(7, 2, 4, 'BAMBA', 'Seydou', 'prof.bamba@lma-abidjan.ci', '+225 07 22 33 44', 'prof.bamba', '$2y$12$xZjwXIu36Z85QEO4gbmWJOL1vANqc1mT1piB.H0pqaR92CNcN2YA6', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:11', NULL, NULL),
(8, 2, 4, 'OUATTARA', 'Mariam', 'prof.ouattara@lma-abidjan.ci', '+225 07 33 44 55', 'prof.ouattara', '$2y$12$OEcIIdxXQAiZVOCUcWZuLeJzx73udYtdt9Oi8ICbcYlJPow8grkhC', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:11', NULL, NULL),
(9, 2, 4, 'KONÉ', 'Adama', 'prof.kone@lma-abidjan.ci', '+225 07 44 55 66', 'prof.kone', '$2y$12$dntKmyLvWbQbn2F2pWeUQeGq8PAxddLm37I14BQ3brKikXDGGcwbi', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:11', NULL, NULL),
(10, 2, 4, 'TRAORÉ', 'Aminata', 'prof.traore@lma-abidjan.ci', '+225 07 55 66 77', 'prof.traore', '$2y$12$0vT76iF2iZL8iTr30Q1oVenS502WlWrXN/tk75FGIw0XVGz3Csvny', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:12', NULL, NULL),
(11, 2, 4, 'SANOGO', 'Boubacar', 'prof.sanogo@lma-abidjan.ci', '+225 07 66 77 88', 'prof.sanogo', '$2y$12$LVQp8lT6F08ZBxojHi/UJunzUicUDcfaJqUL0krNyqZj7Np1Ofr6W', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:12', NULL, NULL),
(12, 2, 4, 'DIABATÉ', 'Kadidia', 'prof.diabate@lma-abidjan.ci', '+225 07 77 88 99', 'prof.diabate', '$2y$12$aLFRTE4SOb0sjCLLhxt3ceCHDeJRAx5YeJkJsW59dAO0ui./ntBuC', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:12', NULL, NULL),
(13, 2, 4, 'DEMBÉLÉ', 'Moussa', 'prof.dembele@lma-abidjan.ci', '+225 07 88 99 00', 'prof.dembele', '$2y$12$ROu/rNBCo2drVnge2A4DgejP81Pi1.2ZP9IdTe3NHOrerrnfPA/.e', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:13', NULL, NULL),
(14, 2, 4, 'COULIBALY', 'Rokia', 'prof.coulibaly@lma-abidjan.ci', '+225 07 99 00 11', 'prof.coulibaly', '$2y$12$Ous149wvFXZoG1t/f0a1mOanriWayIHBtJzqVU5J4FqBFreCsa306', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:13', NULL, NULL),
(15, 2, 4, 'FOFANA', 'Abdoulaye', 'prof.fofana@lma-abidjan.ci', '+225 07 00 11 22', 'prof.fofana', '$2y$12$Q3Qy4ZTI2aMuu5bmQXgDleZ0X.mXVAtLHKYJKE6b5hLkrrQ8/gZ0S', NULL, 1, 0, NULL, NULL, NULL, '2026-05-18 20:57:13', NULL, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `affectations_cours`
--
ALTER TABLE `affectations_cours`
  ADD CONSTRAINT `affectations_cours_ibfk_1` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`),
  ADD CONSTRAINT `affectations_cours_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `affectations_cours_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `affectations_cours_ibfk_4` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`);

--
-- Contraintes pour la table `annees_scolaires`
--
ALTER TABLE `annees_scolaires`
  ADD CONSTRAINT `annees_scolaires_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`),
  ADD CONSTRAINT `annonces_ibfk_2` FOREIGN KEY (`auteur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `annonces_ibfk_3` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `auth_logs`
--
ALTER TABLE `auth_logs`
  ADD CONSTRAINT `auth_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `bulletins`
--
ALTER TABLE `bulletins`
  ADD CONSTRAINT `bulletins_ibfk_1` FOREIGN KEY (`inscription_id`) REFERENCES `inscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bulletins_ibfk_2` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `categories_comptables`
--
ALTER TABLE `categories_comptables`
  ADD CONSTRAINT `categories_comptables_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`),
  ADD CONSTRAINT `classes_ibfk_3` FOREIGN KEY (`niveau_id`) REFERENCES `niveaux` (`id`),
  ADD CONSTRAINT `classes_ibfk_4` FOREIGN KEY (`titulaire_id`) REFERENCES `personnel` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `creneaux_horaires`
--
ALTER TABLE `creneaux_horaires`
  ADD CONSTRAINT `creneaux_horaires_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cycles`
--
ALTER TABLE `cycles`
  ADD CONSTRAINT `cycles_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossiers_paiement`
--
ALTER TABLE `dossiers_paiement`
  ADD CONSTRAINT `dossiers_paiement_ibfk_1` FOREIGN KEY (`inscription_id`) REFERENCES `inscriptions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD CONSTRAINT `eleves_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`),
  ADD CONSTRAINT `eleves_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `emplois_du_temps`
--
ALTER TABLE `emplois_du_temps`
  ADD CONSTRAINT `emplois_du_temps_ibfk_1` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `emplois_du_temps_ibfk_2` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`),
  ADD CONSTRAINT `emplois_du_temps_ibfk_3` FOREIGN KEY (`affectation_id`) REFERENCES `affectations_cours` (`id`),
  ADD CONSTRAINT `emplois_du_temps_ibfk_4` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `emplois_du_temps_ibfk_5` FOREIGN KEY (`creneau_id`) REFERENCES `creneaux_horaires` (`id`);

--
-- Contraintes pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD CONSTRAINT `emprunts_ibfk_1` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`),
  ADD CONSTRAINT `emprunts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`affectation_id`) REFERENCES `affectations_cours` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_3` FOREIGN KEY (`type_evaluation_id`) REFERENCES `types_evaluation` (`id`);

--
-- Contraintes pour la table `examens`
--
ALTER TABLE `examens`
  ADD CONSTRAINT `examens_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`),
  ADD CONSTRAINT `examens_ibfk_2` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`),
  ADD CONSTRAINT `examens_ibfk_3` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `inscriptions_ibfk_3` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`);

--
-- Contraintes pour la table `livres`
--
ALTER TABLE `livres`
  ADD CONSTRAINT `livres_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`expediteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `moyennes`
--
ALTER TABLE `moyennes`
  ADD CONSTRAINT `moyennes_ibfk_1` FOREIGN KEY (`inscription_id`) REFERENCES `inscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `moyennes_ibfk_2` FOREIGN KEY (`affectation_id`) REFERENCES `affectations_cours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `moyennes_ibfk_3` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD CONSTRAINT `niveaux_ibfk_1` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_3` FOREIGN KEY (`saisie_par`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_ibfk_1` FOREIGN KEY (`dossier_paiement_id`) REFERENCES `dossiers_paiement` (`id`),
  ADD CONSTRAINT `paiements_ibfk_2` FOREIGN KEY (`tranche_id`) REFERENCES `tranches_paiement` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `paiements_ibfk_3` FOREIGN KEY (`encaisse_par`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `parent_eleve`
--
ALTER TABLE `parent_eleve`
  ADD CONSTRAINT `parent_eleve_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parent_eleve_ibfk_2` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `periodes`
--
ALTER TABLE `periodes`
  ADD CONSTRAINT `periodes_ibfk_1` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `personnel_ibfk_2` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`);

--
-- Contraintes pour la table `planning_examens`
--
ALTER TABLE `planning_examens`
  ADD CONSTRAINT `planning_examens_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `planning_examens_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `planning_examens_ibfk_3` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `planning_examens_ibfk_4` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `presences`
--
ALTER TABLE `presences`
  ADD CONSTRAINT `presences_ibfk_1` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presences_ibfk_2` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `salles`
--
ALTER TABLE `salles`
  ADD CONSTRAINT `salles_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `seances`
--
ALTER TABLE `seances`
  ADD CONSTRAINT `seances_ibfk_1` FOREIGN KEY (`emploi_du_temps_id`) REFERENCES `emplois_du_temps` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `seances_ibfk_2` FOREIGN KEY (`affectation_id`) REFERENCES `affectations_cours` (`id`),
  ADD CONSTRAINT `seances_ibfk_3` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `surveillances_examen`
--
ALTER TABLE `surveillances_examen`
  ADD CONSTRAINT `surveillances_examen_ibfk_1` FOREIGN KEY (`planning_examen_id`) REFERENCES `planning_examens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surveillances_examen_ibfk_2` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`);

--
-- Contraintes pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD CONSTRAINT `tarifs_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`),
  ADD CONSTRAINT `tarifs_ibfk_2` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`),
  ADD CONSTRAINT `tarifs_ibfk_3` FOREIGN KEY (`niveau_id`) REFERENCES `niveaux` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tarifs_ibfk_4` FOREIGN KEY (`type_frais_id`) REFERENCES `types_frais` (`id`);

--
-- Contraintes pour la table `tranches_paiement`
--
ALTER TABLE `tranches_paiement`
  ADD CONSTRAINT `tranches_paiement_ibfk_1` FOREIGN KEY (`dossier_paiement_id`) REFERENCES `dossiers_paiement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tranches_paiement_ibfk_2` FOREIGN KEY (`type_frais_id`) REFERENCES `types_frais` (`id`);

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`categorie_id`) REFERENCES `categories_comptables` (`id`),
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`paiement_id`) REFERENCES `paiements` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_ibfk_5` FOREIGN KEY (`saisi_par`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `transferts`
--
ALTER TABLE `transferts`
  ADD CONSTRAINT `transferts_ibfk_1` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`);

--
-- Contraintes pour la table `types_evaluation`
--
ALTER TABLE `types_evaluation`
  ADD CONSTRAINT `types_evaluation_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `types_frais`
--
ALTER TABLE `types_frais`
  ADD CONSTRAINT `types_frais_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissements` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
