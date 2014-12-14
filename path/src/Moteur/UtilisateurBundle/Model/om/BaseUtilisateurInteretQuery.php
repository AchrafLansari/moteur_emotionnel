<?php

namespace Moteur\UtilisateurBundle\Model\om;

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
use Moteur\UtilisateurBundle\Model\Interet;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurInteret;
use Moteur\UtilisateurBundle\Model\UtilisateurInteretPeer;
use Moteur\UtilisateurBundle\Model\UtilisateurInteretQuery;

/**
 * @method UtilisateurInteretQuery orderByUtilisateurId($order = Criteria::ASC) Order by the utilisateur_id column
 * @method UtilisateurInteretQuery orderByInteretId($order = Criteria::ASC) Order by the interet_id column
 * @method UtilisateurInteretQuery orderByValeur($order = Criteria::ASC) Order by the valeur column
 *
 * @method UtilisateurInteretQuery groupByUtilisateurId() Group by the utilisateur_id column
 * @method UtilisateurInteretQuery groupByInteretId() Group by the interet_id column
 * @method UtilisateurInteretQuery groupByValeur() Group by the valeur column
 *
 * @method UtilisateurInteretQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UtilisateurInteretQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UtilisateurInteretQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UtilisateurInteretQuery leftJoinInteret($relationAlias = null) Adds a LEFT JOIN clause to the query using the Interet relation
 * @method UtilisateurInteretQuery rightJoinInteret($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Interet relation
 * @method UtilisateurInteretQuery innerJoinInteret($relationAlias = null) Adds a INNER JOIN clause to the query using the Interet relation
 *
 * @method UtilisateurInteretQuery leftJoinUtilisateur($relationAlias = null) Adds a LEFT JOIN clause to the query using the Utilisateur relation
 * @method UtilisateurInteretQuery rightJoinUtilisateur($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Utilisateur relation
 * @method UtilisateurInteretQuery innerJoinUtilisateur($relationAlias = null) Adds a INNER JOIN clause to the query using the Utilisateur relation
 *
 * @method UtilisateurInteret findOne(PropelPDO $con = null) Return the first UtilisateurInteret matching the query
 * @method UtilisateurInteret findOneOrCreate(PropelPDO $con = null) Return the first UtilisateurInteret matching the query, or a new UtilisateurInteret object populated from the query conditions when no match is found
 *
 * @method UtilisateurInteret findOneByUtilisateurId(int $utilisateur_id) Return the first UtilisateurInteret filtered by the utilisateur_id column
 * @method UtilisateurInteret findOneByInteretId(int $interet_id) Return the first UtilisateurInteret filtered by the interet_id column
 * @method UtilisateurInteret findOneByValeur(int $valeur) Return the first UtilisateurInteret filtered by the valeur column
 *
 * @method array findByUtilisateurId(int $utilisateur_id) Return UtilisateurInteret objects filtered by the utilisateur_id column
 * @method array findByInteretId(int $interet_id) Return UtilisateurInteret objects filtered by the interet_id column
 * @method array findByValeur(int $valeur) Return UtilisateurInteret objects filtered by the valeur column
 */
abstract class BaseUtilisateurInteretQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUtilisateurInteretQuery object.
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
            $modelName = 'Moteur\\UtilisateurBundle\\Model\\UtilisateurInteret';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UtilisateurInteretQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UtilisateurInteretQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UtilisateurInteretQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UtilisateurInteretQuery) {
            return $criteria;
        }
        $query = new UtilisateurInteretQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$utilisateur_id, $interet_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UtilisateurInteret|UtilisateurInteret[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UtilisateurInteretPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UtilisateurInteretPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UtilisateurInteret A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `utilisateur_id`, `interet_id`, `valeur` FROM `utilisateur_interet` WHERE `utilisateur_id` = :p0 AND `interet_id` = :p1';
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
            $obj = new UtilisateurInteret();
            $obj->hydrate($row);
            UtilisateurInteretPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return UtilisateurInteret|UtilisateurInteret[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UtilisateurInteret[]|mixed the list of results, formatted by the current formatter
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
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UtilisateurInteretPeer::UTILISATEUR_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UtilisateurInteretPeer::INTERET_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UtilisateurInteretPeer::UTILISATEUR_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UtilisateurInteretPeer::INTERET_ID, $key[1], Criteria::EQUAL);
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
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function filterByUtilisateurId($utilisateurId = null, $comparison = null)
    {
        if (is_array($utilisateurId)) {
            $useMinMax = false;
            if (isset($utilisateurId['min'])) {
                $this->addUsingAlias(UtilisateurInteretPeer::UTILISATEUR_ID, $utilisateurId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($utilisateurId['max'])) {
                $this->addUsingAlias(UtilisateurInteretPeer::UTILISATEUR_ID, $utilisateurId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurInteretPeer::UTILISATEUR_ID, $utilisateurId, $comparison);
    }

    /**
     * Filter the query on the interet_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInteretId(1234); // WHERE interet_id = 1234
     * $query->filterByInteretId(array(12, 34)); // WHERE interet_id IN (12, 34)
     * $query->filterByInteretId(array('min' => 12)); // WHERE interet_id >= 12
     * $query->filterByInteretId(array('max' => 12)); // WHERE interet_id <= 12
     * </code>
     *
     * @see       filterByInteret()
     *
     * @param     mixed $interetId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function filterByInteretId($interetId = null, $comparison = null)
    {
        if (is_array($interetId)) {
            $useMinMax = false;
            if (isset($interetId['min'])) {
                $this->addUsingAlias(UtilisateurInteretPeer::INTERET_ID, $interetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($interetId['max'])) {
                $this->addUsingAlias(UtilisateurInteretPeer::INTERET_ID, $interetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurInteretPeer::INTERET_ID, $interetId, $comparison);
    }

    /**
     * Filter the query on the valeur column
     *
     * Example usage:
     * <code>
     * $query->filterByValeur(1234); // WHERE valeur = 1234
     * $query->filterByValeur(array(12, 34)); // WHERE valeur IN (12, 34)
     * $query->filterByValeur(array('min' => 12)); // WHERE valeur >= 12
     * $query->filterByValeur(array('max' => 12)); // WHERE valeur <= 12
     * </code>
     *
     * @param     mixed $valeur The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function filterByValeur($valeur = null, $comparison = null)
    {
        if (is_array($valeur)) {
            $useMinMax = false;
            if (isset($valeur['min'])) {
                $this->addUsingAlias(UtilisateurInteretPeer::VALEUR, $valeur['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($valeur['max'])) {
                $this->addUsingAlias(UtilisateurInteretPeer::VALEUR, $valeur['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurInteretPeer::VALEUR, $valeur, $comparison);
    }

    /**
     * Filter the query by a related Interet object
     *
     * @param   Interet|PropelObjectCollection $interet The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurInteretQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByInteret($interet, $comparison = null)
    {
        if ($interet instanceof Interet) {
            return $this
                ->addUsingAlias(UtilisateurInteretPeer::INTERET_ID, $interet->getId(), $comparison);
        } elseif ($interet instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UtilisateurInteretPeer::INTERET_ID, $interet->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByInteret() only accepts arguments of type Interet or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Interet relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function joinInteret($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Interet');

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
            $this->addJoinObject($join, 'Interet');
        }

        return $this;
    }

    /**
     * Use the Interet relation Interet object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\UtilisateurBundle\Model\InteretQuery A secondary query class using the current class as primary query
     */
    public function useInteretQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInteret($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Interet', '\Moteur\UtilisateurBundle\Model\InteretQuery');
    }

    /**
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurInteretQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateur($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(UtilisateurInteretPeer::UTILISATEUR_ID, $utilisateur->getId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UtilisateurInteretPeer::UTILISATEUR_ID, $utilisateur->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UtilisateurInteretQuery The current query, for fluid interface
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
     * @param   UtilisateurInteret $utilisateurInteret Object to remove from the list of results
     *
     * @return UtilisateurInteretQuery The current query, for fluid interface
     */
    public function prune($utilisateurInteret = null)
    {
        if ($utilisateurInteret) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UtilisateurInteretPeer::UTILISATEUR_ID), $utilisateurInteret->getUtilisateurId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UtilisateurInteretPeer::INTERET_ID), $utilisateurInteret->getInteretId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
