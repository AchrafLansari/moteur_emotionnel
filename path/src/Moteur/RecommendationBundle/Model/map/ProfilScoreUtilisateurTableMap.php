<?php

namespace Moteur\RecommendationBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'profil_score_utilisateur' table.
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
class ProfilScoreUtilisateurTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.RecommendationBundle.Model.map.ProfilScoreUtilisateurTableMap';

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
        $this->setName('profil_score_utilisateur');
        $this->setPhpName('ProfilScoreUtilisateur');
        $this->setClassname('Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateur');
        $this->setPackage('src.Moteur.RecommendationBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('utilisateur_a_id', 'UtilisateurAId', 'INTEGER' , 'utilisateur', 'id', true, null, null);
        $this->addForeignPrimaryKey('utilisateur_b_id', 'UtilisateurBId', 'INTEGER' , 'utilisateur', 'id', true, null, null);
        $this->addPrimaryKey('score', 'Score', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UtilisateurRelatedByUtilisateurAId', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::MANY_TO_ONE, array('utilisateur_a_id' => 'id', ), null, null);
        $this->addRelation('UtilisateurRelatedByUtilisateurBId', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::MANY_TO_ONE, array('utilisateur_b_id' => 'id', ), null, null);
    } // buildRelations()

} // ProfilScoreUtilisateurTableMap
