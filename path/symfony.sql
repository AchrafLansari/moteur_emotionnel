-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 14 Décembre 2014 à 20:37
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `symfony`
--
CREATE DATABASE IF NOT EXISTS `symfony` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `symfony`;

DELIMITER $$
--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getDepartementUtilisateur`(`id` INT) RETURNS varchar(100) CHARSET utf8
    READS SQL DATA
return (select departement from ip JOIN utilisateur ON utilisateur.ip_id = ip.id WHERE utilisateur.id = id)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getPaysUtilisateur`(`id` INT) RETURNS varchar(100) CHARSET utf8
    READS SQL DATA
return (select pays from ip JOIN utilisateur ON utilisateur.ip_id = ip.id WHERE utilisateur.id = id)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getVilleUtilisateur`(`id` INT) RETURNS varchar(100) CHARSET latin1
    READS SQL DATA
return (select ip.ville from ip JOIN utilisateur ON utilisateur.ip_id = ip.id WHERE utilisateur.id = id)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `score_age_utilisateurs`(`id_1` INT, `id_2` INT) RETURNS int(11)
    READS SQL DATA
return SQRT(
        	ABS(
                80-ABS(
                    (select age from utilisateur where id = id_1)
                    -
                    (select age from utilisateur where id = id_2)
                )
        	)
    	) / 3$$

CREATE DEFINER=`root`@`localhost` FUNCTION `score_ip_utilisateurs`(`id_1` INT, `id_2` INT) RETURNS int(11)
    READS SQL DATA
return (SELECT IF( getPaysUtilisateur( id_1 ) = getPaysUtilisateur( id_2 ) , 1, 0 ) + IF( getDepartementUtilisateur( id_1 ) = getDepartementUtilisateur( id_2 ) , 1, 0 ) + IF( getVilleUtilisateur( id_1 ) = getVilleUtilisateur( id_2 ) , 1, 0 ))$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `interet`
--

CREATE TABLE IF NOT EXISTS `interet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `interet`
--

INSERT INTO `interet` (`id`, `nom`) VALUES
(1, 'sport'),
(2, 'tennis'),
(3, 'politique'),
(4, 'cinema'),
(5, 'theatre');

-- --------------------------------------------------------

--
-- Structure de la table `ip`
--

CREATE TABLE IF NOT EXISTS `ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pays` varchar(100) DEFAULT NULL,
  `departement` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Contenu de la table `ip`
--

INSERT INTO `ip` (`id`, `pays`, `departement`, `ville`) VALUES
(1, 'CN - China', 'Beijing', 'Beijing'),
(2, 'US - United States', 'North Carolina', 'Durham'),
(3, 'FR - France', NULL, NULL),
(4, 'US - United States', 'Ohio', 'Columbus'),
(5, 'US - United States', 'Oregon', 'Eugene'),
(6, 'FR - France', 'Champagne-Ardenne', 'Reims'),
(7, 'US - United States', 'Massachusetts', 'Cambridge'),
(8, NULL, NULL, NULL),
(9, 'US - United States', 'Georgia', 'Leesburg'),
(10, 'DK - Denmark', NULL, NULL),
(11, 'US - United States', 'New York', 'Buffalo'),
(12, 'GB - United Kingdom', 'Gloucestershire', 'Teddington'),
(13, 'DK - Denmark', 'Hovedstaden', 'Copenhagen'),
(14, 'US - United States', NULL, NULL),
(15, 'KR - Korea, Republic of', NULL, NULL),
(16, 'US - United States', 'Missouri', 'Chesterfield'),
(17, 'FR - France', NULL, NULL),
(18, 'CN - China', 'Hebei', 'Hebei'),
(19, 'CN - China', 'Hainan', 'Haikou'),
(20, 'US - United States', 'California', 'Palo Alto'),
(21, 'RU - Russian Federation', 'Tyumen''', 'Tyumen'),
(22, 'US - United States', NULL, NULL),
(23, 'BD - Bangladesh', NULL, NULL),
(24, 'US - United States', NULL, NULL),
(25, 'US - United States', NULL, NULL),
(26, 'RO - Romania', NULL, NULL),
(27, 'US - United States', 'Ohio', 'Columbus'),
(28, 'US - United States', 'Virginia', 'Falls Church'),
(29, 'US - United States', 'North Carolina', 'Durham'),
(30, 'US - United States', 'North Carolina', 'Raleigh'),
(31, 'US - United States', NULL, NULL),
(32, 'US - United States', 'Ohio', 'Columbus'),
(33, 'GB - United Kingdom', 'Plymouth', 'Plymouth'),
(34, 'FR - France', 'Ile-de-France', 'Paris'),
(35, 'US - United States', 'Massachusetts', 'Westfield'),
(36, 'US - United States', 'Michigan', 'Taylor'),
(37, 'TR - Turkey', 'Ankara', 'Ankara'),
(38, 'DK - Denmark', NULL, NULL),
(39, 'CN - China', 'Beijing', 'Beijing'),
(40, 'IL - Israel', NULL, NULL),
(41, 'DE - Germany', 'Sachsen', 'Rothenburg'),
(42, 'US - United States', 'Ohio', 'Columbus'),
(43, 'CH - Switzerland', 'Aargau', 'Gebenstorf'),
(44, 'JP - Japan', NULL, NULL),
(45, 'AU - Australia', NULL, NULL),
(46, 'JP - Japan', NULL, NULL),
(47, 'US - United States', NULL, NULL),
(48, 'US - United States', 'Ohio', 'Columbus'),
(49, 'CN - China', 'Fujian', 'Fuzhou'),
(50, 'US - United States', 'New York', 'Melville'),
(51, 'AU - Australia', NULL, NULL),
(52, 'NL - Netherlands', NULL, NULL),
(53, 'GB - United Kingdom', NULL, NULL),
(54, 'US - United States', NULL, NULL),
(55, 'US - United States', NULL, NULL),
(56, 'GB - United Kingdom', NULL, NULL),
(57, 'US - United States', 'Texas', 'Houston');

