<?php

namespace Moteur\UtilisateurBundle\Model\map;

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
 * @package    propel.generator.src.Moteur.UtilisateurBundle.Model.map
 */
class UtilisateurTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.UtilisateurBundle.Model.map.UtilisateurTableMap';

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
        $this->setClassname('Moteur\\UtilisateurBundle\\Model\\Utilisateur');
        $this->setPackage('src.Moteur.UtilisateurBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('nom', 'Nom', 'VARCHAR', false, 100, null);
        $this->getColumn('nom', false)->setPrimaryString(true);
        $this->addColumn('prenom', 'Prenom', 'VARCHAR', false, 100, null);
        $this->getColumn('prenom', false)->setPrimaryString(true);
        $this->addColumn('mail', 'Mail', 'VARCHAR', false, 100, null);
        $this->getColumn('mail', false)->setPrimaryString(true);
        $this->addColumn('age', 'Age', 'INTEGER', false, null, null);
        $this->addColumn('ville', 'Ville', 'VARCHAR', false, 100, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 255, null);
        $this->addColumn('ip_utilisateur', 'IpUtilisateur', 'VARCHAR', false, 12, null);
        $this->addForeignKey('ip_id', 'IpId', 'INTEGER', 'ip', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Ip', 'Moteur\\UtilisateurBundle\\Model\\Ip', RelationMap::MANY_TO_ONE, array('ip_id' => 'id', ), null, null);
        $this->addRelation('UtilisateurProduit', 'Moteur\\ProduitBundle\\Model\\UtilisateurProduit', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_id', ), null, null, 'UtilisateurProduits');
        $this->addRelation('ProfilScoreUtilisateurRelatedByUtilisateurAId', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateur', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_a_id', ), null, null, 'ProfilScoreUtilisateursRelatedByUtilisateurAId');
        $this->addRelation('ProfilScoreUtilisateurRelatedByUtilisateurBId', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateur', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_b_id', ), null, null, 'ProfilScoreUtilisateursRelatedByUtilisateurBId');
        $this->addRelation('Requete', 'Moteur\\RecommendationBundle\\Model\\Requete', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_id', ), null, null, 'Requetes');
        $this->addRelation('ProfilScoreUtilisateurProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateurProduit', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_id', ), null, null, 'ProfilScoreUtilisateurProduits');
        $this->addRelation('ProfilScoreRequeteUtilisateurProduit', 'Moteur\\RecommendationBundle\\Model\\ProfilScoreRequeteUtilisateurProduit', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_id', ), null, null, 'ProfilScoreRequeteUtilisateurProduits');
        $this->addRelation('UtilisateurInteret', 'Moteur\\UtilisateurBundle\\Model\\UtilisateurInteret', RelationMap::ONE_TO_MANY, array('id' => 'utilisateur_id', ), null, null, 'UtilisateurInterets');
    } // buildRelations()

} // UtilisateurTableMap
