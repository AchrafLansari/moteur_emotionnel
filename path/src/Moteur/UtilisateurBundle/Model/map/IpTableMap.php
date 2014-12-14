<?php

namespace Moteur\UtilisateurBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'ip' table.
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
class IpTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.UtilisateurBundle.Model.map.IpTableMap';

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
        $this->setName('ip');
        $this->setPhpName('Ip');
        $this->setClassname('Moteur\\UtilisateurBundle\\Model\\Ip');
        $this->setPackage('src.Moteur.UtilisateurBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('pays', 'Pays', 'VARCHAR', false, 100, null);
        $this->addColumn('departement', 'Departement', 'VARCHAR', false, 100, null);
        $this->addColumn('ville', 'Ville', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Utilisateur', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::ONE_TO_MANY, array('id' => 'ip_id', ), null, null, 'Utilisateurs');
    } // buildRelations()

} // IpTableMap
