<?php

namespace Acme\UserBundle\Model\om;

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
use Acme\UserBundle\Model\Categorie;
use Acme\UserBundle\Model\Utilisateur;
use Acme\UserBundle\Model\UtilisateurPeer;
use Acme\UserBundle\Model\UtilisateurQuery;

/**
 * @method UtilisateurQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UtilisateurQuery orderByNom($order = Criteria::ASC) Order by the nom column
 * @method UtilisateurQuery orderByPrenom($order = Criteria::ASC) Order by the prenom column
 * @method UtilisateurQuery orderByAge($order = Criteria::ASC) Order by the age column
 * @method UtilisateurQuery orderByVille($order = Criteria::ASC) Order by the ville column
 * @method UtilisateurQuery orderByIp($order = Criteria::ASC) Order by the ip column
 * @method UtilisateurQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method UtilisateurQuery orderByCategorieId($order = Criteria::ASC) Order by the categorie_id column
 *
 * @method UtilisateurQuery groupById() Group by the id column
 * @method UtilisateurQuery groupByNom() Group by the nom column
 * @method UtilisateurQuery groupByPrenom() Group by the prenom column
 * @method UtilisateurQuery groupByAge() Group by the age column
 * @method UtilisateurQuery groupByVille() Group by the ville column
 * @method UtilisateurQuery groupByIp() Group by the ip column
 * @method UtilisateurQuery groupByDescription() Group by the description column
 * @method UtilisateurQuery groupByCategorieId() Group by the categorie_id column
 *
 * @method UtilisateurQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UtilisateurQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UtilisateurQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UtilisateurQuery leftJoinCategorie($relationAlias = null) Adds a LEFT JOIN clause to the query using the Categorie relation
 * @method UtilisateurQuery rightJoinCategorie($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Categorie relation
 * @method UtilisateurQuery innerJoinCategorie($relationAlias = null) Adds a INNER JOIN clause to the query using the Categorie relation
 *
 * @method Utilisateur findOne(PropelPDO $con = null) Return the first Utilisateur matching the query
 * @method Utilisateur findOneOrCreate(PropelPDO $con = null) Return the first Utilisateur matching the query, or a new Utilisateur object populated from the query conditions when no match is found
 *
 * @method Utilisateur findOneByNom(string $nom) Return the first Utilisateur filtered by the nom column
 * @method Utilisateur findOneByPrenom(string $prenom) Return the first Utilisateur filtered by the prenom column
 * @method Utilisateur findOneByAge(string $age) Return the first Utilisateur filtered by the age column
 * @method Utilisateur findOneByVille(string $ville) Return the first Utilisateur filtered by the ville column
 * @method Utilisateur findOneByIp(string $ip) Return the first Utilisateur filtered by the ip column
 * @method Utilisateur findOneByDescription(string $description) Return the first Utilisateur filtered by the description column
 * @method Utilisateur findOneByCategorieId(int $categorie_id) Return the first Utilisateur filtered by the categorie_id column
 *
 * @method array findById(int $id) Return Utilisateur objects filtered by the id column
 * @method array findByNom(string $nom) Return Utilisateur objects filtered by the nom column
 * @method array findByPrenom(string $prenom) Return Utilisateur objects filtered by the prenom column
 * @method array findByAge(string $age) Return Utilisateur objects filtered by the age column
 * @method array findByVille(string $ville) Return Utilisateur objects filtered by the ville column
 * @method array findByIp(string $ip) Return Utilisateur objects filtered by the ip column
 * @method array findByDescription(string $description) Return Utilisateur objects filtered by the description column
 * @method array findByCategorieId(int $categorie_id) Return Utilisateur objects filtered by the categorie_id column
 */
