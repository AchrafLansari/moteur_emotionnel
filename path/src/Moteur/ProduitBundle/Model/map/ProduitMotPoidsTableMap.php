<?php

namespace Moteur\ProduitBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'produit_mot_poids' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Moteur.ProduitBundle.Model.map
 */
class ProduitMotPoidsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.ProduitBundle.Model.map.ProduitMotPoidsTableMap';

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
        $this->setName('produit_mot_poids');
        $this->setPhpName('ProduitMotPoids');
        $this->setClassname('Moteur\\ProduitBundle\\Model\\ProduitMotPoids');
        $this->setPackage('src.Moteur.ProduitBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('mot_id', 'MotId', 'INTEGER' , 'mot', 'id', true, null, null);
        $this->addForeignPrimaryKey('produit_id', 'ProduitId', 'INTEGER' , 'produit', 'id', true, null, null);
        $this->addPrimaryKey('poids', 'Poids', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Mot', 'Moteur\\ProduitBundle\\Model\\Mot', RelationMap::MANY_TO_ONE, array('mot_id' => 'id', ), null, null);
        $this->addRelation('Produit', 'Moteur\\ProduitBundle\\Model\\Produit', RelationMap::MANY_TO_ONE, array('produit_id' => 'id', ), null, null);
    } // buildRelations()

} // ProduitMotPoidsTableMap
