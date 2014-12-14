<?php

namespace Moteur\ProduitBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'produit' table.
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
class ProduitTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.ProduitBundle.Model.map.ProduitTableMap';

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
        $this->setName('produit');
        $this->setPhpName('Produit');
        $this->setClassname('Moteur\\ProduitBundle\\Model\\Produit');
        $this->setPackage('src.Moteur.ProduitBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('titre', 'Titre', 'VARCHAR', false, 100, null);
        $this->addColumn('auteur', 'Auteur', 'VARCHAR', false, 100, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 1000, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ProduitMotPoids', 'Moteur\\ProduitBundle\\Model\\ProduitMotPoids', RelationMap::ONE_TO_MANY, array('id' => 'produit_id', ), null, null, 'ProduitMotPoidss');
        $this->addRelation('ProfilScoreRequeteProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreRequeteProduit', RelationMap::ONE_TO_MANY, array('id' => 'produit_id', ), null, null, 'ProfilScoreRequeteProduits');
        $this->addRelation('ProfilScoreUtilisateurProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateurProduit', RelationMap::ONE_TO_MANY, array('id' => 'produit_id', ), null, null, 'ProfilScoreUtilisateurProduits');
        $this->addRelation('ProfilScoreRequeteUtilisateurProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreRequeteUtilisateurProduit', RelationMap::ONE_TO_MANY, array('id' => 'produit_id', ), null, null, 'ProfilScoreRequeteUtilisateurProduits');
    } // buildRelations()

} // ProduitTableMap
