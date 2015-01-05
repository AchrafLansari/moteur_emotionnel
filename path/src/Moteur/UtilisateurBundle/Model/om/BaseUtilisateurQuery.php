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
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateur;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\UtilisateurBundle\Model\Ip;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurInteret;
use Moteur\UtilisateurBundle\Model\UtilisateurPeer;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;

/**
 * @method UtilisateurQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UtilisateurQuery orderByNom($order = Criteria::ASC) Order by the nom column
 * @method UtilisateurQuery orderByPrenom($order = Criteria::ASC) Order by the prenom column
 * @method UtilisateurQuery orderByMail($order = Criteria::ASC) Order by the mail column
 * @method UtilisateurQuery orderByAge($order = Criteria::ASC) Order by the age column
 * @method UtilisateurQuery orderByVille($order = Criteria::ASC) Order by the ville column
 * @method UtilisateurQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method UtilisateurQuery orderByIpUtilisateur($order = Criteria::ASC) Order by the ip_utilisateur column
 * @method UtilisateurQuery orderByIpId($order = Criteria::ASC) Order by the ip_id column
 *
 * @method UtilisateurQuery groupById() Group by the id column
 * @method UtilisateurQuery groupByNom() Group by the nom column
 * @method UtilisateurQuery groupByPrenom() Group by the prenom column
 * @method UtilisateurQuery groupByMail() Group by the mail column
 * @method UtilisateurQuery groupByAge() Group by the age column
 * @method UtilisateurQuery groupByVille() Group by the ville column
 * @method UtilisateurQuery groupByDescription() Group by the description column
 * @method UtilisateurQuery groupByIpUtilisateur() Group by the ip_utilisateur column
 * @method UtilisateurQuery groupByIpId() Group by the ip_id column
 *
 * @method UtilisateurQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UtilisateurQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UtilisateurQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UtilisateurQuery leftJoinIp($relationAlias = null) Adds a LEFT JOIN clause to the query using the Ip relation
 * @method UtilisateurQuery rightJoinIp($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Ip relation
 * @method UtilisateurQuery innerJoinIp($relationAlias = null) Adds a INNER JOIN clause to the query using the Ip relation
 *
 * @method UtilisateurQuery leftJoinUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the UtilisateurProduit relation
 * @method UtilisateurQuery rightJoinUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UtilisateurProduit relation
 * @method UtilisateurQuery innerJoinUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the UtilisateurProduit relation
 *
 * @method UtilisateurQuery leftJoinProfilScoreUtilisateurRelatedByUtilisateurAId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurAId relation
 * @method UtilisateurQuery rightJoinProfilScoreUtilisateurRelatedByUtilisateurAId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurAId relation
 * @method UtilisateurQuery innerJoinProfilScoreUtilisateurRelatedByUtilisateurAId($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurAId relation
 *
 * @method UtilisateurQuery leftJoinProfilScoreUtilisateurRelatedByUtilisateurBId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurBId relation
 * @method UtilisateurQuery rightJoinProfilScoreUtilisateurRelatedByUtilisateurBId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurBId relation
 * @method UtilisateurQuery innerJoinProfilScoreUtilisateurRelatedByUtilisateurBId($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurBId relation
 *
 * @method UtilisateurQuery leftJoinRequete($relationAlias = null) Adds a LEFT JOIN clause to the query using the Requete relation
 * @method UtilisateurQuery rightJoinRequete($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Requete relation
 * @method UtilisateurQuery innerJoinRequete($relationAlias = null) Adds a INNER JOIN clause to the query using the Requete relation
 *
 * @method UtilisateurQuery leftJoinProfilScoreUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
 * @method UtilisateurQuery rightJoinProfilScoreUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
 * @method UtilisateurQuery innerJoinProfilScoreUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreUtilisateurProduit relation
 *
 * @method UtilisateurQuery leftJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 * @method UtilisateurQuery rightJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 * @method UtilisateurQuery innerJoinProfilScoreRequeteUtilisateurProduit($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfilScoreRequeteUtilisateurProduit relation
 *
 * @method UtilisateurQuery leftJoinUtilisateurInteret($relationAlias = null) Adds a LEFT JOIN clause to the query using the UtilisateurInteret relation
 * @method UtilisateurQuery rightJoinUtilisateurInteret($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UtilisateurInteret relation
 * @method UtilisateurQuery innerJoinUtilisateurInteret($relationAlias = null) Adds a INNER JOIN clause to the query using the UtilisateurInteret relation
 *
 * @method Utilisateur findOne(PropelPDO $con = null) Return the first Utilisateur matching the query
 * @method Utilisateur findOneOrCreate(PropelPDO $con = null) Return the first Utilisateur matching the query, or a new Utilisateur object populated from the query conditions when no match is found
 *
 * @method Utilisateur findOneByNom(string $nom) Return the first Utilisateur filtered by the nom column
 * @method Utilisateur findOneByPrenom(string $prenom) Return the first Utilisateur filtered by the prenom column
 * @method Utilisateur findOneByMail(string $mail) Return the first Utilisateur filtered by the mail column
 * @method Utilisateur findOneByAge(int $age) Return the first Utilisateur filtered by the age column
 * @method Utilisateur findOneByVille(string $ville) Return the first Utilisateur filtered by the ville column
 * @method Utilisateur findOneByDescription(string $description) Return the first Utilisateur filtered by the description column
 * @method Utilisateur findOneByIpUtilisateur(string $ip_utilisateur) Return the first Utilisateur filtered by the ip_utilisateur column
 * @method Utilisateur findOneByIpId(int $ip_id) Return the first Utilisateur filtered by the ip_id column
 *
 * @method array findById(int $id) Return Utilisateur objects filtered by the id column
 * @method array findByNom(string $nom) Return Utilisateur objects filtered by the nom column
 * @method array findByPrenom(string $prenom) Return Utilisateur objects filtered by the prenom column
 * @method array findByMail(string $mail) Return Utilisateur objects filtered by the mail column
 * @method array findByAge(int $age) Return Utilisateur objects filtered by the age column
 * @method array findByVille(string $ville) Return Utilisateur objects filtered by the ville column
 * @method array findByDescription(string $description) Return Utilisateur objects filtered by the description column
 * @method array findByIpUtilisateur(string $ip_utilisateur) Return Utilisateur objects filtered by the ip_utilisateur column
 * @method array findByIpId(int $ip_id) Return Utilisateur objects filtered by the ip_id column
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
            $dbName = 'symfony';
        }
        if (null === $modelName) {
            $modelName = 'Moteur\\UtilisateurBundle\\Model\\Utilisateur';
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
        $sql = 'SELECT `id`, `nom`, `prenom`, `mail`, `age`, `ville`, `description`, `ip_utilisateur`, `ip_id` FROM `utilisateur` WHERE `id` = :p0';
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
     * Filter the query on the mail column
     *
     * Example usage:
     * <code>
     * $query->filterByMail('fooValue');   // WHERE mail = 'fooValue'
     * $query->filterByMail('%fooValue%'); // WHERE mail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mail The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByMail($mail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mail)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mail)) {
                $mail = str_replace('*', '%', $mail);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::MAIL, $mail, $comparison);
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
     * Filter the query on the ip_utilisateur column
     *
     * Example usage:
     * <code>
     * $query->filterByIpUtilisateur('fooValue');   // WHERE ip_utilisateur = 'fooValue'
     * $query->filterByIpUtilisateur('%fooValue%'); // WHERE ip_utilisateur LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ipUtilisateur The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByIpUtilisateur($ipUtilisateur = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ipUtilisateur)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ipUtilisateur)) {
                $ipUtilisateur = str_replace('*', '%', $ipUtilisateur);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::IP_UTILISATEUR, $ipUtilisateur, $comparison);
    }

    /**
     * Filter the query on the ip_id column
     *
     * Example usage:
     * <code>
     * $query->filterByIpId(1234); // WHERE ip_id = 1234
     * $query->filterByIpId(array(12, 34)); // WHERE ip_id IN (12, 34)
     * $query->filterByIpId(array('min' => 12)); // WHERE ip_id >= 12
     * $query->filterByIpId(array('max' => 12)); // WHERE ip_id <= 12
     * </code>
     *
     * @see       filterByIp()
     *
     * @param     mixed $ipId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function filterByIpId($ipId = null, $comparison = null)
    {
        if (is_array($ipId)) {
            $useMinMax = false;
            if (isset($ipId['min'])) {
                $this->addUsingAlias(UtilisateurPeer::IP_ID, $ipId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ipId['max'])) {
                $this->addUsingAlias(UtilisateurPeer::IP_ID, $ipId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UtilisateurPeer::IP_ID, $ipId, $comparison);
    }

    /**
     * Filter the query by a related Ip object
     *
     * @param   Ip|PropelObjectCollection $ip The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIp($ip, $comparison = null)
    {
        if ($ip instanceof Ip) {
            return $this
                ->addUsingAlias(UtilisateurPeer::IP_ID, $ip->getId(), $comparison);
        } elseif ($ip instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UtilisateurPeer::IP_ID, $ip->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByIp() only accepts arguments of type Ip or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Ip relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function joinIp($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Ip');

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
            $this->addJoinObject($join, 'Ip');
        }

        return $this;
    }

    /**
     * Use the Ip relation Ip object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\UtilisateurBundle\Model\IpQuery A secondary query class using the current class as primary query
     */
    public function useIpQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIp($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Ip', '\Moteur\UtilisateurBundle\Model\IpQuery');
    }

    /**
     * Filter the query by a related UtilisateurProduit object
     *
     * @param   UtilisateurProduit|PropelObjectCollection $utilisateurProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateurProduit($utilisateurProduit, $comparison = null)
    {
        if ($utilisateurProduit instanceof UtilisateurProduit) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $utilisateurProduit->getUtilisateurId(), $comparison);
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
     * @return UtilisateurQuery The current query, for fluid interface
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
     * Filter the query by a related ProfilScoreUtilisateur object
     *
     * @param   ProfilScoreUtilisateur|PropelObjectCollection $profilScoreUtilisateur  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreUtilisateurRelatedByUtilisateurAId($profilScoreUtilisateur, $comparison = null)
    {
        if ($profilScoreUtilisateur instanceof ProfilScoreUtilisateur) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $profilScoreUtilisateur->getUtilisateurAId(), $comparison);
        } elseif ($profilScoreUtilisateur instanceof PropelObjectCollection) {
            return $this
                ->useProfilScoreUtilisateurRelatedByUtilisateurAIdQuery()
                ->filterByPrimaryKeys($profilScoreUtilisateur->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProfilScoreUtilisateurRelatedByUtilisateurAId() only accepts arguments of type ProfilScoreUtilisateur or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurAId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function joinProfilScoreUtilisateurRelatedByUtilisateurAId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProfilScoreUtilisateurRelatedByUtilisateurAId');

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
            $this->addJoinObject($join, 'ProfilScoreUtilisateurRelatedByUtilisateurAId');
        }

        return $this;
    }

    /**
     * Use the ProfilScoreUtilisateurRelatedByUtilisateurAId relation ProfilScoreUtilisateur object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery A secondary query class using the current class as primary query
     */
    public function useProfilScoreUtilisateurRelatedByUtilisateurAIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfilScoreUtilisateurRelatedByUtilisateurAId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProfilScoreUtilisateurRelatedByUtilisateurAId', '\Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery');
    }

    /**
     * Filter the query by a related ProfilScoreUtilisateur object
     *
     * @param   ProfilScoreUtilisateur|PropelObjectCollection $profilScoreUtilisateur  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreUtilisateurRelatedByUtilisateurBId($profilScoreUtilisateur, $comparison = null)
    {
        if ($profilScoreUtilisateur instanceof ProfilScoreUtilisateur) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $profilScoreUtilisateur->getUtilisateurBId(), $comparison);
        } elseif ($profilScoreUtilisateur instanceof PropelObjectCollection) {
            return $this
                ->useProfilScoreUtilisateurRelatedByUtilisateurBIdQuery()
                ->filterByPrimaryKeys($profilScoreUtilisateur->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProfilScoreUtilisateurRelatedByUtilisateurBId() only accepts arguments of type ProfilScoreUtilisateur or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProfilScoreUtilisateurRelatedByUtilisateurBId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function joinProfilScoreUtilisateurRelatedByUtilisateurBId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProfilScoreUtilisateurRelatedByUtilisateurBId');

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
            $this->addJoinObject($join, 'ProfilScoreUtilisateurRelatedByUtilisateurBId');
        }

        return $this;
    }

    /**
     * Use the ProfilScoreUtilisateurRelatedByUtilisateurBId relation ProfilScoreUtilisateur object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery A secondary query class using the current class as primary query
     */
    public function useProfilScoreUtilisateurRelatedByUtilisateurBIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfilScoreUtilisateurRelatedByUtilisateurBId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProfilScoreUtilisateurRelatedByUtilisateurBId', '\Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery');
    }

    /**
     * Filter the query by a related Requete object
     *
     * @param   Requete|PropelObjectCollection $requete  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRequete($requete, $comparison = null)
    {
        if ($requete instanceof Requete) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $requete->getUtilisateurId(), $comparison);
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
     * @return UtilisateurQuery The current query, for fluid interface
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
     * Filter the query by a related ProfilScoreUtilisateurProduit object
     *
     * @param   ProfilScoreUtilisateurProduit|PropelObjectCollection $profilScoreUtilisateurProduit  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreUtilisateurProduit($profilScoreUtilisateurProduit, $comparison = null)
    {
        if ($profilScoreUtilisateurProduit instanceof ProfilScoreUtilisateurProduit) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $profilScoreUtilisateurProduit->getUtilisateurId(), $comparison);
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
     * @return UtilisateurQuery The current query, for fluid interface
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
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfilScoreRequeteUtilisateurProduit($profilScoreRequeteUtilisateurProduit, $comparison = null)
    {
        if ($profilScoreRequeteUtilisateurProduit instanceof ProfilScoreRequeteUtilisateurProduit) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $profilScoreRequeteUtilisateurProduit->getUtilisateurId(), $comparison);
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
     * @return UtilisateurQuery The current query, for fluid interface
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
     * Filter the query by a related UtilisateurInteret object
     *
     * @param   UtilisateurInteret|PropelObjectCollection $utilisateurInteret  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UtilisateurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUtilisateurInteret($utilisateurInteret, $comparison = null)
    {
        if ($utilisateurInteret instanceof UtilisateurInteret) {
            return $this
                ->addUsingAlias(UtilisateurPeer::ID, $utilisateurInteret->getUtilisateurId(), $comparison);
        } elseif ($utilisateurInteret instanceof PropelObjectCollection) {
            return $this
                ->useUtilisateurInteretQuery()
                ->filterByPrimaryKeys($utilisateurInteret->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUtilisateurInteret() only accepts arguments of type UtilisateurInteret or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UtilisateurInteret relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UtilisateurQuery The current query, for fluid interface
     */
    public function joinUtilisateurInteret($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UtilisateurInteret');

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
            $this->addJoinObject($join, 'UtilisateurInteret');
        }

        return $this;
    }

    /**
     * Use the UtilisateurInteret relation UtilisateurInteret object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Moteur\UtilisateurBundle\Model\UtilisateurInteretQuery A secondary query class using the current class as primary query
     */
    public function useUtilisateurInteretQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUtilisateurInteret($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UtilisateurInteret', '\Moteur\UtilisateurBundle\Model\UtilisateurInteretQuery');
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
