<?php

namespace Moteur\ProduitBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'utilisateur_produit' table.
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
class UtilisateurProduitTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Moteur.ProduitBundle.Model.map.UtilisateurProduitTableMap';

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
        $this->setName('utilisateur_produit');
        $this->setPhpName('UtilisateurProduit');
        $this->setClassname('Moteur\\ProduitBundle\\Model\\UtilisateurProduit');
        $this->setPackage('src.Moteur.ProduitBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('utilisateur_id', 'UtilisateurId', 'INTEGER' , 'utilisateur', 'id', true, null, null);
        $this->addForeignPrimaryKey('produit_id', 'ProduitId', 'INTEGER' , 'produit', 'id', true, null, null);
        $this->addColumn('note', 'Note', 'INTEGER', false, null, null);
        $this->addColumn('achat', 'Achat', 'BOOLEAN', false, 1, false);
        $this->addColumn('nombre_visite', 'NombreVisite', 'INTEGER', false, null, 0);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Produit', 'Moteur\\ProduitBundle\\Model\\Produit', RelationMap::MANY_TO_ONE, array('produit_id' => 'id', ), null, null);
        $this->addRelation('Utilisateur', 'Moteur\\UtilisateurBundle\\Model\\Utilisateur', RelationMap::MANY_TO_ONE, array('utilisateur_id' => 'id', ), null, null);
    } // buildRelations()

} // UtilisateurProduitTableMap
