
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- utilisateur
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE `utilisateur`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(100),
    `prenom` VARCHAR(100),
    `age` DECIMAL,
    `ville` VARCHAR(100),
    `ip` DECIMAL,
    `description` TEXT,
    `categorie_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `utilisateur_FI_1` (`categorie_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- produit
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `produit`;

CREATE TABLE `produit`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(100),
    `autheur` VARCHAR(100),
    `description` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- categorie
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `categorie`;

CREATE TABLE `categorie`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(100),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
