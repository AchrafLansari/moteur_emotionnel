<?php

namespace Moteur\RecommendationBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'profil_score_requete_utilisateur_produit' table.
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
class ProfilScoreRequeteUtilisateurProduitTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.RecommendationBundle.Model.map.ProfilScoreRequeteUtilisateurProduitTableMap';

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
        $this->setName('profil_score_requete_utilisateur_produit');
        $this->setPhpName('ProfilScoreRequeteUtilisateurProduit');
        $this->setClassname('Moteur\\RecommendationBundle\\Model\\ProfilScoreRequeteUtilisateurProduit');
        $this->setPackage('src.Moteur.RecommendationBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('requete_id', 'RequeteId', 'INTEGER' , 'requete', 'requete_id', true, null, null);
        $this->addForeignPrimaryKey('utilisateur_id', 'UtilisateurId', 'INTEGER' , 'utilisateur', 'id', true, null, null);
        $this->addForeignPrimaryKey('produit_id', 'ProduitId', 'INTEGER' , 'produit', 'id', true, null, null);
        $this->addPrimaryKey('score', 'Score', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Requete', 'Moteur\\RecommendationBundle\\Model\\Requete', RelationMap::MANY_TO_ONE, array('requete_id' => 'requete_id', ), null, null);
        $this->addRelation('Utilisateur', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::MANY_TO_ONE, array('utilisateur_id' => 'id', ), null, null);
        $this->addRelation('Produit', 'Moteur\\ProduitBundle\\Model\\Produit', RelationMap::MANY_TO_ONE, array('produit_id' => 'id', ), null, null);
    } // buildRelations()

} // ProfilScoreRequeteUtilisateurProduitTableMap
