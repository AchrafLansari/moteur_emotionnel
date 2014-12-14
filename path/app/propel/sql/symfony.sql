
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- produit
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `produit`;

CREATE TABLE `produit`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `titre` VARCHAR(100),
    `auteur` VARCHAR(100),
    `description` VARCHAR(1000),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- mot
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mot`;

CREATE TABLE `mot`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `mot` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `mot_U_1` (`mot`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- produit_mot_poids
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `produit_mot_poids`;

CREATE TABLE `produit_mot_poids`
(
    `mot_id` INTEGER NOT NULL,
    `produit_id` INTEGER NOT NULL,
    `poids` INTEGER NOT NULL,
    PRIMARY KEY (`mot_id`,`produit_id`,`poids`),
    INDEX `produit_mot_poids_FI_2` (`produit_id`),
    CONSTRAINT `produit_mot_poids_FK_1`
        FOREIGN KEY (`mot_id`)
        REFERENCES `mot` (`id`),
    CONSTRAINT `produit_mot_poids_FK_2`
        FOREIGN KEY (`produit_id`)
        REFERENCES `produit` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- utilisateur_produit
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `utilisateur_produit`;

CREATE TABLE `utilisateur_produit`
(
    `utilisateur_id` INTEGER NOT NULL,
    `produit_id` INTEGER NOT NULL,
    `note` INTEGER,
    `achat` TINYINT(1) DEFAULT 0,
    `nombre_visite` INTEGER DEFAULT 0,
    PRIMARY KEY (`utilisateur_id`,`produit_id`),
    INDEX `utilisateur_produit_FI_1` (`produit_id`),
    CONSTRAINT `utilisateur_produit_FK_1`
        FOREIGN KEY (`produit_id`)
        REFERENCES `interet` (`id`),
    CONSTRAINT `utilisateur_produit_FK_2`
        FOREIGN KEY (`utilisateur_id`)
        REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- requete
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `requete`;

CREATE TABLE `requete`
(
    `requete_id` INTEGER NOT NULL,
    `mot_id` INTEGER NOT NULL,
    `utilisateur_id` INTEGER NOT NULL,
    PRIMARY KEY (`requete_id`,`mot_id`,`utilisateur_id`),
    INDEX `requete_FI_1` (`mot_id`),
    INDEX `requete_FI_2` (`utilisateur_id`),
    CONSTRAINT `requete_FK_1`
        FOREIGN KEY (`mot_id`)
        REFERENCES `mot` (`id`),
    CONSTRAINT `requete_FK_2`
        FOREIGN KEY (`utilisateur_id`)
        REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- utilisateur
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE `utilisateur`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(100),
    `prenom` VARCHAR(100),
    `mail` VARCHAR(100),
    `age` INTEGER,
    `ville` VARCHAR(100),
    `ip_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `utilisateur_FI_1` (`ip_id`),
    CONSTRAINT `utilisateur_FK_1`
        FOREIGN KEY (`ip_id`)
        REFERENCES `ip` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- ip
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ip`;

CREATE TABLE `ip`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `pays` VARCHAR(100),
    `departement` VARCHAR(100),
    `ville` VARCHAR(100),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- interet
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `interet`;

CREATE TABLE `interet`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(100),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- utilisateur_interet
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `utilisateur_interet`;

CREATE TABLE `utilisateur_interet`
(
    `utilisateur_id` INTEGER NOT NULL,
    `interet_id` INTEGER NOT NULL,
    `valeur` INTEGER,
    PRIMARY KEY (`utilisateur_id`,`interet_id`),
    INDEX `utilisateur_interet_FI_1` (`interet_id`),
    CONSTRAINT `utilisateur_interet_FK_1`
        FOREIGN KEY (`interet_id`)
        REFERENCES `interet` (`id`),
    CONSTRAINT `utilisateur_interet_FK_2`
        FOREIGN KEY (`utilisateur_id`)
        REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
