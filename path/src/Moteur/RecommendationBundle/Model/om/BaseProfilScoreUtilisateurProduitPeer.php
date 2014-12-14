<?php

namespace Moteur\RecommendationBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Moteur\ProduitBundle\Model\ProduitPeer;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitPeer;
use Moteur\RecommendationBundle\Model\map\ProfilScoreUtilisateurProduitTableMap;
use Moteur\UtilisateurBundle\Model\UtilisateurPeer;

abstract class BaseProfilScoreUtilisateurProduitPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'symfony';

    /** the table name for this class */
    const TABLE_NAME = 'profil_score_utilisateur_produit';

    /** the related Propel class for this table */
    const OM_CLASS = 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateurProduit';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Moteur\\RecommendationBundle\\Model\\map\\ProfilScoreUtilisateurProduitTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 3;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 3;

    /** the column name for the utilisateur_id field */
    const UTILISATEUR_ID = 'profil_score_utilisateur_produit.utilisateur_id';

    /** the column name for the produit_id field */
    const PRODUIT_ID = 'profil_score_utilisateur_produit.produit_id';

    /** the column name for the score field */
    const SCORE = 'profil_score_utilisateur_produit.score';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of ProfilScoreUtilisateurProduit objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array ProfilScoreUtilisateurProduit[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ProfilScoreUtilisateurProduitPeer::$fieldNames[ProfilScoreUtilisateurProduitPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('UtilisateurId', 'ProduitId', 'Score', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('utilisateurId', 'produitId', 'score', ),
        BasePeer::TYPE_COLNAME => array (ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProfilScoreUtilisateurProduitPeer::SCORE, ),
        BasePeer::TYPE_RAW_COLNAME => array ('UTILISATEUR_ID', 'PRODUIT_ID', 'SCORE', ),
        BasePeer::TYPE_FIELDNAME => array ('utilisateur_id', 'produit_id', 'score', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ProfilScoreUtilisateurProduitPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('UtilisateurId' => 0, 'ProduitId' => 1, 'Score' => 2, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('utilisateurId' => 0, 'produitId' => 1, 'score' => 2, ),
        BasePeer::TYPE_COLNAME => array (ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID => 0, ProfilScoreUtilisateurProduitPeer::PRODUIT_ID => 1, ProfilScoreUtilisateurProduitPeer::SCORE => 2, ),
        BasePeer::TYPE_RAW_COLNAME => array ('UTILISATEUR_ID' => 0, 'PRODUIT_ID' => 1, 'SCORE' => 2, ),
        BasePeer::TYPE_FIELDNAME => array ('utilisateur_id' => 0, 'produit_id' => 1, 'score' => 2, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = ProfilScoreUtilisateurProduitPeer::getFieldNames($toType);
        $key = isset(ProfilScoreUtilisateurProduitPeer::$fieldKeys[$fromType][$name]) ? ProfilScoreUtilisateurProduitPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ProfilScoreUtilisateurProduitPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, ProfilScoreUtilisateurProduitPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ProfilScoreUtilisateurProduitPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. ProfilScoreUtilisateurProduitPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ProfilScoreUtilisateurProduitPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID);
            $criteria->addSelectColumn(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID);
            $criteria->addSelectColumn(ProfilScoreUtilisateurProduitPeer::SCORE);
        } else {
            $criteria->addSelectColumn($alias . '.utilisateur_id');
            $criteria->addSelectColumn($alias . '.produit_id');
            $criteria->addSelectColumn($alias . '.score');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return ProfilScoreUtilisateurProduit
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ProfilScoreUtilisateurProduitPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return ProfilScoreUtilisateurProduitPeer::populateObjects(ProfilScoreUtilisateurProduitPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param ProfilScoreUtilisateurProduit $obj A ProfilScoreUtilisateurProduit object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = serialize(array((string) $obj->getUtilisateurId(), (string) $obj->getProduitId(), (string) $obj->getScore()));
            } // if key === null
            ProfilScoreUtilisateurProduitPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A ProfilScoreUtilisateurProduit object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof ProfilScoreUtilisateurProduit) {
                $key = serialize(array((string) $value->getUtilisateurId(), (string) $value->getProduitId(), (string) $value->getScore()));
            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key
                $key = serialize(array((string) $value[0], (string) $value[1], (string) $value[2]));
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or ProfilScoreUtilisateurProduit object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ProfilScoreUtilisateurProduitPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return ProfilScoreUtilisateurProduit Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ProfilScoreUtilisateurProduitPeer::$instances[$key])) {
                return ProfilScoreUtilisateurProduitPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (ProfilScoreUtilisateurProduitPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        ProfilScoreUtilisateurProduitPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to profil_score_utilisateur_produit
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null && $row[$startcol + 1] === null && $row[$startcol + 2] === null) {
            return null;
        }

        return serialize(array((string) $row[$startcol], (string) $row[$startcol + 1], (string) $row[$startcol + 2]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return array((int) $row[$startcol], (int) $row[$startcol + 1], (int) $row[$startcol + 2]);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = ProfilScoreUtilisateurProduitPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (ProfilScoreUtilisateurProduit object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ProfilScoreUtilisateurProduitPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Utilisateur table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUtilisateur(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, UtilisateurPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Produit table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinProduit(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProduitPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of ProfilScoreUtilisateurProduit objects pre-filled with their Utilisateur objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ProfilScoreUtilisateurProduit objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUtilisateur(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        }

        ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        $startcol = ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS;
        UtilisateurPeer::addSelectColumns($criteria);

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, UtilisateurPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ProfilScoreUtilisateurProduitPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UtilisateurPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UtilisateurPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UtilisateurPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UtilisateurPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (ProfilScoreUtilisateurProduit) to $obj2 (Utilisateur)
                $obj2->addProfilScoreUtilisateurProduit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of ProfilScoreUtilisateurProduit objects pre-filled with their Produit objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ProfilScoreUtilisateurProduit objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinProduit(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        }

        ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        $startcol = ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS;
        ProduitPeer::addSelectColumns($criteria);

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProduitPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ProfilScoreUtilisateurProduitPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ProduitPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ProduitPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ProduitPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ProduitPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (ProfilScoreUtilisateurProduit) to $obj2 (Produit)
                $obj2->addProfilScoreUtilisateurProduit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, UtilisateurPeer::ID, $join_behavior);

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProduitPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of ProfilScoreUtilisateurProduit objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ProfilScoreUtilisateurProduit objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        }

        ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        $startcol2 = ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS;

        UtilisateurPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UtilisateurPeer::NUM_HYDRATE_COLUMNS;

        ProduitPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ProduitPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, UtilisateurPeer::ID, $join_behavior);

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProduitPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ProfilScoreUtilisateurProduitPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Utilisateur rows

            $key2 = UtilisateurPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = UtilisateurPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UtilisateurPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UtilisateurPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (ProfilScoreUtilisateurProduit) to the collection in $obj2 (Utilisateur)
                $obj2->addProfilScoreUtilisateurProduit($obj1);
            } // if joined row not null

            // Add objects for joined Produit rows

            $key3 = ProduitPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = ProduitPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = ProduitPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ProduitPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (ProfilScoreUtilisateurProduit) to the collection in $obj3 (Produit)
                $obj3->addProfilScoreUtilisateurProduit($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Utilisateur table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUtilisateur(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProduitPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Produit table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptProduit(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, UtilisateurPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of ProfilScoreUtilisateurProduit objects pre-filled with all related objects except Utilisateur.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ProfilScoreUtilisateurProduit objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUtilisateur(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        }

        ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        $startcol2 = ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS;

        ProduitPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ProduitPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, ProduitPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ProfilScoreUtilisateurProduitPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Produit rows

                $key2 = ProduitPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ProduitPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ProduitPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ProduitPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (ProfilScoreUtilisateurProduit) to the collection in $obj2 (Produit)
                $obj2->addProfilScoreUtilisateurProduit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of ProfilScoreUtilisateurProduit objects pre-filled with all related objects except Produit.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ProfilScoreUtilisateurProduit objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptProduit(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        }

        ProfilScoreUtilisateurProduitPeer::addSelectColumns($criteria);
        $startcol2 = ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS;

        UtilisateurPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UtilisateurPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, UtilisateurPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ProfilScoreUtilisateurProduitPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ProfilScoreUtilisateurProduitPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ProfilScoreUtilisateurProduitPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Utilisateur rows

                $key2 = UtilisateurPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UtilisateurPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UtilisateurPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UtilisateurPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (ProfilScoreUtilisateurProduit) to the collection in $obj2 (Utilisateur)
                $obj2->addProfilScoreUtilisateurProduit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME)->getTable(ProfilScoreUtilisateurProduitPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseProfilScoreUtilisateurProduitPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Moteur\RecommendationBundle\Model\map\ProfilScoreUtilisateurProduitTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return ProfilScoreUtilisateurProduitPeer::OM_CLASS;
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param   int $utilisateur_id
     * @param   int $produit_id
     * @param   int $score
     * @param      PropelPDO $con
     * @return ProfilScoreUtilisateurProduit
     */
    public static function retrieveByPK($utilisateur_id, $produit_id, $score, PropelPDO $con = null) {
        $_instancePoolKey = serialize(array((string) $utilisateur_id, (string) $produit_id, (string) $score));
         if (null !== ($obj = ProfilScoreUtilisateurProduitPeer::getInstanceFromPool($_instancePoolKey))) {
             return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $criteria = new Criteria(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        $criteria->add(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $utilisateur_id);
        $criteria->add(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $produit_id);
        $criteria->add(ProfilScoreUtilisateurProduitPeer::SCORE, $score);
        $v = ProfilScoreUtilisateurProduitPeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
    }
} // BaseProfilScoreUtilisateurProduitPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseProfilScoreUtilisateurProduitPeer::buildTableMap();

