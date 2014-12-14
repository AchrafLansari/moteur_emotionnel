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
use Moteur\ProduitBundle\Model\Mot;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\RecommendationBundle\Model\RequetePeer;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;

/**
 * @method RequeteQuery orderByRequeteId($order = Criteria::ASC) Order by the requete_id column
 * @method RequeteQuery orderByMotId($order = Criteria::ASC) Order by the mot_id column
 * @method RequeteQuery orderByUtilisateurId($order = Criteria::ASC) Order by the utilisateur_id column
 *
 * @method RequeteQuery groupByRequeteId() Group by the requete_id column
 * @method RequeteQuery groupByMotId() Group by the mot_id column
 * @method RequeteQuery groupByUtilisateurId() Group by the utilisateur_id column
 *
 * @method RequeteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method RequeteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method RequeteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method RequeteQuery leftJoinMot($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mot relation
 * @method RequeteQuery rightJoinMot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mot relation
 * @method RequeteQuery innerJoinMot($relationAlias = null) Adds a INNER JOIN clause to the query using the Mot relation
 *
 * @method RequeteQuery leftJoinUtilisateur($relationAlias = null) Adds a LEFT JOIN clause to the query using the Utilisateur relation
 * @method RequeteQuery rightJoinUtilisateur($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Utilisateur relation
 * @method RequeteQuery innerJoinUtilisateur($relationAlias = null) Adds a INNER JOIN clause to the query using the Utilisateur relation
 *
 * @method RequeteQuery leftJoinProfilScoreRequeteProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreRequeteProduit relation
 * @method RequeteQuery rightJoinProfilScoreRequeteProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreRequeteProduit relation
 * @method RequeteQuery innerJoinProfilScoreRequeteProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreRequeteProduit relation
 *
 * @method RequeteQuery leftJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 * @method RequeteQuery rightJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 * @method RequeteQuery innerJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 *
 * @method Requete findOne(PropelPDO $con = null) Return the first Requete matching the query
 * @method Requete findOneOrCreate(PropelPDO $con = null) Return the first Requete matching the query, or a new Requete object populated from the query conditions when no match is found
 *
 * @method Requete findOneByRequeteId(int $requete_id) Return the first Requete filtered by the requete_id column
 * @method Requete findOneByMotId(int $mot_id) Return the first Requete filtered by the mot_id column
 * @method Requete findOneByUtilisateurId(int $utilisateur_id) Return the first Requete filtered by the utilisateur_id column
 *
 * @method array findByRequeteId(int $requete_id) Return Requete objects filtered by the requete_id column
 * @method array findByMotId(int $mot_id) Return Requete objects filtered by the mot_id column
 * @method array findByUtilisateurId(int $utilisateur_id) Return Requete objects filtered by the utilisateur_id column
 */