-- --------------------------------------------------------

--
-- Structure de la table `mot`
--

CREATE TABLE IF NOT EXISTS `mot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mot` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mot_U_1` (`mot`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `mot`
--

INSERT INTO `mot` (`id`, `mot`) VALUES
(6, 'aventure'),
(7, 'ecrit'),
(3, 'fabuleux'),
(14, 'gendarme'),
(1, 'kevin'),
(2, 'masseix'),
(8, 'meme'),
(12, 'policier'),
(9, 'premier'),
(4, 'roman'),
(13, 'test'),
(5, 'titre');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) DEFAULT NULL,
  `auteur` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`id`, `titre`, `auteur`, `description`) VALUES
(1, 'mon fabuleux titre de roman', 'Kevin Masseix', 'le tout premier roman d''aventure ecrit par moi meme'),
(2, 'mon fabuleux titre de roman policier', 'Kevin Masseix', 'le tout premier roman d''aventure ecrit par moi meme'),
(3, 'mon fabuleux titre de roman policier', 'Kevin Masseix', 'le tout premier roman d''aventure ecrit par moi meme'),
(4, 'mon fabuleux titre de roman policier', 'Kevin Masseix', 'le tout premier roman d''aventure ecrit par moi meme');

-- --------------------------------------------------------

--
-- Structure de la table `produit_mot_poids`
--

CREATE TABLE IF NOT EXISTS `produit_mot_poids` (
  `mot_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `poids` int(11) NOT NULL,
  PRIMARY KEY (`mot_id`,`produit_id`,`poids`),
  KEY `produit_mot_poids_FI_2` (`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `produit_mot_poids`
--

INSERT INTO `produit_mot_poids` (`mot_id`, `produit_id`, `poids`) VALUES
(1, 1, 10),
(2, 1, 10),
(3, 1, 5),
(4, 1, 6),
(5, 1, 5),
(6, 1, 1),
(7, 1, 1),
(8, 1, 1),
(9, 1, 1),
(1, 2, 10),
(2, 2, 10),
(3, 2, 5),
(4, 2, 6),
(5, 2, 5),
(6, 2, 1),
(7, 2, 1),
(8, 2, 1),
(9, 2, 1),
(12, 2, 5),
(1, 3, 10),
(2, 3, 10),
(3, 3, 5),
(4, 3, 6),
(5, 3, 5),
(6, 3, 1),
(7, 3, 1),
(8, 3, 1),
(9, 3, 1),
(12, 3, 5),
(1, 4, 10),
(2, 4, 10),
(3, 4, 5),
(4, 4, 6),
(5, 4, 5),
(6, 4, 1),
(7, 4, 1),
(8, 4, 1),
(9, 4, 1),
(12, 4, 5);

-- --------------------------------------------------------

--
-- Structure de la table `requete`
--

CREATE TABLE IF NOT EXISTS `requete` (
  `requete_id` int(11) NOT NULL,
  `mot_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`requete_id`,`mot_id`,`utilisateur_id`),
  KEY `requete_FI_1` (`mot_id`),
  KEY `requete_FI_2` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `requete`
