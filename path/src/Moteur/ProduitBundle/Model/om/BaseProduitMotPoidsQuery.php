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
use Moteur\ProduitBundle\Model\Produit;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\ProduitBundle\Model\ProduitMotPoidsPeer;
use Moteur\ProduitBundle\Model\ProduitMotPoidsQuery;

/**
 * @method ProduitMotPoidsQuery orderByMotId($order = Criteria::ASC) Order by the mot_id column
 * @method ProduitMotPoidsQuery orderByProduitId($order = Criteria::ASC) Order by the produit_id column
 * @method ProduitMotPoidsQuery orderByPoids($order = Criteria::ASC) Order by the poids column
 *
 * @method ProduitMotPoidsQuery groupByMotId() Group by the mot_id column
 * @method ProduitMotPoidsQuery groupByProduitId() Group by the produit_id column
 * @method ProduitMotPoidsQuery groupByPoids() Group by the poids column
 *
 * @method ProduitMotPoidsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ProduitMotPoidsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ProduitMotPoidsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ProduitMotPoidsQuery leftJoinMot($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mot relation
 * @method ProduitMotPoidsQuery rightJoinMot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mot relation
 * @method ProduitMotPoidsQuery innerJoinMot($relationAlias = null) Adds a INNER JOIN clause to the query using the Mot relation
 *
 * @method ProduitMotPoidsQuery leftJoinProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Produit relation
 * @method ProduitMotPoidsQuery rightJoinProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Produit relation
 * @method ProduitMotPoidsQuery innerJoinProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the Produit relation
 *
 * @method ProduitMotPoids findOne(PropelPDO $con = null) Return the first ProduitMotPoids matching the query
 * @method ProduitMotPoids findOneOrCreate(PropelPDO $con = null) Return the first ProduitMotPoids matching the query, or a new ProduitMotPoids object populated from the query conditions when no match is found
 *
 * @method ProduitMotPoids findOneByMotId(int $mot_id) Return the first ProduitMotPoids filtered by the mot_id column
 * @method ProduitMotPoids findOneByProduitId(int $produit_id) Return the first ProduitMotPoids filtered by the produit_id column
 * @method ProduitMotPoids findOneByPoids(int $poids) Return the first ProduitMotPoids filtered by the poids column
 *
 * @method array findByMotId(int $mot_id) Return ProduitMotPoids objects filtered by the mot_id column
 * @method array findByProduitId(int $produit_id) Return ProduitMotPoids objects filtered by the produit_id column
 * @method array findByPoids(int $poids) Return ProduitMotPoids objects filtered by the poids column
 */
