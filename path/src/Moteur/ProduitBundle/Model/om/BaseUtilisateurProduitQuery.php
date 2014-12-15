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
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\ProduitBundle\Model\UtilisateurProduitPeer;
use Moteur\ProduitBundle\Model\UtilisateurProduitQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;

/**
 * @method UtilisateurProduitQuery orderByUtilisateurId($order = Criteria::ASC) Order by the utilisateur_id column
 * @method UtilisateurProduitQuery orderByProduitId($order = Criteria::ASC) Order by the produit_id column
 * @method UtilisateurProduitQuery orderByNote($order = Criteria::ASC) Order by the note column
 * @method UtilisateurProduitQuery orderByAchat($order = Criteria::ASC) Order by the achat column
 * @method UtilisateurProduitQuery orderByNombreVisite($order = Criteria::ASC) Order by the nombre_visite column
 *
 * @method UtilisateurProduitQuery groupByUtilisateurId() Group by the utilisateur_id column
 * @method UtilisateurProduitQuery groupByProduitId() Group by the produit_id column
 * @method UtilisateurProduitQuery groupByNote() Group by the note column
 * @method UtilisateurProduitQuery groupByAchat() Group by the achat column
 * @method UtilisateurProduitQuery groupByNombreVisite() Group by the nombre_visite column
 *
 * @method UtilisateurProduitQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UtilisateurProduitQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UtilisateurProduitQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UtilisateurProduitQuery leftJoinProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Produit relation
 * @method UtilisateurProduitQuery rightJoinProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Produit relation
 * @method UtilisateurProduitQuery innerJoinProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the Produit relation
 *
 * @method UtilisateurProduitQuery leftJoinUtilisateur($relationAlias = null) Adds a LEFT JOIN clause to the query using the Utilisateur relation
 * @method UtilisateurProduitQuery rightJoinUtilisateur($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Utilisateur relation
 * @method UtilisateurProduitQuery innerJoinUtilisateur($relationAlias = null) Adds a INNER JOIN clause to the query using the Utilisateur relation
 *
 * @method UtilisateurProduit findOne(PropelPDO $con = null) Return the first UtilisateurProduit matching the query
 * @method UtilisateurProduit findOneOrCreate(PropelPDO $con = null) Return the first UtilisateurProduit matching the query, or a new UtilisateurProduit object populated from the query conditions when no match is found
 *
 * @method UtilisateurProduit findOneByUtilisateurId(int $utilisateur_id) Return the first UtilisateurProduit filtered by the utilisateur_id column
 * @method UtilisateurProduit findOneByProduitId(int $produit_id) Return the first UtilisateurProduit filtered by the produit_id column
 * @method UtilisateurProduit findOneByNote(int $note) Return the first UtilisateurProduit filtered by the note column
 * @method UtilisateurProduit findOneByAchat(boolean $achat) Return the first UtilisateurProduit filtered by the achat column
 * @method UtilisateurProduit findOneByNombreVisite(int $nombre_visite) Return the first UtilisateurProduit filtered by the nombre_visite column
 *
 * @method array findByUtilisateurId(int $utilisateur_id) Return UtilisateurProduit objects filtered by the utilisateur_id column
 * @method array findByProduitId(int $produit_id) Return UtilisateurProduit objects filtered by the produit_id column
 * @method array findByNote(int $note) Return UtilisateurProduit objects filtered by the note column
 * @method array findByAchat(boolean $achat) Return UtilisateurProduit objects filtered by the achat column
 * @method array findByNombreVisite(int $nombre_visite) Return UtilisateurProduit objects filtered by the nombre_visite column
 */
abstract class BaseUtilisateurProduitQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUtilisateurProduitQuery object.
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
            $modelName = 'Moteur\\ProduitBundle\\Model\\UtilisateurProduit';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UtilisateurProduitQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UtilisateurProduitQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UtilisateurProduitQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UtilisateurProduitQuery) {
            return $criteria;
        }
        $query = new UtilisateurProduitQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$utilisateur_id, $produit_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UtilisateurProduit|UtilisateurProduit[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UtilisateurProduitPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 UtilisateurProduit A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `utilisateur_id`, `produit_id`, `note`, `achat`, `nombre_visite` FROM `utilisateur_produit` WHERE `utilisateur_id` = :p0 AND `produit_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new UtilisateurProduit();
            $obj->hydrate($row);
            UtilisateurProduitPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return UtilisateurProduit|UtilisateurProduit[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|UtilisateurProduit[]|mixed the list of results, formatted by the current formatter
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
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UtilisateurProduitPeer::UTILISATEUR_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UtilisateurProduitPeer::PRODUIT_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UtilisateurProduitPeer::UTILISATEUR_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UtilisateurProduitPeer::PRODUIT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the utilisateur_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUtilisateurId(1234); // WHERE utilisateur_id = 1234
     * $query->filterByUtilisateurId(array(12, 34)); // WHERE utilisateur_id IN (12, 34)
     * $query->filterByUtilisateurId(array('min' => 12)); // WHERE utilisateur_id >= 12
     * $query->filterByUtilisateurId(array('max' => 12)); // WHERE utilisateur_id <= 12
     * </code>
     *
     * @see       filterByUtilisateur()
     *
     * @param     mixed $utilisateurId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByUtilisateurId($utilisateurId = null, $comparison = null)
    {
        if (is_array($utilisateurId)) {
            $useMinMax = false;
            if (isset($utilisateurId['min'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::UTILISATEUR_ID, $utilisateurId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($utilisateurId['max'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::UTILISATEUR_ID, $utilisateurId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurProduitPeer::UTILISATEUR_ID, $utilisateurId, $comparison);
    }

    /**
     * Filter the query on the produit_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProduitId(1234); // WHERE produit_id = 1234
     * $query->filterByProduitId(array(12, 34)); // WHERE produit_id IN (12, 34)
     * $query->filterByProduitId(array('min' => 12)); // WHERE produit_id >= 12
     * $query->filterByProduitId(array('max' => 12)); // WHERE produit_id <= 12
     * </code>
     *
     * @see       filterByProduit()
     *
     * @param     mixed $produitId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByProduitId($produitId = null, $comparison = null)
    {
        if (is_array($produitId)) {
            $useMinMax = false;
            if (isset($produitId['min'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::PRODUIT_ID, $produitId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($produitId['max'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::PRODUIT_ID, $produitId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurProduitPeer::PRODUIT_ID, $produitId, $comparison);
    }

    /**
     * Filter the query on the note column
     *
     * Example usage:
     * <code>
     * $query->filterByNote(1234); // WHERE note = 1234
     * $query->filterByNote(array(12, 34)); // WHERE note IN (12, 34)
     * $query->filterByNote(array('min' => 12)); // WHERE note >= 12
     * $query->filterByNote(array('max' => 12)); // WHERE note <= 12
     * </code>
     *
     * @param     mixed $note The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByNote($note = null, $comparison = null)
    {
        if (is_array($note)) {
            $useMinMax = false;
            if (isset($note['min'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::NOTE, $note['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($note['max'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::NOTE, $note['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurProduitPeer::NOTE, $note, $comparison);
    }

    /**
     * Filter the query on the achat column
     *
     * Example usage:
     * <code>
     * $query->filterByAchat(true); // WHERE achat = true
     * $query->filterByAchat('yes'); // WHERE achat = true
     * </code>
     *
     * @param     boolean|string $achat The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByAchat($achat = null, $comparison = null)
    {
        if (is_string($achat)) {
            $achat = in_array(strtolower($achat), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UtilisateurProduitPeer::ACHAT, $achat, $comparison);
    }

    /**
     * Filter the query on the nombre_visite column
     *
     * Example usage:
     * <code>
     * $query->filterByNombreVisite(1234); // WHERE nombre_visite = 1234
     * $query->filterByNombreVisite(array(12, 34)); // WHERE nombre_visite IN (12, 34)
     * $query->filterByNombreVisite(array('min' => 12)); // WHERE nombre_visite >= 12
     * $query->filterByNombreVisite(array('max' => 12)); // WHERE nombre_visite <= 12
     * </code>
     *
     * @param     mixed $nombreVisite The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByNombreVisite($nombreVisite = null, $comparison = null)
    {
        if (is_array($nombreVisite)) {
            $useMinMax = false;
            if (isset($nombreVisite['min'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::NOMBRE_VISITE, $nombreVisite['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nombreVisite['max'])) {
                $this->addUsingAlias(UtilisateurProduitPeer::NOMBRE_VISITE, $nombreVisite['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurProduitPeer::NOMBRE_VISITE, $nombreVisite, $comparison);
    }

    /**
     * Filter the query by a related Produit object
     *
     * @param   Produit|PropelObjectCollection $produit The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProduit($produit, $comparison = null)
    {
        if ($produit instanceof Produit) {
            return $this
                ->addUsingAlias(UtilisateurProduitPeer::PRODUIT_ID, $produit->getId(), $comparison);
        } elseif ($produit instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UtilisateurProduitPeer::PRODUIT_ID, $produit->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProduit() only accepts arguments of type Produit or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Produit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function joinProduit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Produit');

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
            $this->addJoinObject($join, 'Produit');
        }

        return $this;
    }

    /**
     * Use the Produit relation Produit object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\ProduitBundle\Model\ProduitQuery A secondary query class using the current class as primary query
     */
    public function useProduitQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProduit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Produit', '\Moteur\ProduitBundle\Model\ProduitQuery');
    }

    /**
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateur($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(UtilisateurProduitPeer::UTILISATEUR_ID, $utilisateur->getId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UtilisateurProduitPeer::UTILISATEUR_ID, $utilisateur->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUtilisateur() only accepts arguments of type Utilisateur or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Utilisateur relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function joinUtilisateur($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Utilisateur');

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
            $this->addJoinObject($join, 'Utilisateur');
        }

        return $this;
    }

    /**
     * Use the Utilisateur relation Utilisateur object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\UtilisateurBundle\Model\UtilisateurQuery A secondary query class using the current class as primary query
     */
    public function useUtilisateurQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUtilisateur($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Utilisateur', '\Moteur\UtilisateurBundle\Model\UtilisateurQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UtilisateurProduit $utilisateurProduit Object to remove from the list of results
     *
     * @return UtilisateurProduitQuery The current query, for fluid interface
     */
    public function prune($utilisateurProduit = null)
    {
        if ($utilisateurProduit) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UtilisateurProduitPeer::UTILISATEUR_ID), $utilisateurProduit->getUtilisateurId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UtilisateurProduitPeer::PRODUIT_ID), $utilisateurProduit->getProduitId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
