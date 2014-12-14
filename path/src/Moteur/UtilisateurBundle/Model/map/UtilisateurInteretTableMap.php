<?php

namespace Moteur\UtilisateurBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'utilisateur_interet' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Moteur.UtilisateurBundle.Model.map
 */
class UtilisateurInteretTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.UtilisateurBundle.Model.map.UtilisateurInteretTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('utilisateur_interet');
        $this->setPhpName('UtilisateurInteret');
        $this->setClassname('Moteur\\UtilisateurBundle\\Model\\UtilisateurInteret');
        $this->setPackage('src.Moteur.UtilisateurBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('utilisateur_id', 'UtilisateurId', 'INTEGER' , 'utilisateur', 'id', true, null, null);
        $this->addForeignPrimaryKey('interet_id', 'InteretId', 'INTEGER' , 'interet', 'id', true, null, null);
        $this->addColumn('valeur', 'Valeur', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Interet', 'Moteur\\UtilisateurBundle\\Model\\Interet', RelationMap::MANY_TO_ONE, array('interet_id' => 'id', ), null, null);
        $this->addRelation('Utilisateur', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::MANY_TO_ONE, array('utilisateur_id' => 'id', ), null, null);
    } // buildRelations()

} // UtilisateurInteretTableMap
