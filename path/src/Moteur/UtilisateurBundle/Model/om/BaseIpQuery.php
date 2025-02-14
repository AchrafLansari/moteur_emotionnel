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
use Moteur\UtilisateurBundle\Model\Ip;
use Moteur\UtilisateurBundle\Model\IpPeer;
use Moteur\UtilisateurBundle\Model\IpQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;

/**
 * @method IpQuery orderById($order = Criteria::ASC) Order by the id column
 * @method IpQuery orderByPays($order = Criteria::ASC) Order by the pays column
 * @method IpQuery orderByDepartement($order = Criteria::ASC) Order by the departement column
 * @method IpQuery orderByVille($order = Criteria::ASC) Order by the ville column
 *
 * @method IpQuery groupById() Group by the id column
 * @method IpQuery groupByPays() Group by the pays column
 * @method IpQuery groupByDepartement() Group by the departement column
 * @method IpQuery groupByVille() Group by the ville column
 *
 * @method IpQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method IpQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method IpQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method IpQuery leftJoinUtilisateur($relationAlias = null) Adds a LEFT JOIN clause to the query using the Utilisateur relation
 * @method IpQuery rightJoinUtilisateur($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Utilisateur relation
 * @method IpQuery innerJoinUtilisateur($relationAlias = null) Adds a INNER JOIN clause to the query using the Utilisateur relation
 *
 * @method Ip findOne(PropelPDO $con = null) Return the first Ip matching the query
 * @method Ip findOneOrCreate(PropelPDO $con = null) Return the first Ip matching the query, or a new Ip object populated from the query conditions when no match is found
 *
 * @method Ip findOneByPays(string $pays) Return the first Ip filtered by the pays column
 * @method Ip findOneByDepartement(string $departement) Return the first Ip filtered by the departement column
 * @method Ip findOneByVille(string $ville) Return the first Ip filtered by the ville column
 *
 * @method array findById(int $id) Return Ip objects filtered by the id column
 * @method array findByPays(string $pays) Return Ip objects filtered by the pays column
 * @method array findByDepartement(string $departement) Return Ip objects filtered by the departement column
 * @method array findByVille(string $ville) Return Ip objects filtered by the ville column
 */
abstract class BaseIpQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseIpQuery object.
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
            $modelName = 'Moteur\\UtilisateurBundle\\Model\\Ip';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new IpQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   IpQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return IpQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof IpQuery) {
            return $criteria;
        }
        $query = new IpQuery(null, null, $modelAlias);

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
     * @return   Ip|Ip[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IpPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(IpPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Ip A model object, or null if the key is not found
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
     * @return                 Ip A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `pays`, `departement`, `ville` FROM `ip` WHERE `id` = :p0';
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
            $obj = new Ip();
            $obj->hydrate($row);
            IpPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Ip|Ip[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Ip[]|mixed the list of results, formatted by the current formatter
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
     * @return IpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IpPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return IpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IpPeer::ID, $keys, Criteria::IN);
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
     * @return IpQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IpPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IpPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IpPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the pays column
     *
     * Example usage:
     * <code>
     * $query->filterByPays('fooValue');   // WHERE pays = 'fooValue'
     * $query->filterByPays('%fooValue%'); // WHERE pays LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pays The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IpQuery The current query, for fluid interface
     */
    public function filterByPays($pays = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pays)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pays)) {
                $pays = str_replace('*', '%', $pays);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IpPeer::PAYS, $pays, $comparison);
    }

    /**
     * Filter the query on the departement column
     *
     * Example usage:
     * <code>
     * $query->filterByDepartement('fooValue');   // WHERE departement = 'fooValue'
     * $query->filterByDepartement('%fooValue%'); // WHERE departement LIKE '%fooValue%'
     * </code>
     *
     * @param     string $departement The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IpQuery The current query, for fluid interface
     */
    public function filterByDepartement($departement = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($departement)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $departement)) {
                $departement = str_replace('*', '%', $departement);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IpPeer::DEPARTEMENT, $departement, $comparison);
    }

    /**
     * Filter the query on the ville column
     *
     * Example usage:
     * <code>
     * $query->filterByVille('fooValue');   // WHERE ville = 'fooValue'
     * $query->filterByVille('%fooValue%'); // WHERE ville LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ville The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IpQuery The current query, for fluid interface
     */
    public function filterByVille($ville = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ville)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ville)) {
                $ville = str_replace('*', '%', $ville);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IpPeer::VILLE, $ville, $comparison);
    }

    /**
     * Filter the query by a related Utilisateur object
     *
     * @param   Utilisateur|PropelObjectCollection $utilisateur  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IpQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateur($utilisateur, $comparison = null)
    {
        if ($utilisateur instanceof Utilisateur) {
            return $this
                ->addUsingAlias(IpPeer::ID, $utilisateur->getIpId(), $comparison);
        } elseif ($utilisateur instanceof PropelObjectCollection) {
            return $this
                ->useUtilisateurQuery()
                ->filterByPrimaryKeys($utilisateur->getPrimaryKeys())
                ->endUse();
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
     * @return IpQuery The current query, for fluid interface
     */
    public function joinUtilisateur($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useUtilisateurQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUtilisateur($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Utilisateur', '\Moteur\UtilisateurBundle\Model\UtilisateurQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Ip $ip Object to remove from the list of results
     *
     * @return IpQuery The current query, for fluid interface
     */
    public function prune($ip = null)
    {
        if ($ip) {
            $this->addUsingAlias(IpPeer::ID, $ip->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
