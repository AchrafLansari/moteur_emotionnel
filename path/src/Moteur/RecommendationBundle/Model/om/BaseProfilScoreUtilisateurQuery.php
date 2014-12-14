<?php

namespace Moteur\RecommendationBundle\Model\om;

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
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateur;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurPeer;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;

/**
 * @method ProfilScoreUtilisateurQuery orderByUtilisateurAId($order = Criteria::ASC) Order by the utilisateur_a_id column
 * @method ProfilScoreUtilisateurQuery orderByUtilisateurBId($order = Criteria::ASC) Order by the utilisateur_b_id column
 * @method ProfilScoreUtilisateurQuery orderByScore($order = Criteria::ASC) Order by the score column
 *
 * @method ProfilScoreUtilisateurQuery groupByUtilisateurAId() Group by the utilisateur_a_id column
 * @method ProfilScoreUtilisateurQuery groupByUtilisateurBId() Group by the utilisateur_b_id column
 * @method ProfilScoreUtilisateurQuery groupByScore() Group by the score column
 *
 * @method ProfilScoreUtilisateurQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ProfilScoreUtilisateurQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ProfilScoreUtilisateurQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ProfilScoreUtilisateurQuery leftJoinUtilisateurRelatedByUtilisateurAId($relationAlias = null) Adds a LEFT JOIN clause to the query using the UtilisateurRelatedByUtilisateurAId relation
 * @method ProfilScoreUtilisateurQuery rightJoinUtilisateurRelatedByUtilisateurAId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UtilisateurRelatedByUtilisateurAId relation
 * @method ProfilScoreUtilisateurQuery innerJoinUtilisateurRelatedByUtilisateurAId($relationAlias = null) Adds a INNER JOIN clause to the query using the UtilisateurRelatedByUtilisateurAId relation
 *
 * @method ProfilScoreUtilisateurQuery leftJoinUtilisateurRelatedByUtilisateurBId($relationAlias = null) Adds a LEFT JOIN clause to the query using the UtilisateurRelatedByUtilisateurBId relation
 * @method ProfilScoreUtilisateurQuery rightJoinUtilisateurRelatedByUtilisateurBId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UtilisateurRelatedByUtilisateurBId relation
 * @method ProfilScoreUtilisateurQuery innerJoinUtilisateurRelatedByUtilisateurBId($relationAlias = null) Adds a INNER JOIN clause to the query using the UtilisateurRelatedByUtilisateurBId relation
 *
 * @method ProfilScoreUtilisateur findOne(PropelPDO $con = null) Return the first ProfilScoreUtilisateur matching the query
 * @method ProfilScoreUtilisateur findOneOrCreate(PropelPDO $con = null) Return the first ProfilScoreUtilisateur matching the query, or a new ProfilScoreUtilisateur object populated from the query conditions when no match is found
 *
 * @method ProfilScoreUtilisateur findOneByUtilisateurAId(int $utilisateur_a_id) Return the first ProfilScoreUtilisateur filtered by the utilisateur_a_id column
 * @method ProfilScoreUtilisateur findOneByUtilisateurBId(int $utilisateur_b_id) Return the first ProfilScoreUtilisateur filtered by the utilisateur_b_id column
 * @method ProfilScoreUtilisateur findOneByScore(int $score) Return the first ProfilScoreUtilisateur filtered by the score column
 *
 * @method array findByUtilisateurAId(int $utilisateur_a_id) Return ProfilScoreUtilisateur objects filtered by the utilisateur_a_id column
 * @method array findByUtilisateurBId(int $utilisateur_b_id) Return ProfilScoreUtilisateur objects filtered by the utilisateur_b_id column
 * @method array findByScore(int $score) Return ProfilScoreUtilisateur objects filtered by the score column
 */
abstract class BaseProfilScoreUtilisateurQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseProfilScoreUtilisateurQuery object.
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
            $modelName = 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateur';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ProfilScoreUtilisateurQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ProfilScoreUtilisateurQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ProfilScoreUtilisateurQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ProfilScoreUtilisateurQuery) {
            return $criteria;
        }
        $query = new ProfilScoreUtilisateurQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$utilisateur_a_id, $utilisateur_b_id, $score]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   ProfilScoreUtilisateur|ProfilScoreUtilisateur[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProfilScoreUtilisateurPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ProfilScoreUtilisateur A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `utilisateur_a_id`, `utilisateur_b_id`, `score` FROM `profil_score_utilisateur` WHERE `utilisateur_a_id` = :p0 AND `utilisateur_b_id` = :p1 AND `score` = :p2';
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
            $obj = new ProfilScoreUtilisateur();
            $obj->hydrate($row);
            ProfilScoreUtilisateurPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ProfilScoreUtilisateur|ProfilScoreUtilisateur[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ProfilScoreUtilisateur[]|mixed the list of results, formatted by the current formatter
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
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(ProfilScoreUtilisateurPeer::SCORE, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(ProfilScoreUtilisateurPeer::SCORE, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the utilisateur_a_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUtilisateurAId(1234); // WHERE utilisateur_a_id = 1234
     * $query->filterByUtilisateurAId(array(12, 34)); // WHERE utilisateur_a_id IN (12, 34)
     * $query->filterByUtilisateurAId(array('min' => 12)); // WHERE utilisateur_a_id >= 12
     * $query->filterByUtilisateurAId(array('max' => 12)); // WHERE utilisateur_a_id <= 12
     * </code>
     *
     * @see       filterByUtilisateurRelatedByUtilisateurAId()
     *
     * @param     mixed $utilisateurAId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function filterByUtilisateurAId($utilisateurAId = null, $comparison = null)
    {
        if (is_array($utilisateurAId)) {
            $useMinMax = false;
            if (isset($utilisateurAId['min'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $utilisateurAId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($utilisateurAId['max'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $utilisateurAId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $utilisateurAId, $comparison);
    }

    /**
     * Filter the query on the utilisateur_b_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUtilisateurBId(1234); // WHERE utilisateur_b_id = 1234
     * $query->filterByUtilisateurBId(array(12, 34)); // WHERE utilisateur_b_id IN (12, 34)
     * $query->filterByUtilisateurBId(array('min' => 12)); // WHERE utilisateur_b_id >= 12
     * $query->filterByUtilisateurBId(array('max' => 12)); // WHERE utilisateur_b_id <= 12
     * </code>
     *
     * @see       filterByUtilisateurRelatedByUtilisateurBId()
     *
     * @param     mixed $utilisateurBId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function filterByUtilisateurBId($utilisateurBId = null, $comparison = null)
    {
        if (is_array($utilisateurBId)) {
            $useMinMax = false;
            if (isset($utilisateurBId['min'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $utilisateurBId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($utilisateurBId['max'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $utilisateurBId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $utilisateurBId, $comparison);
    }

    /**
     * Filter the query on the score column
     *
     * Example usage:
     * <code>
     * $query->filterByScore(1234); // WHERE score = 1234
     * $query->filterByScore(array(12, 34)); // WHERE score IN (12, 34)
     * $query->filterByScore(array('min' => 12)); // WHERE score >= 12
     * $query->filterByScore(array('max' => 12)); // WHERE score <= 12
     * </code>
     *
     * @param     mixed $score The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurPeer::SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurPeer::SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilScoreUtilisateurPeer::SCORE, $score, $comparison);
    }

    /**
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProfilScoreUtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateurRelatedByUtilisateurAId($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $utilisateur->getId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID, $utilisateur->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUtilisateurRelatedByUtilisateurAId() only accepts arguments of type Utilisateur or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UtilisateurRelatedByUtilisateurAId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function joinUtilisateurRelatedByUtilisateurAId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UtilisateurRelatedByUtilisateurAId');

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
            $this->addJoinObject($join, 'UtilisateurRelatedByUtilisateurAId');
        }

        return $this;
    }

    /**
     * Use the UtilisateurRelatedByUtilisateurAId relation Utilisateur object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\UtilisateurBundle\Model\UtilisateurQuery A secondary query class using the current class as primary query
     */
    public function useUtilisateurRelatedByUtilisateurAIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUtilisateurRelatedByUtilisateurAId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UtilisateurRelatedByUtilisateurAId', '\Moteur\UtilisateurBundle\Model\UtilisateurQuery');
    }

    /**
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProfilScoreUtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateurRelatedByUtilisateurBId($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $utilisateur->getId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID, $utilisateur->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUtilisateurRelatedByUtilisateurBId() only accepts arguments of type Utilisateur or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UtilisateurRelatedByUtilisateurBId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function joinUtilisateurRelatedByUtilisateurBId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UtilisateurRelatedByUtilisateurBId');

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
            $this->addJoinObject($join, 'UtilisateurRelatedByUtilisateurBId');
        }

        return $this;
    }

    /**
     * Use the UtilisateurRelatedByUtilisateurBId relation Utilisateur object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\UtilisateurBundle\Model\UtilisateurQuery A secondary query class using the current class as primary query
     */
    public function useUtilisateurRelatedByUtilisateurBIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUtilisateurRelatedByUtilisateurBId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UtilisateurRelatedByUtilisateurBId', '\Moteur\UtilisateurBundle\Model\UtilisateurQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ProfilScoreUtilisateur $profilScoreUtilisateur Object to remove from the list of results
     *
     * @return ProfilScoreUtilisateurQuery The current query, for fluid interface
     */
    public function prune($profilScoreUtilisateur = null)
    {
        if ($profilScoreUtilisateur) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ProfilScoreUtilisateurPeer::UTILISATEUR_A_ID), $profilScoreUtilisateur->getUtilisateurAId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ProfilScoreUtilisateurPeer::UTILISATEUR_B_ID), $profilScoreUtilisateur->getUtilisateurBId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(ProfilScoreUtilisateurPeer::SCORE), $profilScoreUtilisateur->getScore(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
