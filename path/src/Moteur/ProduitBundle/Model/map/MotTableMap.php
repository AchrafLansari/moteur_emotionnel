<?php

namespace Moteur\ProduitBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'mot' table.
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
class MotTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.ProduitBundle.Model.map.MotTableMap';

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
        $this->setName('mot');
        $this->setPhpName('Mot');
        $this->setClassname('Moteur\\ProduitBundle\\Model\\Mot');
        $this->setPackage('src.Moteur.ProduitBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('mot', 'Mot', 'VARCHAR', false, 30, null);
        $this->getColumn('mot', false)->setPrimaryString(true);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ProduitMotPoids', 'Moteur\\ProduitBundle\\Model\\ProduitMotPoids', RelationMap::ONE_TO_MANY, array('id' => 'mot_id', ), null, null, 'ProduitMotPoidss');
        $this->addRelation('Requete', 'Moteur\\RecommendationBundle\\Model\\Requete', RelationMap::ONE_TO_MANY, array('id' => 'mot_id', ), null, null, 'Requetes');
    } // buildRelations()

} // MotTableMap
