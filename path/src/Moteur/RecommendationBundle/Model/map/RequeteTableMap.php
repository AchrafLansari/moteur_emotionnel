<?php

namespace Moteur\RecommendationBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'requete' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Moteur.RecommendationBundle.Model.map
 */
class RequeteTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.RecommendationBundle.Model.map.RequeteTableMap';

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
        $this->setName('requete');
        $this->setPhpName('Requete');
        $this->setClassname('Moteur\\RecommendationBundle\\Model\\Requete');
        $this->setPackage('src.Moteur.RecommendationBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('requete_id', 'RequeteId', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('mot_id', 'MotId', 'INTEGER' , 'mot', 'id', true, null, null);
        $this->addForeignPrimaryKey('utilisateur_id', 'UtilisateurId', 'INTEGER' , 'utilisateur', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Mot', 'Moteur\\ProduitBundle\\Model\\Mot', RelationMap::MANY_TO_ONE, array('mot_id' => 'id', ), null, null);
        $this->addRelation('Utilisateur', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::MANY_TO_ONE, array('utilisateur_id' => 'id', ), null, null);
        $this->addRelation('ProfilScoreRequeteProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreRequeteProduit', RelationMap::ONE_TO_MANY, array('requete_id' => 'requete_id', ), null, null, 'ProfilScoreRequeteProduits');
        $this->addRelation('ProfilScoreRequeteUtilisateurProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreRequeteUtilisateurProduit', RelationMap::ONE_TO_MANY, array('requete_id' => 'requete_id', ), null, null, 'ProfilScoreRequeteUtilisateurProduits');
    } // buildRelations()

} // RequeteTableMap