--

INSERT INTO `requete` (`requete_id`, `mot_id`, `utilisateur_id`) VALUES
(1, 5, 1),
(2, 5, 1),
(3, 5, 1),
(6, 5, 1),
(7, 5, 1),
(3, 6, 1),
(6, 6, 1),
(7, 6, 1),
(3, 14, 1),
(6, 14, 1),
(7, 14, 1);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `score_utilisateurs`
--
CREATE TABLE IF NOT EXISTS `score_utilisateurs` (
`id_1` int(11)
,`id_2` bigint(11)
,`score` double
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `score_utilisateurs_age`
--
CREATE TABLE IF NOT EXISTS `score_utilisateurs_age` (
`id_1` int(11)
,`id_2` int(11)
,`score` int(11)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `score_utilisateurs_interet`
--
CREATE TABLE IF NOT EXISTS `score_utilisateurs_interet` (
`id_1` int(11)
,`id_2` int(11)
,`score` double
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `score_utilisateurs_ip`
--
CREATE TABLE IF NOT EXISTS `score_utilisateurs_ip` (
`id_1` int(11)
,`id_2` int(11)
,`score` int(11)
);
-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `ip_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_FI_1` (`ip_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `mail`, `age`, `ville`, `ip_id`) VALUES
(1, 'fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f', 'fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f', 'masseix.kevin@gmail.com', 68, 'Paris', 49),
(2, 'c1dfd96eea8cc2b62785275bca38ac261256e278', '1b6453892473a467d07372d45eb05abc2031647a', 'masseix.kevin@gmail.com', 75, 'Paris', 50),
(3, '1b6453892473a467d07372d45eb05abc2031647a', '356a192b7913b04c54574d18c28d46e6395428ab', 'masseix.kevin@gmail.com', 51, 'Paris', 51),
(4, 'c1dfd96eea8cc2b62785275bca38ac261256e278', '1b6453892473a467d07372d45eb05abc2031647a', 'masseix.kevin@gmail.com', 27, 'Paris', 52),
(5, 'fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f', 'b6589fc6ab0dc82cf12099d1c2d40ab994e8410c', 'masseix.kevin@gmail.com', 42, 'Paris', 53),
(6, 'fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f', '0ade7c2cf97f75d009975f4d720d1fa6c19f4897', 'masseix.kevin@gmail.com', 72, 'Paris', 54),
(7, '77de68daecd823babbb58edb1c8e14d7106e83bb', '356a192b7913b04c54574d18c28d46e6395428ab', 'masseix.kevin@gmail.com', 12, 'Paris', 55),
(8, 'da4b9237bacccdf19c0760cab7aec4a8359010b0', '1b6453892473a467d07372d45eb05abc2031647a', 'masseix.kevin@gmail.com', 24, 'Paris', 56),
(9, '0ade7c2cf97f75d009975f4d720d1fa6c19f4897', 'b6589fc6ab0dc82cf12099d1c2d40ab994e8410c', 'masseix.kevin@gmail.com', 19, 'Paris', 57);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_interet`
--

CREATE TABLE IF NOT EXISTS `utilisateur_interet` (
  `utilisateur_id` int(11) NOT NULL,
  `interet_id` int(11) NOT NULL,
  `valeur` int(11) DEFAULT NULL,
  PRIMARY KEY (`utilisateur_id`,`interet_id`),
  KEY `utilisateur_interet_FI_1` (`interet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur_interet`
--

INSERT INTO `utilisateur_interet` (`utilisateur_id`, `interet_id`, `valeur`) VALUES
(1, 1, 0),
(1, 3, 8),
(1, 4, 3),
(2, 2, 8),
(2, 5, 3),
(3, 1, 7),
(3, 4, 8),
(3, 5, 10),
(4, 2, 4),
(4, 3, 4),
(4, 5, 9),
(5, 1, 1),
(5, 3, 10),
(5, 4, 8),
(7, 2, 5),
(7, 3, 8),
(7, 4, 2),
(8, 1, 6),
(9, 1, 1),
(9, 2, 2),
(9, 4, 8),
(9, 5, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_produit`
--

CREATE TABLE IF NOT EXISTS `utilisateur_produit` (
  `utilisateur_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL,
  `achat` tinyint(1) DEFAULT '0',
  `nombre_visite` int(11) DEFAULT '0',
  PRIMARY KEY (`utilisateur_id`,`produit_id`),
  KEY `utilisateur_produit_FI_1` (`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur_produit`
--

INSERT INTO `utilisateur_produit` (`utilisateur_id`, `produit_id`, `note`, `achat`, `nombre_visite`) VALUES
(4, 1, 7, 1, 0),
(5, 1, 8, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la vue `score_utilisateurs`
--
DROP TABLE IF EXISTS `score_utilisateurs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `score_utilisateurs` AS select `sc_ip`.`id_1` AS `id_1`,ifnull(ifnull(`sc_int`.`id_2`,`sc_ip`.`id_2`),`sc_age`.`id_2`) AS `id_2`,((ifnull(`sc_ip`.`score`,0) + ifnull(`sc_int`.`score`,0)) + ifnull(`sc_age`.`score`,0)) AS `score` from ((`score_utilisateurs_ip` `sc_ip` left join `score_utilisateurs_interet` `sc_int` on(((`sc_ip`.`id_1` = `sc_int`.`id_1`) and (`sc_ip`.`id_2` = `sc_int`.`id_2`)))) left join `score_utilisateurs_age` `sc_age` on(((`sc_ip`.`id_1` = `sc_age`.`id_1`) and (`sc_ip`.`id_2` = `sc_age`.`id_2`))));

-- --------------------------------------------------------

--
-- Structure de la vue `score_utilisateurs_age`
--
DROP TABLE IF EXISTS `score_utilisateurs_age`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `score_utilisateurs_age` AS select `u1`.`id` AS `id_1`,`u2`.`id` AS `id_2`,`score_age_utilisateurs`(`u1`.`id`,`u2`.`id`) AS `score` from (`utilisateur` `u1` join `utilisateur` `u2` on((`u1`.`id` < `u2`.`id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `score_utilisateurs_interet`
--
DROP TABLE IF EXISTS `score_utilisateurs_interet`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `score_utilisateurs_interet` AS select `u1`.`utilisateur_id` AS `id_1`,`u2`.`utilisateur_id` AS `id_2`,sum(sqrt((10 - abs((`u1`.`valeur` - `u2`.`valeur`))))) AS `score` from (`utilisateur_interet` `u1` join `utilisateur_interet` `u2` on((`u1`.`utilisateur_id` < `u2`.`utilisateur_id`))) where (`u1`.`interet_id` = `u2`.`interet_id`) group by `id_1`,`id_2`;

-- --------------------------------------------------------

--
-- Structure de la vue `score_utilisateurs_ip`
--
DROP TABLE IF EXISTS `score_utilisateurs_ip`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `score_utilisateurs_ip` AS select `u1`.`id` AS `id_1`,`u2`.`id` AS `id_2`,`score_ip_utilisateurs`(`u1`.`id`,`u2`.`id`) AS `score` from (`utilisateur` `u1` join `utilisateur` `u2` on((`u1`.`id` < `u2`.`id`)));

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `produit_mot_poids`
--
ALTER TABLE `produit_mot_poids`
  ADD CONSTRAINT `produit_mot_poids_FK_1` FOREIGN KEY (`mot_id`) REFERENCES `mot` (`id`),
  ADD CONSTRAINT `produit_mot_poids_FK_2` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `requete`
--
ALTER TABLE `requete`
  ADD CONSTRAINT `requete_FK_1` FOREIGN KEY (`mot_id`) REFERENCES `mot` (`id`),
  ADD CONSTRAINT `requete_FK_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_FK_1` FOREIGN KEY (`ip_id`) REFERENCES `ip` (`id`);

--
-- Contraintes pour la table `utilisateur_interet`
--
ALTER TABLE `utilisateur_interet`
  ADD CONSTRAINT `utilisateur_interet_FK_1` FOREIGN KEY (`interet_id`) REFERENCES `interet` (`id`),
  ADD CONSTRAINT `utilisateur_interet_FK_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `utilisateur_produit`
--
ALTER TABLE `utilisateur_produit`
  ADD CONSTRAINT `utilisateur_produit_FK_1` FOREIGN KEY (`produit_id`) REFERENCES `interet` (`id`),
  ADD CONSTRAINT `utilisateur_produit_FK_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
