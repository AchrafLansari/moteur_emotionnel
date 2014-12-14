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
use Moteur\ProduitBundle\Model\Produit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitPeer;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;

/**
 * @method ProfilScoreUtilisateurProduitQuery orderByUtilisateurId($order = Criteria::ASC) Order by the utilisateur_id column
 * @method ProfilScoreUtilisateurProduitQuery orderByProduitId($order = Criteria::ASC) Order by the produit_id column
 * @method ProfilScoreUtilisateurProduitQuery orderByScore($order = Criteria::ASC) Order by the score column
 *
 * @method ProfilScoreUtilisateurProduitQuery groupByUtilisateurId() Group by the utilisateur_id column
 * @method ProfilScoreUtilisateurProduitQuery groupByProduitId() Group by the produit_id column
 * @method ProfilScoreUtilisateurProduitQuery groupByScore() Group by the score column
 *
 * @method ProfilScoreUtilisateurProduitQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ProfilScoreUtilisateurProduitQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ProfilScoreUtilisateurProduitQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ProfilScoreUtilisateurProduitQuery leftJoinUtilisateur($relationAlias = null) Adds a LEFT JOIN clause to the query using the Utilisateur relation
 * @method ProfilScoreUtilisateurProduitQuery rightJoinUtilisateur($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Utilisateur relation
 * @method ProfilScoreUtilisateurProduitQuery innerJoinUtilisateur($relationAlias = null) Adds a INNER JOIN clause to the query using the Utilisateur relation
 *
 * @method ProfilScoreUtilisateurProduitQuery leftJoinProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Produit relation
 * @method ProfilScoreUtilisateurProduitQuery rightJoinProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Produit relation
 * @method ProfilScoreUtilisateurProduitQuery innerJoinProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the Produit relation
 *
 * @method ProfilScoreUtilisateurProduit findOne(PropelPDO $con = null) Return the first ProfilScoreUtilisateurProduit matching the query
 * @method ProfilScoreUtilisateurProduit findOneOrCreate(PropelPDO $con = null) Return the first ProfilScoreUtilisateurProduit matching the query, or a new ProfilScoreUtilisateurProduit object populated from the query conditions when no match is found
 *
 * @method ProfilScoreUtilisateurProduit findOneByUtilisateurId(int $utilisateur_id) Return the first ProfilScoreUtilisateurProduit filtered by the utilisateur_id column
 * @method ProfilScoreUtilisateurProduit findOneByProduitId(int $produit_id) Return the first ProfilScoreUtilisateurProduit filtered by the produit_id column
 * @method ProfilScoreUtilisateurProduit findOneByScore(int $score) Return the first ProfilScoreUtilisateurProduit filtered by the score column
 *
 * @method array findByUtilisateurId(int $utilisateur_id) Return ProfilScoreUtilisateurProduit objects filtered by the utilisateur_id column
 * @method array findByProduitId(int $produit_id) Return ProfilScoreUtilisateurProduit objects filtered by the produit_id column
 * @method array findByScore(int $score) Return ProfilScoreUtilisateurProduit objects filtered by the score column
 */
abstract class BaseProfilScoreUtilisateurProduitQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseProfilScoreUtilisateurProduitQuery object.
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
            $modelName = 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateurProduit';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ProfilScoreUtilisateurProduitQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ProfilScoreUtilisateurProduitQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ProfilScoreUtilisateurProduitQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ProfilScoreUtilisateurProduitQuery) {
            return $criteria;
        }
        $query = new ProfilScoreUtilisateurProduitQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$utilisateur_id, $produit_id, $score]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   ProfilScoreUtilisateurProduit|ProfilScoreUtilisateurProduit[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ProfilScoreUtilisateurProduit A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `utilisateur_id`, `produit_id`, `score` FROM `profil_score_utilisateur_produit` WHERE `utilisateur_id` = :p0 AND `produit_id` = :p1 AND `score` = :p2';
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
            $obj = new ProfilScoreUtilisateurProduit();
            $obj->hydrate($row);
            ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ProfilScoreUtilisateurProduit|ProfilScoreUtilisateurProduit[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ProfilScoreUtilisateurProduit[]|mixed the list of results, formatted by the current formatter
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
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::SCORE, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(ProfilScoreUtilisateurProduitPeer::SCORE, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
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
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByUtilisateurId($utilisateurId = null, $comparison = null)
    {
        if (is_array($utilisateurId)) {
            $useMinMax = false;
            if (isset($utilisateurId['min'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $utilisateurId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($utilisateurId['max'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $utilisateurId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $utilisateurId, $comparison);
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
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByProduitId($produitId = null, $comparison = null)
    {
        if (is_array($produitId)) {
            $useMinMax = false;
            if (isset($produitId['min'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $produitId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($produitId['max'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $produitId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $produitId, $comparison);
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
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilScoreUtilisateurProduitPeer::SCORE, $score, $comparison);
    }

    /**
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateur($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $utilisateur->getId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $utilisateur->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
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
     * Filter the query by a related Produit object
     *
     * @param   Produit|PropelObjectCollection $produit The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProduit($produit, $comparison = null)
    {
        if ($produit instanceof Produit) {
            return $this
                ->addUsingAlias(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $produit->getId(), $comparison);
        } elseif ($produit instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $produit->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
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
     * @param   ProfilScoreUtilisateurProduit $profilScoreUtilisateurProduit Object to remove from the list of results
     *
     * @return ProfilScoreUtilisateurProduitQuery The current query, for fluid interface
     */
    public function prune($profilScoreUtilisateurProduit = null)
    {
        if ($profilScoreUtilisateurProduit) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID), $profilScoreUtilisateurProduit->getUtilisateurId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID), $profilScoreUtilisateurProduit->getProduitId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(ProfilScoreUtilisateurProduitPeer::SCORE), $profilScoreUtilisateurProduit->getScore(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
