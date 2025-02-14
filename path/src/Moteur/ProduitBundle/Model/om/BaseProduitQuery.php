<?php

namespace Moteur\ProduitBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Moteur\ProduitBundle\Model\Produit;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\ProduitBundle\Model\ProduitPeer;
use Moteur\ProduitBundle\Model\ProduitQuery;
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;

/**
 * @method ProduitQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ProduitQuery orderByTitre($order = Criteria::ASC) Order by the titre column
 * @method ProduitQuery orderBySousTitre($order = Criteria::ASC) Order by the sous_titre column
 * @method ProduitQuery orderByAuteur($order = Criteria::ASC) Order by the auteur column
 * @method ProduitQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method ProduitQuery orderByImage($order = Criteria::ASC) Order by the image column
 * @method ProduitQuery orderByLien($order = Criteria::ASC) Order by the lien column
 *
 * @method ProduitQuery groupById() Group by the id column
 * @method ProduitQuery groupByTitre() Group by the titre column
 * @method ProduitQuery groupBySousTitre() Group by the sous_titre column
 * @method ProduitQuery groupByAuteur() Group by the auteur column
 * @method ProduitQuery groupByDescription() Group by the description column
 * @method ProduitQuery groupByImage() Group by the image column
 * @method ProduitQuery groupByLien() Group by the lien column
 *
 * @method ProduitQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ProduitQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ProduitQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ProduitQuery leftJoinProduitMotPoids($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProduitMotPoids relation
 * @method ProduitQuery rightJoinProduitMotPoids($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProduitMotPoids relation
 * @method ProduitQuery innerJoinProduitMotPoids($relationAlias = null) Adds a INNER JOIN clause to the query using the ProduitMotPoids relation
 *
 * @method ProduitQuery leftJoinUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the UtilisateurProduit relation
 * @method ProduitQuery rightJoinUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UtilisateurProduit relation
 * @method ProduitQuery innerJoinUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the UtilisateurProduit relation
 *
 * @method ProduitQuery leftJoinProfilScoreRequeteProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreRequeteProduit relation
 * @method ProduitQuery rightJoinProfilScoreRequeteProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreRequeteProduit relation
 * @method ProduitQuery innerJoinProfilScoreRequeteProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreRequeteProduit relation
 *
 * @method ProduitQuery leftJoinProfilScoreUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
 * @method ProduitQuery rightJoinProfilScoreUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
 * @method ProduitQuery innerJoinProfilScoreUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
 *
 * @method ProduitQuery leftJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 * @method ProduitQuery rightJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 * @method ProduitQuery innerJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 *
 * @method Produit findOne(PropelPDO $con = null) Return the first Produit matching the query
 * @method Produit findOneOrCreate(PropelPDO $con = null) Return the first Produit matching the query, or a new Produit object populated from the query conditions when no match is found
 *
 * @method Produit findOneByTitre(string $titre) Return the first Produit filtered by the titre column
 * @method Produit findOneBySousTitre(string $sous_titre) Return the first Produit filtered by the sous_titre column
 * @method Produit findOneByAuteur(string $auteur) Return the first Produit filtered by the auteur column
 * @method Produit findOneByDescription(string $description) Return the first Produit filtered by the description column
 * @method Produit findOneByImage(string $image) Return the first Produit filtered by the image column
 * @method Produit findOneByLien(string $lien) Return the first Produit filtered by the lien column
 *
 * @method array findById(int $id) Return Produit objects filtered by the id column
 * @method array findByTitre(string $titre) Return Produit objects filtered by the titre column
 * @method array findBySousTitre(string $sous_titre) Return Produit objects filtered by the sous_titre column
 * @method array findByAuteur(string $auteur) Return Produit objects filtered by the auteur column
 * @method array findByDescription(string $description) Return Produit objects filtered by the description column
 * @method array findByImage(string $image) Return Produit objects filtered by the image column
 * @method array findByLien(string $lien) Return Produit objects filtered by the lien column
 */
