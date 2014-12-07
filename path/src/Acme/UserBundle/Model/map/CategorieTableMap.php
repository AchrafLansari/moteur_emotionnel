<?php

namespace Acme\UserBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'categorie' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Acme.UserBundle.Model.map
 */
class CategorieTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Acme.UserBundle.Model.map.CategorieTableMap';

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
        $this->setName('categorie');
        $this->setPhpName('Categorie');
        $this->setClassname('Acme\\UserBundle\\Model\\Categorie');
        $this->setPackage('src.Acme.UserBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('nom', 'Nom', 'VARCHAR', false, 100, null);
        $this->getColumn('nom', false)->setPrimaryString(true);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Utilisateur', 'Acme\\UserBundle\\Model\\Utilisateur', RelationMap::ONE_TO_MANY, array('id' => 'categorie_id', ), null, null, 'Utilisateurs');
    } // buildRelations()

} // CategorieTableMap