abstract class BaseRequeteQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseRequeteQuery object.
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
            $modelName = 'Moteur\\RecommendationBundle\\Model\\Requete';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RequeteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   RequeteQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return RequeteQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RequeteQuery) {
            return $criteria;
        }
        $query = new RequeteQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$requete_id, $mot_id, $utilisateur_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Requete|Requete[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RequetePeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(RequetePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Requete A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `requete_id`, `mot_id`, `utilisateur_id` FROM `requete` WHERE `requete_id` = :p0 AND `mot_id` = :p1 AND `utilisateur_id` = :p2';
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
            $obj = new Requete();
            $obj->hydrate($row);
            RequetePeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return Requete|Requete[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Requete[]|mixed the list of results, formatted by the current formatter
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
     * @return RequeteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(RequetePeer::REQUETE_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(RequetePeer::MOT_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(RequetePeer::UTILISATEUR_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return RequeteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(RequetePeer::REQUETE_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(RequetePeer::MOT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(RequetePeer::UTILISATEUR_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the requete_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRequeteId(1234); // WHERE requete_id = 1234
     * $query->filterByRequeteId(array(12, 34)); // WHERE requete_id IN (12, 34)
     * $query->filterByRequeteId(array('min' => 12)); // WHERE requete_id >= 12
     * $query->filterByRequeteId(array('max' => 12)); // WHERE requete_id <= 12
     * </code>
     *
     * @param     mixed $requeteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RequeteQuery The current query, for fluid interface
     */
    public function filterByRequeteId($requeteId = null, $comparison = null)
    {
        if (is_array($requeteId)) {
            $useMinMax = false;
            if (isset($requeteId['min'])) {
                $this->addUsingAlias(RequetePeer::REQUETE_ID, $requeteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($requeteId['max'])) {
                $this->addUsingAlias(RequetePeer::REQUETE_ID, $requeteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequetePeer::REQUETE_ID, $requeteId, $comparison);
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
     * @return RequeteQuery The current query, for fluid interface
     */
    public function filterByMotId($motId = null, $comparison = null)
    {
        if (is_array($motId)) {
            $useMinMax = false;
            if (isset($motId['min'])) {
                $this->addUsingAlias(RequetePeer::MOT_ID, $motId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($motId['max'])) {
                $this->addUsingAlias(RequetePeer::MOT_ID, $motId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequetePeer::MOT_ID, $motId, $comparison);
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
     * @return RequeteQuery The current query, for fluid interface
     */
    public function filterByUtilisateurId($utilisateurId = null, $comparison = null)
    {
        if (is_array($utilisateurId)) {
            $useMinMax = false;
            if (isset($utilisateurId['min'])) {
                $this->addUsingAlias(RequetePeer::UTILISATEUR_ID, $utilisateurId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($utilisateurId['max'])) {
                $this->addUsingAlias(RequetePeer::UTILISATEUR_ID, $utilisateurId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequetePeer::UTILISATEUR_ID, $utilisateurId, $comparison);
    }

    /**
     * Filter the query by a related Mot object
     *
     * @param   Mot|PropelObjectCollection $mot The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RequeteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMot($mot, $comparison = null)
    {
        if ($mot instanceof Mot) {
            return $this
                ->addUsingAlias(RequetePeer::MOT_ID, $mot->getId(), $comparison);
        } elseif ($mot instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RequetePeer::MOT_ID, $mot->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return RequeteQuery The current query, for fluid interface
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
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RequeteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateur($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(RequetePeer::UTILISATEUR_ID, $utilisateur->getId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RequetePeer::UTILISATEUR_ID, $utilisateur->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return RequeteQuery The current query, for fluid interface
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
     * Filter the query by a related ProfilScoreRequeteProduit object
     *
     * @param   ProfilScoreRequeteProduit|PropelObjectCollection $profilScoreRequeteProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RequeteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreRequeteProduit($profilScoreRequeteProduit, $comparison = null)
    {
        if ($profilScoreRequeteProduit instanceof ProfilScoreRequeteProduit) {
            return $this
                ->addUsingAlias(RequetePeer::REQUETE_ID, $profilScoreRequeteProduit->getRequeteId(), $comparison);
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
     * @return RequeteQuery The current query, for fluid interface
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
     * Filter the query by a related ProfilScoreRequeteUtilisateurProduit object
     *
     * @param   ProfilScoreRequeteUtilisateurProduit|PropelObjectCollection $profilScoreRequeteUtilisateurProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RequeteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreRequeteUtilisateurProduit($profilScoreRequeteUtilisateurProduit, $comparison = null)
    {
        if ($profilScoreRequeteUtilisateurProduit instanceof ProfilScoreRequeteUtilisateurProduit) {
            return $this
                ->addUsingAlias(RequetePeer::REQUETE_ID, $profilScoreRequeteUtilisateurProduit->getRequeteId(), $comparison);
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
     * @return RequeteQuery The current query, for fluid interface
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
     * @param   Requete $requete Object to remove from the list of results
     *
     * @return RequeteQuery The current query, for fluid interface
     */
    public function prune($requete = null)
    {
        if ($requete) {
            $this->addCond('pruneCond0', $this->getAliasedColName(RequetePeer::REQUETE_ID), $requete->getRequeteId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(RequetePeer::MOT_ID), $requete->getMotId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(RequetePeer::UTILISATEUR_ID), $requete->getUtilisateurId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
