<?php

namespace Moteur\ProduitBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Moteur\ProduitBundle\Model\Mot;
use Moteur\ProduitBundle\Model\MotPeer;
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\ProduitBundle\Model\ProduitMotPoidsQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\RecommendationBundle\Model\RequeteQuery;

abstract class BaseMot extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Moteur\\ProduitBundle\\Model\\MotPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        MotPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the mot field.
     * @var        string
     */
    protected $mot;

    /**
     * @var        PropelObjectCollection|ProduitMotPoids[] Collection to store aggregation of ProduitMotPoids objects.
     */
    protected $collProduitMotPoidss;
    protected $collProduitMotPoidssPartial;

    /**
     * @var        PropelObjectCollection|Requete[] Collection to store aggregation of Requete objects.
     */
    protected $collRequetes;
    protected $collRequetesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $produitMotPoidssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $requetesScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [mot] column value.
     *
     * @return string
     */
    public function getMot()
    {

        return $this->mot;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Mot The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = MotPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [mot] column.
     *
     * @param  string $v new value
     * @return Mot The current object (for fluent API support)
     */
    public function setMot($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mot !== $v) {
            $this->mot = $v;
            $this->modifiedColumns[] = MotPeer::MOT;
        }


        return $this;
    } // setMot()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->mot = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 2; // 2 = MotPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Mot object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(MotPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = MotPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collProduitMotPoidss = null;

            $this->collRequetes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(MotPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = MotQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(MotPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MotPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->produitMotPoidssScheduledForDeletion !== null) {
                if (!$this->produitMotPoidssScheduledForDeletion->isEmpty()) {
                    ProduitMotPoidsQuery::create()
                        ->filterByPrimaryKeys($this->produitMotPoidssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->produitMotPoidssScheduledForDeletion = null;
                }
            }

            if ($this->collProduitMotPoidss !== null) {
                foreach ($this->collProduitMotPoidss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->requetesScheduledForDeletion !== null) {
                if (!$this->requetesScheduledForDeletion->isEmpty()) {
                    RequeteQuery::create()
                        ->filterByPrimaryKeys($this->requetesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->requetesScheduledForDeletion = null;
                }
            }

            if ($this->collRequetes !== null) {
                foreach ($this->collRequetes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = MotPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MotPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MotPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(MotPeer::MOT)) {
            $modifiedColumns[':p' . $index++]  = '`mot`';
        }

        $sql = sprintf(
            'INSERT INTO `mot` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`mot`':
                        $stmt->bindValue($identifier, $this->mot, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = MotPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collProduitMotPoidss !== null) {
                    foreach ($this->collProduitMotPoidss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collRequetes !== null) {
                    foreach ($this->collRequetes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = MotPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getMot();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Mot'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Mot'][$this->getPrimaryKey()] = true;
        $keys = MotPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getMot(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collProduitMotPoidss) {
                $result['ProduitMotPoidss'] = $this->collProduitMotPoidss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRequetes) {
                $result['Requetes'] = $this->collRequetes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = MotPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setMot($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = MotPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setMot($arr[$keys[1]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MotPeer::DATABASE_NAME);

        if ($this->isColumnModified(MotPeer::ID)) $criteria->add(MotPeer::ID, $this->id);
        if ($this->isColumnModified(MotPeer::MOT)) $criteria->add(MotPeer::MOT, $this->mot);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(MotPeer::DATABASE_NAME);
        $criteria->add(MotPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Mot (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMot($this->getMot());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getProduitMotPoidss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProduitMotPoids($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRequetes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRequete($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Mot Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return MotPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new MotPeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ProduitMotPoids' == $relationName) {
            $this->initProduitMotPoidss();
        }
        if ('Requete' == $relationName) {
            $this->initRequetes();
        }
    }

    /**
     * Clears out the collProduitMotPoidss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Mot The current object (for fluent API support)
     * @see        addProduitMotPoidss()
     */
    public function clearProduitMotPoidss()
    {
        $this->collProduitMotPoidss = null; // important to set this to null since that means it is uninitialized
        $this->collProduitMotPoidssPartial = null;

        return $this;
    }

    /**
     * reset is the collProduitMotPoidss collection loaded partially
     *
     * @return void
     */
    public function resetPartialProduitMotPoidss($v = true)
    {
        $this->collProduitMotPoidssPartial = $v;
    }

    /**
     * Initializes the collProduitMotPoidss collection.
     *
     * By default this just sets the collProduitMotPoidss collection to an empty array (like clearcollProduitMotPoidss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProduitMotPoidss($overrideExisting = true)
    {
        if (null !== $this->collProduitMotPoidss && !$overrideExisting) {
            return;
        }
        $this->collProduitMotPoidss = new PropelObjectCollection();
        $this->collProduitMotPoidss->setModel('ProduitMotPoids');
    }

    /**
     * Gets an array of ProduitMotPoids objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Mot is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProduitMotPoids[] List of ProduitMotPoids objects
     * @throws PropelException
     */
    public function getProduitMotPoidss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProduitMotPoidssPartial && !$this->isNew();
        if (null === $this->collProduitMotPoidss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProduitMotPoidss) {
                // return empty collection
                $this->initProduitMotPoidss();
            } else {
                $collProduitMotPoidss = ProduitMotPoidsQuery::create(null, $criteria)
                    ->filterByMot($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProduitMotPoidssPartial && count($collProduitMotPoidss)) {
                      $this->initProduitMotPoidss(false);

                      foreach ($collProduitMotPoidss as $obj) {
                        if (false == $this->collProduitMotPoidss->contains($obj)) {
                          $this->collProduitMotPoidss->append($obj);
                        }
                      }

                      $this->collProduitMotPoidssPartial = true;
                    }

                    $collProduitMotPoidss->getInternalIterator()->rewind();

                    return $collProduitMotPoidss;
                }

                if ($partial && $this->collProduitMotPoidss) {
                    foreach ($this->collProduitMotPoidss as $obj) {
                        if ($obj->isNew()) {
                            $collProduitMotPoidss[] = $obj;
                        }
                    }
                }

                $this->collProduitMotPoidss = $collProduitMotPoidss;
                $this->collProduitMotPoidssPartial = false;
            }
        }

        return $this->collProduitMotPoidss;
    }

    /**
     * Sets a collection of ProduitMotPoids objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $produitMotPoidss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Mot The current object (for fluent API support)
     */
    public function setProduitMotPoidss(PropelCollection $produitMotPoidss, PropelPDO $con = null)
    {
        $produitMotPoidssToDelete = $this->getProduitMotPoidss(new Criteria(), $con)->diff($produitMotPoidss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->produitMotPoidssScheduledForDeletion = clone $produitMotPoidssToDelete;

        foreach ($produitMotPoidssToDelete as $produitMotPoidsRemoved) {
            $produitMotPoidsRemoved->setMot(null);
        }

        $this->collProduitMotPoidss = null;
        foreach ($produitMotPoidss as $produitMotPoids) {
            $this->addProduitMotPoids($produitMotPoids);
        }

        $this->collProduitMotPoidss = $produitMotPoidss;
        $this->collProduitMotPoidssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProduitMotPoids objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProduitMotPoids objects.
     * @throws PropelException
     */
    public function countProduitMotPoidss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProduitMotPoidssPartial && !$this->isNew();
        if (null === $this->collProduitMotPoidss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProduitMotPoidss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProduitMotPoidss());
            }
            $query = ProduitMotPoidsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMot($this)
                ->count($con);
        }

        return count($this->collProduitMotPoidss);
    }

    /**
     * Method called to associate a ProduitMotPoids object to this object
     * through the ProduitMotPoids foreign key attribute.
     *
     * @param    ProduitMotPoids $l ProduitMotPoids
     * @return Mot The current object (for fluent API support)
     */
    public function addProduitMotPoids(ProduitMotPoids $l)
    {
        if ($this->collProduitMotPoidss === null) {
            $this->initProduitMotPoidss();
            $this->collProduitMotPoidssPartial = true;
        }

        if (!in_array($l, $this->collProduitMotPoidss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProduitMotPoids($l);

            if ($this->produitMotPoidssScheduledForDeletion and $this->produitMotPoidssScheduledForDeletion->contains($l)) {
                $this->produitMotPoidssScheduledForDeletion->remove($this->produitMotPoidssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProduitMotPoids $produitMotPoids The produitMotPoids object to add.
     */
    protected function doAddProduitMotPoids($produitMotPoids)
    {
        $this->collProduitMotPoidss[]= $produitMotPoids;
        $produitMotPoids->setMot($this);
    }

    /**
     * @param	ProduitMotPoids $produitMotPoids The produitMotPoids object to remove.
     * @return Mot The current object (for fluent API support)
     */
    public function removeProduitMotPoids($produitMotPoids)
    {
        if ($this->getProduitMotPoidss()->contains($produitMotPoids)) {
            $this->collProduitMotPoidss->remove($this->collProduitMotPoidss->search($produitMotPoids));
            if (null === $this->produitMotPoidssScheduledForDeletion) {
                $this->produitMotPoidssScheduledForDeletion = clone $this->collProduitMotPoidss;
                $this->produitMotPoidssScheduledForDeletion->clear();
            }
            $this->produitMotPoidssScheduledForDeletion[]= clone $produitMotPoids;
            $produitMotPoids->setMot(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Mot is new, it will return
     * an empty collection; or if this Mot has previously
     * been saved, it will retrieve related ProduitMotPoidss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Mot.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProduitMotPoids[] List of ProduitMotPoids objects
     */
    public function getProduitMotPoidssJoinProduit($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProduitMotPoidsQuery::create(null, $criteria);
        $query->joinWith('Produit', $join_behavior);

        return $this->getProduitMotPoidss($query, $con);
    }

    /**
     * Clears out the collRequetes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Mot The current object (for fluent API support)
     * @see        addRequetes()
     */
    public function clearRequetes()
    {
        $this->collRequetes = null; // important to set this to null since that means it is uninitialized
        $this->collRequetesPartial = null;

        return $this;
    }

    /**
     * reset is the collRequetes collection loaded partially
     *
     * @return void
     */
    public function resetPartialRequetes($v = true)
    {
        $this->collRequetesPartial = $v;
    }

    /**
     * Initializes the collRequetes collection.
     *
     * By default this just sets the collRequetes collection to an empty array (like clearcollRequetes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRequetes($overrideExisting = true)
    {
        if (null !== $this->collRequetes && !$overrideExisting) {
            return;
        }
        $this->collRequetes = new PropelObjectCollection();
        $this->collRequetes->setModel('Requete');
    }

    /**
     * Gets an array of Requete objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Mot is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Requete[] List of Requete objects
     * @throws PropelException
     */
    public function getRequetes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collRequetesPartial && !$this->isNew();
        if (null === $this->collRequetes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRequetes) {
                // return empty collection
                $this->initRequetes();
            } else {
                $collRequetes = RequeteQuery::create(null, $criteria)
                    ->filterByMot($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collRequetesPartial && count($collRequetes)) {
                      $this->initRequetes(false);

                      foreach ($collRequetes as $obj) {
                        if (false == $this->collRequetes->contains($obj)) {
                          $this->collRequetes->append($obj);
                        }
                      }

                      $this->collRequetesPartial = true;
                    }

                    $collRequetes->getInternalIterator()->rewind();

                    return $collRequetes;
                }

                if ($partial && $this->collRequetes) {
                    foreach ($this->collRequetes as $obj) {
                        if ($obj->isNew()) {
                            $collRequetes[] = $obj;
                        }
                    }
                }

                $this->collRequetes = $collRequetes;
                $this->collRequetesPartial = false;
            }
        }

        return $this->collRequetes;
    }

    /**
     * Sets a collection of Requete objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $requetes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Mot The current object (for fluent API support)
     */
    public function setRequetes(PropelCollection $requetes, PropelPDO $con = null)
    {
        $requetesToDelete = $this->getRequetes(new Criteria(), $con)->diff($requetes);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->requetesScheduledForDeletion = clone $requetesToDelete;

        foreach ($requetesToDelete as $requeteRemoved) {
            $requeteRemoved->setMot(null);
        }

        $this->collRequetes = null;
        foreach ($requetes as $requete) {
            $this->addRequete($requete);
        }

        $this->collRequetes = $requetes;
        $this->collRequetesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Requete objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Requete objects.
     * @throws PropelException
     */
    public function countRequetes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collRequetesPartial && !$this->isNew();
        if (null === $this->collRequetes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRequetes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRequetes());
            }
            $query = RequeteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMot($this)
                ->count($con);
        }

        return count($this->collRequetes);
    }

    /**
     * Method called to associate a Requete object to this object
     * through the Requete foreign key attribute.
     *
     * @param    Requete $l Requete
     * @return Mot The current object (for fluent API support)
     */
    public function addRequete(Requete $l)
    {
        if ($this->collRequetes === null) {
            $this->initRequetes();
            $this->collRequetesPartial = true;
        }

        if (!in_array($l, $this->collRequetes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRequete($l);

            if ($this->requetesScheduledForDeletion and $this->requetesScheduledForDeletion->contains($l)) {
                $this->requetesScheduledForDeletion->remove($this->requetesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Requete $requete The requete object to add.
     */
    protected function doAddRequete($requete)
    {
        $this->collRequetes[]= $requete;
        $requete->setMot($this);
    }

    /**
     * @param	Requete $requete The requete object to remove.
     * @return Mot The current object (for fluent API support)
     */
    public function removeRequete($requete)
    {
        if ($this->getRequetes()->contains($requete)) {
            $this->collRequetes->remove($this->collRequetes->search($requete));
            if (null === $this->requetesScheduledForDeletion) {
                $this->requetesScheduledForDeletion = clone $this->collRequetes;
                $this->requetesScheduledForDeletion->clear();
            }
            $this->requetesScheduledForDeletion[]= clone $requete;
            $requete->setMot(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Mot is new, it will return
     * an empty collection; or if this Mot has previously
     * been saved, it will retrieve related Requetes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Mot.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Requete[] List of Requete objects
     */
    public function getRequetesJoinUtilisateur($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RequeteQuery::create(null, $criteria);
        $query->joinWith('Utilisateur', $join_behavior);

        return $this->getRequetes($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->mot = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collProduitMotPoidss) {
                foreach ($this->collProduitMotPoidss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRequetes) {
                foreach ($this->collRequetes as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collProduitMotPoidss instanceof PropelCollection) {
            $this->collProduitMotPoidss->clearIterator();
        }
        $this->collProduitMotPoidss = null;
        if ($this->collRequetes instanceof PropelCollection) {
            $this->collRequetes->clearIterator();
        }
        $this->collRequetes = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'mot' column
     */
    public function __toString()
    {
        return (string) $this->getMot();
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