abstract class BaseProduitQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseProduitQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'symfony';
        }
        if (null === $modelName) {
            $modelName = 'Moteur\\ProduitBundle\\Model\\Produit';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ProduitQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ProduitQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ProduitQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ProduitQuery) {
            return $criteria;
        }
        $query = new ProduitQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Produit|Produit[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProduitPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Produit A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Produit A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `titre`, `sous_titre`, `auteur`, `description`, `image`, `lien` FROM `produit` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Produit();
            $obj->hydrate($row);
            ProduitPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Produit|Produit[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Produit[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ProduitPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ProduitPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ProduitPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ProduitPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProduitPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the titre column
     *
     * Example usage:
     * <code>
     * $query->filterByTitre('fooValue');   // WHERE titre = 'fooValue'
     * $query->filterByTitre('%fooValue%'); // WHERE titre LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titre The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByTitre($titre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titre)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $titre)) {
                $titre = str_replace('*', '%', $titre);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProduitPeer::TITRE, $titre, $comparison);
    }

    /**
     * Filter the query on the sous_titre column
     *
     * Example usage:
     * <code>
     * $query->filterBySousTitre('fooValue');   // WHERE sous_titre = 'fooValue'
     * $query->filterBySousTitre('%fooValue%'); // WHERE sous_titre LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sousTitre The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterBySousTitre($sousTitre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sousTitre)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $sousTitre)) {
                $sousTitre = str_replace('*', '%', $sousTitre);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProduitPeer::SOUS_TITRE, $sousTitre, $comparison);
    }

    /**
     * Filter the query on the auteur column
     *
     * Example usage:
     * <code>
     * $query->filterByAuteur('fooValue');   // WHERE auteur = 'fooValue'
     * $query->filterByAuteur('%fooValue%'); // WHERE auteur LIKE '%fooValue%'
     * </code>
     *
     * @param     string $auteur The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByAuteur($auteur = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($auteur)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $auteur)) {
                $auteur = str_replace('*', '%', $auteur);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProduitPeer::AUTEUR, $auteur, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProduitPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE image = 'fooValue'
     * $query->filterByImage('%fooValue%'); // WHERE image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $image)) {
                $image = str_replace('*', '%', $image);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProduitPeer::IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the lien column
     *
     * Example usage:
     * <code>
     * $query->filterByLien('fooValue');   // WHERE lien = 'fooValue'
     * $query->filterByLien('%fooValue%'); // WHERE lien LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lien The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function filterByLien($lien = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lien)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lien)) {
                $lien = str_replace('*', '%', $lien);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProduitPeer::LIEN, $lien, $comparison);
    }

    /**
     * Filter the query by a related ProduitMotPoids object
     *
     * @param   ProduitMotPoids|PropelObjectCollection $produitMotPoids  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProduitMotPoids($produitMotPoids, $comparison = null)
    {
        if ($produitMotPoids instanceof ProduitMotPoids) {
            return $this
                ->addUsingAlias(ProduitPeer::ID, $produitMotPoids->getProduitId(), $comparison);
        } elseif ($produitMotPoids instanceof PropelObjectCollection) {
            return $this
                ->useProduitMotPoidsQuery()
                ->filterByPrimaryKeys($produitMotPoids->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProduitMotPoids() only accepts arguments of type ProduitMotPoids or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProduitMotPoids relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function joinProduitMotPoids($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProduitMotPoids');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ProduitMotPoids');
        }

        return $this;
    }

    /**
     * Use the ProduitMotPoids relation ProduitMotPoids object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\ProduitBundle\Model\ProduitMotPoidsQuery A secondary query class using the current class as primary query
     */
    public function useProduitMotPoidsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProduitMotPoids($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProduitMotPoids', '\Moteur\ProduitBundle\Model\ProduitMotPoidsQuery');
    }

    /**
     * Filter the query by a related UtilisateurProduit object
     *
     * @param   UtilisateurProduit|PropelObjectCollection $utilisateurProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateurProduit($utilisateurProduit, $comparison = null)
    {
        if ($utilisateurProduit instanceof UtilisateurProduit) {
            return $this
                ->addUsingAlias(ProduitPeer::ID, $utilisateurProduit->getProduitId(), $comparison);
        } elseif ($utilisateurProduit instanceof PropelObjectCollection) {
            return $this
                ->useUtilisateurProduitQuery()
                ->filterByPrimaryKeys($utilisateurProduit->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUtilisateurProduit() only accepts arguments of type UtilisateurProduit or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UtilisateurProduit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function joinUtilisateurProduit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UtilisateurProduit');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UtilisateurProduit');
        }

        return $this;
    }

    /**
     * Use the UtilisateurProduit relation UtilisateurProduit object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\ProduitBundle\Model\UtilisateurProduitQuery A secondary query class using the current class as primary query
     */
    public function useUtilisateurProduitQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUtilisateurProduit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UtilisateurProduit', '\Moteur\ProduitBundle\Model\UtilisateurProduitQuery');
    }

    /**
     * Filter the query by a related ProfilScoreRequeteProduit object
     *
     * @param   ProfilScoreRequeteProduit|PropelObjectCollection $profilScoreRequeteProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreRequeteProduit($profilScoreRequeteProduit, $comparison = null)
    {
        if ($profilScoreRequeteProduit instanceof ProfilScoreRequeteProduit) {
            return $this
                ->addUsingAlias(ProduitPeer::ID, $profilScoreRequeteProduit->getProduitId(), $comparison);
        } elseif ($profilScoreRequeteProduit instanceof PropelObjectCollection) {
            return $this
                ->useProfilScoreRequeteProduitQuery()
                ->filterByPrimaryKeys($profilScoreRequeteProduit->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProfilScoreRequeteProduit() only accepts arguments of type ProfilScoreRequeteProduit or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProfilScoreRequeteProduit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function joinProfilScoreRequeteProduit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProfilScoreRequeteProduit');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ProfilScoreRequeteProduit');
        }

        return $this;
    }

    /**
     * Use the ProfilScoreRequeteProduit relation ProfilScoreRequeteProduit object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduitQuery A secondary query class using the current class as primary query
     */
    public function useProfilScoreRequeteProduitQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfilScoreRequeteProduit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProfilScoreRequeteProduit', '\Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduitQuery');
    }

    /**
     * Filter the query by a related ProfilScoreUtilisateurProduit object
     *
     * @param   ProfilScoreUtilisateurProduit|PropelObjectCollection $profilScoreUtilisateurProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreUtilisateurProduit($profilScoreUtilisateurProduit, $comparison = null)
    {
        if ($profilScoreUtilisateurProduit instanceof ProfilScoreUtilisateurProduit) {
            return $this
                ->addUsingAlias(ProduitPeer::ID, $profilScoreUtilisateurProduit->getProduitId(), $comparison);
        } elseif ($profilScoreUtilisateurProduit instanceof PropelObjectCollection) {
            return $this
                ->useProfilScoreUtilisateurProduitQuery()
                ->filterByPrimaryKeys($profilScoreUtilisateurProduit->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProfilScoreUtilisateurProduit() only accepts arguments of type ProfilScoreUtilisateurProduit or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function joinProfilScoreUtilisateurProduit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProfilScoreUtilisateurProduit');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ProfilScoreUtilisateurProduit');
        }

        return $this;
    }

    /**
     * Use the ProfilScoreUtilisateurProduit relation ProfilScoreUtilisateurProduit object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitQuery A secondary query class using the current class as primary query
     */
    public function useProfilScoreUtilisateurProduitQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfilScoreUtilisateurProduit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProfilScoreUtilisateurProduit', '\Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitQuery');
    }

    /**
     * Filter the query by a related ProfilScoreRequeteUtilisateurProduit object
     *
     * @param   ProfilScoreRequeteUtilisateurProduit|PropelObjectCollection $profilScoreRequeteUtilisateurProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreRequeteUtilisateurProduit($profilScoreRequeteUtilisateurProduit, $comparison = null)
    {
        if ($profilScoreRequeteUtilisateurProduit instanceof ProfilScoreRequeteUtilisateurProduit) {
            return $this
                ->addUsingAlias(ProduitPeer::ID, $profilScoreRequeteUtilisateurProduit->getProduitId(), $comparison);
        } elseif ($profilScoreRequeteUtilisateurProduit instanceof PropelObjectCollection) {
            return $this
                ->useProfilScoreRequeteUtilisateurProduitQuery()
                ->filterByPrimaryKeys($profilScoreRequeteUtilisateurProduit->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProfilScoreRequeteUtilisateurProduit() only accepts arguments of type ProfilScoreRequeteUtilisateurProduit or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function joinProfilScoreRequeteUtilisateurProduit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProfilScoreRequeteUtilisateurProduit');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ProfilScoreRequeteUtilisateurProduit');
        }

        return $this;
    }

    /**
     * Use the ProfilScoreRequeteUtilisateurProduit relation ProfilScoreRequeteUtilisateurProduit object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduitQuery A secondary query class using the current class as primary query
     */
    public function useProfilScoreRequeteUtilisateurProduitQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfilScoreRequeteUtilisateurProduit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProfilScoreRequeteUtilisateurProduit', '\Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduitQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Produit $produit Object to remove from the list of results
     *
     * @return ProduitQuery The current query, for fluid interface
     */
    public function prune($produit = null)
    {
        if ($produit) {
            $this->addUsingAlias(ProduitPeer::ID, $produit->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