abstract class BaseProduitMotPoidsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseProduitMotPoidsQuery object.
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
            $modelName = 'Moteur\\ProduitBundle\\Model\\ProduitMotPoids';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ProduitMotPoidsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ProduitMotPoidsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ProduitMotPoidsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ProduitMotPoidsQuery) {
            return $criteria;
        }
        $query = new ProduitMotPoidsQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$mot_id, $produit_id, $poids]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   ProduitMotPoids|ProduitMotPoids[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProduitMotPoidsPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ProduitMotPoidsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ProduitMotPoids A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `mot_id`, `produit_id`, `poids` FROM `produit_mot_poids` WHERE `mot_id` = :p0 AND `produit_id` = :p1 AND `poids` = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new ProduitMotPoids();
            $obj->hydrate($row);
            ProduitMotPoidsPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ProduitMotPoids|ProduitMotPoids[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ProduitMotPoids[]|mixed the list of results, formatted by the current formatter
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
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ProduitMotPoidsPeer::MOT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ProduitMotPoidsPeer::PRODUIT_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(ProduitMotPoidsPeer::POIDS, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ProduitMotPoidsPeer::MOT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ProduitMotPoidsPeer::PRODUIT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(ProduitMotPoidsPeer::POIDS, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the mot_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMotId(1234); // WHERE mot_id = 1234
     * $query->filterByMotId(array(12, 34)); // WHERE mot_id IN (12, 34)
     * $query->filterByMotId(array('min' => 12)); // WHERE mot_id >= 12
     * $query->filterByMotId(array('max' => 12)); // WHERE mot_id <= 12
     * </code>
     *
     * @see       filterByMot()
     *
     * @param     mixed $motId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function filterByMotId($motId = null, $comparison = null)
    {
        if (is_array($motId)) {
            $useMinMax = false;
            if (isset($motId['min'])) {
                $this->addUsingAlias(ProduitMotPoidsPeer::MOT_ID, $motId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($motId['max'])) {
                $this->addUsingAlias(ProduitMotPoidsPeer::MOT_ID, $motId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProduitMotPoidsPeer::MOT_ID, $motId, $comparison);
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
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function filterByProduitId($produitId = null, $comparison = null)
    {
        if (is_array($produitId)) {
            $useMinMax = false;
            if (isset($produitId['min'])) {
                $this->addUsingAlias(ProduitMotPoidsPeer::PRODUIT_ID, $produitId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($produitId['max'])) {
                $this->addUsingAlias(ProduitMotPoidsPeer::PRODUIT_ID, $produitId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProduitMotPoidsPeer::PRODUIT_ID, $produitId, $comparison);
    }

    /**
     * Filter the query on the poids column
     *
     * Example usage:
     * <code>
     * $query->filterByPoids(1234); // WHERE poids = 1234
     * $query->filterByPoids(array(12, 34)); // WHERE poids IN (12, 34)
     * $query->filterByPoids(array('min' => 12)); // WHERE poids >= 12
     * $query->filterByPoids(array('max' => 12)); // WHERE poids <= 12
     * </code>
     *
     * @param     mixed $poids The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function filterByPoids($poids = null, $comparison = null)
    {
        if (is_array($poids)) {
            $useMinMax = false;
            if (isset($poids['min'])) {
                $this->addUsingAlias(ProduitMotPoidsPeer::POIDS, $poids['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($poids['max'])) {
                $this->addUsingAlias(ProduitMotPoidsPeer::POIDS, $poids['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProduitMotPoidsPeer::POIDS, $poids, $comparison);
    }

    /**
     * Filter the query by a related Mot object
     *
     * @param   Mot|PropelObjectCollection $mot The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitMotPoidsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMot($mot, $comparison = null)
    {
        if ($mot instanceof Mot) {
            return $this
                ->addUsingAlias(ProduitMotPoidsPeer::MOT_ID, $mot->getId(), $comparison);
        } elseif ($mot instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProduitMotPoidsPeer::MOT_ID, $mot->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMot() only accepts arguments of type Mot or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Mot relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function joinMot($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Mot');

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
            $this->addJoinObject($join, 'Mot');
        }

        return $this;
    }

    /**
     * Use the Mot relation Mot object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\ProduitBundle\Model\MotQuery A secondary query class using the current class as primary query
     */
    public function useMotQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMot($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Mot', '\Moteur\ProduitBundle\Model\MotQuery');
    }

    /**
     * Filter the query by a related Produit object
     *
     * @param   Produit|PropelObjectCollection $produit The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProduitMotPoidsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProduit($produit, $comparison = null)
    {
        if ($produit instanceof Produit) {
            return $this
                ->addUsingAlias(ProduitMotPoidsPeer::PRODUIT_ID, $produit->getId(), $comparison);
        } elseif ($produit instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProduitMotPoidsPeer::PRODUIT_ID, $produit->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ProduitMotPoidsQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ProduitMotPoids $produitMotPoids Object to remove from the list of results
     *
     * @return ProduitMotPoidsQuery The current query, for fluid interface
     */
    public function prune($produitMotPoids = null)
    {
        if ($produitMotPoids) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ProduitMotPoidsPeer::MOT_ID), $produitMotPoids->getMotId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ProduitMotPoidsPeer::PRODUIT_ID), $produitMotPoids->getProduitId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(ProduitMotPoidsPeer::POIDS), $produitMotPoids->getPoids(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
