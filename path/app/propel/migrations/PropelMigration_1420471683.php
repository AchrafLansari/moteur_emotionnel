<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1420471683.
 * Generated on 2015-01-05 15:28:03 
 */
class PropelMigration_1420471683
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'symfony' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `produit` CHANGE `description` `description` VARCHAR(255);

ALTER TABLE `produit`
    ADD `sous_titre` VARCHAR(255) AFTER `titre`,
    ADD `image` VARCHAR(255) AFTER `description`,
    ADD `lien` VARCHAR(255) AFTER `image`;

ALTER TABLE `utilisateur`
    ADD `description` VARCHAR(255) AFTER `ville`,
    ADD `ip` VARCHAR(12) AFTER `description`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'symfony' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `produit` CHANGE `description` `description` VARCHAR(1000);

ALTER TABLE `produit` DROP `sous_titre`;

ALTER TABLE `produit` DROP `image`;

ALTER TABLE `produit` DROP `lien`;

ALTER TABLE `utilisateur` DROP `description`;

ALTER TABLE `utilisateur` DROP `ip`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}