<?php

namespace Acme\UserBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'utilisateur' table.
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
class UtilisateurTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Acme.UserBundle.Model.map.UtilisateurTableMap';

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
        $this->setName('utilisateur');
        $this->setPhpName('Utilisateur');
        $this->setClassname('Acme\\UserBundle\\Model\\Utilisateur');
        $this->setPackage('src.Acme.UserBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('nom', 'Nom', 'VARCHAR', false, 100, null);
        $this->getColumn('nom', false)->setPrimaryString(true);
        $this->addColumn('prenom', 'Prenom', 'VARCHAR', false, 100, null);
        $this->getColumn('prenom', false)->setPrimaryString(true);
        $this->addColumn('age', 'Age', 'DECIMAL', false, null, null);
        $this->addColumn('ville', 'Ville', 'VARCHAR', false, 100, null);
        $this->addColumn('ip', 'Ip', 'DECIMAL', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('categorie_id', 'CategorieId', 'INTEGER', 'categorie', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Categorie', 'Acme\\UserBundle\\Model\\Categorie', RelationMap::MANY_TO_ONE, array('categorie_id' => 'id', ), null, null);
    } // buildRelations()

} // UtilisateurTableMap