abstract class BaseUtilisateurQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUtilisateurQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'moteur_emotionnel';
        }
        if (null === $modelName) {
            $modelName = 'Acme\\UserBundle\\Model\\Utilisateur';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UtilisateurQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UtilisateurQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UtilisateurQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UtilisateurQuery) {
            return $criteria;
        }
        $query = new UtilisateurQuery(null, null, $modelAlias);

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
     * @return   Utilisateur|Utilisateur[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UtilisateurPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UtilisateurPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Utilisateur A model object, or null if the key is not found
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
     * @return                 Utilisateur A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `nom`, `prenom`, `age`, `ville`, `ip`, `description`, `categorie_id` FROM `utilisateur` WHERE `id` = :p0';
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
            $obj = new Utilisateur();
            $obj->hydrate($row);
            UtilisateurPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Utilisateur|Utilisateur[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Utilisateur[]|mixed the list of results, formatted by the current formatter
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
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UtilisateurPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UtilisateurPeer::ID, $keys, Criteria::IN);
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
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UtilisateurPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UtilisateurPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the nom column
     *
     * Example usage:
     * <code>
     * $query->filterByNom('fooValue');   // WHERE nom = 'fooValue'
     * $query->filterByNom('%fooValue%'); // WHERE nom LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nom The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByNom($nom = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nom)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nom)) {
                $nom = str_replace('*', '%', $nom);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::NOM, $nom, $comparison);
    }

    /**
     * Filter the query on the prenom column
     *
     * Example usage:
     * <code>
     * $query->filterByPrenom('fooValue');   // WHERE prenom = 'fooValue'
     * $query->filterByPrenom('%fooValue%'); // WHERE prenom LIKE '%fooValue%'
     * </code>
     *
     * @param     string $prenom The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByPrenom($prenom = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prenom)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $prenom)) {
                $prenom = str_replace('*', '%', $prenom);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::PRENOM, $prenom, $comparison);
    }

    /**
     * Filter the query on the age column
     *
     * Example usage:
     * <code>
     * $query->filterByAge(1234); // WHERE age = 1234
     * $query->filterByAge(array(12, 34)); // WHERE age IN (12, 34)
     * $query->filterByAge(array('min' => 12)); // WHERE age >= 12
     * $query->filterByAge(array('max' => 12)); // WHERE age <= 12
     * </code>
     *
     * @param     mixed $age The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByAge($age = null, $comparison = null)
    {
        if (is_array($age)) {
            $useMinMax = false;
            if (isset($age['min'])) {
                $this->addUsingAlias(UtilisateurPeer::AGE, $age['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($age['max'])) {
                $this->addUsingAlias(UtilisateurPeer::AGE, $age['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::AGE, $age, $comparison);
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
     * @return UtilisateurQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UtilisateurPeer::VILLE, $ville, $comparison);
    }

    /**
     * Filter the query on the ip column
     *
     * Example usage:
     * <code>
     * $query->filterByIp(1234); // WHERE ip = 1234
     * $query->filterByIp(array(12, 34)); // WHERE ip IN (12, 34)
     * $query->filterByIp(array('min' => 12)); // WHERE ip >= 12
     * $query->filterByIp(array('max' => 12)); // WHERE ip <= 12
     * </code>
     *
     * @param     mixed $ip The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByIp($ip = null, $comparison = null)
    {
        if (is_array($ip)) {
            $useMinMax = false;
            if (isset($ip['min'])) {
                $this->addUsingAlias(UtilisateurPeer::IP, $ip['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ip['max'])) {
                $this->addUsingAlias(UtilisateurPeer::IP, $ip['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::IP, $ip, $comparison);
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
     * @return UtilisateurQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UtilisateurPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the categorie_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategorieId(1234); // WHERE categorie_id = 1234
     * $query->filterByCategorieId(array(12, 34)); // WHERE categorie_id IN (12, 34)
     * $query->filterByCategorieId(array('min' => 12)); // WHERE categorie_id >= 12
     * $query->filterByCategorieId(array('max' => 12)); // WHERE categorie_id <= 12
     * </code>
     *
     * @see       filterByCategorie()
     *
     * @param     mixed $categorieId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByCategorieId($categorieId = null, $comparison = null)
    {
        if (is_array($categorieId)) {
            $useMinMax = false;
            if (isset($categorieId['min'])) {
                $this->addUsingAlias(UtilisateurPeer::CATEGORIE_ID, $categorieId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categorieId['max'])) {
                $this->addUsingAlias(UtilisateurPeer::CATEGORIE_ID, $categorieId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::CATEGORIE_ID, $categorieId, $comparison);
    }

    /**
     * Filter the query by a related Categorie object
     *
     * @param   Categorie|PropelObjectCollection $categorie The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCategorie($categorie, $comparison = null)
    {
        if ($categorie instanceof Categorie) {
            return $this
                ->addUsingAlias(UtilisateurPeer::CATEGORIE_ID, $categorie->getId(), $comparison);
        } elseif ($categorie instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UtilisateurPeer::CATEGORIE_ID, $categorie->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategorie() only accepts arguments of type Categorie or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Categorie relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function joinCategorie($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Categorie');

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
            $this->addJoinObject($join, 'Categorie');
        }

        return $this;
    }

    /**
     * Use the Categorie relation Categorie object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Acme\UserBundle\Model\CategorieQuery A secondary query class using the current class as primary query
     */
    public function useCategorieQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCategorie($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Categorie', '\Acme\UserBundle\Model\CategorieQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Utilisateur $utilisateur Object to remove from the list of results
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function prune($utilisateur = null)
    {
        if ($utilisateur) {
            $this->addUsingAlias(UtilisateurPeer::ID, $utilisateur->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
