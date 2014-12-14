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
use Moteur\ProduitBundle\Model\Mot;
use Moteur\ProduitBundle\Model\MotPeer;
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\RecommendationBundle\Model\Requete;

/**
 * @method MotQuery orderById($order = Criteria::ASC) Order by the id column
 * @method MotQuery orderByMot($order = Criteria::ASC) Order by the mot column
 *
 * @method MotQuery groupById() Group by the id column
 * @method MotQuery groupByMot() Group by the mot column
 *
 * @method MotQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method MotQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method MotQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method MotQuery leftJoinProduitMotPoids($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProduitMotPoids relation
 * @method MotQuery rightJoinProduitMotPoids($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProduitMotPoids relation
 * @method MotQuery innerJoinProduitMotPoids($relationAlias = null) Adds a INNER JOIN clause to the query using the ProduitMotPoids relation
 *
 * @method MotQuery leftJoinRequete($relationAlias = null) Adds a LEFT JOIN clause to the query using the Requete relation
 * @method MotQuery rightJoinRequete($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Requete relation
 * @method MotQuery innerJoinRequete($relationAlias = null) Adds a INNER JOIN clause to the query using the Requete relation
 *
 * @method Mot findOne(PropelPDO $con = null) Return the first Mot matching the query
 * @method Mot findOneOrCreate(PropelPDO $con = null) Return the first Mot matching the query, or a new Mot object populated from the query conditions when no match is found
 *
 * @method Mot findOneByMot(string $mot) Return the first Mot filtered by the mot column
 *
 * @method array findById(int $id) Return Mot objects filtered by the id column
 * @method array findByMot(string $mot) Return Mot objects filtered by the mot column
 */
abstract class BaseMotQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseMotQuery object.
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
            $modelName = 'Moteur\\ProduitBundle\\Model\\Mot';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new MotQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   MotQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return MotQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof MotQuery) {
            return $criteria;
        }
        $query = new MotQuery(null, null, $modelAlias);

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
     * @return   Mot|Mot[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MotPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(MotPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Mot A model object, or null if the key is not found
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
     * @return                 Mot A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `mot` FROM `mot` WHERE `id` = :p0';
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
            $obj = new Mot();
            $obj->hydrate($row);
            MotPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Mot|Mot[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Mot[]|mixed the list of results, formatted by the current formatter
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
     * @return MotQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MotPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return MotQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MotPeer::ID, $keys, Criteria::IN);
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
     * @return MotQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MotPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MotPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MotPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the mot column
     *
     * Example usage:
     * <code>
     * $query->filterByMot('fooValue');   // WHERE mot = 'fooValue'
     * $query->filterByMot('%fooValue%'); // WHERE mot LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mot The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MotQuery The current query, for fluid interface
     */
    public function filterByMot($mot = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mot)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mot)) {
                $mot = str_replace('*', '%', $mot);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MotPeer::MOT, $mot, $comparison);
    }

    /**
     * Filter the query by a related ProduitMotPoids object
     *
     * @param   ProduitMotPoids|PropelObjectCollection $produitMotPoids  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MotQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProduitMotPoids($produitMotPoids, $comparison = null)
    {
        if ($produitMotPoids instanceof ProduitMotPoids) {
            return $this
                ->addUsingAlias(MotPeer::ID, $produitMotPoids->getMotId(), $comparison);
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
     * @return MotQuery The current query, for fluid interface
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
     * Filter the query by a related Requete object
     *
     * @param   Requete|PropelObjectCollection $requete  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MotQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRequete($requete, $comparison = null)
    {
        if ($requete instanceof Requete) {
            return $this
                ->addUsingAlias(MotPeer::ID, $requete->getMotId(), $comparison);
        } elseif ($requete instanceof PropelObjectCollection) {
            return $this
                ->useRequeteQuery()
                ->filterByPrimaryKeys($requete->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRequete() only accepts arguments of type Requete or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Requete relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MotQuery The current query, for fluid interface
     */
    public function joinRequete($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Requete');

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
            $this->addJoinObject($join, 'Requete');
        }

        return $this;
    }

    /**
     * Use the Requete relation Requete object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\RecommendationBundle\Model\RequeteQuery A secondary query class using the current class as primary query
     */
    public function useRequeteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRequete($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Requete', '\Moteur\RecommendationBundle\Model\RequeteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Mot $mot Object to remove from the list of results
     *
     * @return MotQuery The current query, for fluid interface
     */
    public function prune($mot = null)
    {
        if ($mot) {
            $this->addUsingAlias(MotPeer::ID, $mot->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
