<?php

namespace Moteur\ProduitBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\ProduitBundle\Model\UtilisateurProduitPeer;
use Moteur\ProduitBundle\Model\UtilisateurProduitQuery;
use Moteur\UtilisateurBundle\Model\Interet;
use Moteur\UtilisateurBundle\Model\InteretQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;

abstract class BaseUtilisateurProduit extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Moteur\\ProduitBundle\\Model\\UtilisateurProduitPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UtilisateurProduitPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the utilisateur_id field.
     * @var        int
     */
    protected $utilisateur_id;

    /**
     * The value for the produit_id field.
     * @var        int
     */
    protected $produit_id;

    /**
     * The value for the note field.
     * @var        int
     */
    protected $note;

    /**
     * The value for the achat field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $achat;

    /**
     * The value for the nombre_visite field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $nombre_visite;

    /**
     * @var        Interet
     */
    protected $aInteret;

    /**
     * @var        Utilisateur
     */
    protected $aUtilisateur;

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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->achat = false;
        $this->nombre_visite = 0;
    }

    /**
     * Initializes internal state of BaseUtilisateurProduit object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [utilisateur_id] column value.
     *
     * @return int
     */
    public function getUtilisateurId()
    {

        return $this->utilisateur_id;
    }

    /**
     * Get the [produit_id] column value.
     *
     * @return int
     */
    public function getProduitId()
    {

        return $this->produit_id;
    }

    /**
     * Get the [note] column value.
     *
     * @return int
     */
    public function getNote()
    {

        return $this->note;
    }

    /**
     * Get the [achat] column value.
     *
     * @return boolean
     */
    public function getAchat()
    {

        return $this->achat;
    }

    /**
     * Get the [nombre_visite] column value.
     *
     * @return int
     */
    public function getNombreVisite()
    {

        return $this->nombre_visite;
    }

    /**
     * Set the value of [utilisateur_id] column.
     *
     * @param  int $v new value
     * @return UtilisateurProduit The current object (for fluent API support)
     */
    public function setUtilisateurId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->utilisateur_id !== $v) {
            $this->utilisateur_id = $v;
            $this->modifiedColumns[] = UtilisateurProduitPeer::UTILISATEUR_ID;
        }

        if ($this->aUtilisateur !== null && $this->aUtilisateur->getId() !== $v) {
            $this->aUtilisateur = null;
        }


        return $this;
    } // setUtilisateurId()

    /**
     * Set the value of [produit_id] column.
     *
     * @param  int $v new value
     * @return UtilisateurProduit The current object (for fluent API support)
     */
    public function setProduitId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->produit_id !== $v) {
            $this->produit_id = $v;
            $this->modifiedColumns[] = UtilisateurProduitPeer::PRODUIT_ID;
        }

        if ($this->aInteret !== null && $this->aInteret->getId() !== $v) {
            $this->aInteret = null;
        }


        return $this;
    } // setProduitId()

    /**
     * Set the value of [note] column.
     *
     * @param  int $v new value
     * @return UtilisateurProduit The current object (for fluent API support)
     */
    public function setNote($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note !== $v) {
            $this->note = $v;
            $this->modifiedColumns[] = UtilisateurProduitPeer::NOTE;
        }


        return $this;
    } // setNote()

    /**
     * Sets the value of the [achat] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UtilisateurProduit The current object (for fluent API support)
     */
    public function setAchat($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->achat !== $v) {
            $this->achat = $v;
            $this->modifiedColumns[] = UtilisateurProduitPeer::ACHAT;
        }


        return $this;
    } // setAchat()

    /**
     * Set the value of [nombre_visite] column.
     *
     * @param  int $v new value
     * @return UtilisateurProduit The current object (for fluent API support)
     */
    public function setNombreVisite($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nombre_visite !== $v) {
            $this->nombre_visite = $v;
            $this->modifiedColumns[] = UtilisateurProduitPeer::NOMBRE_VISITE;
        }


        return $this;
    } // setNombreVisite()

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
            if ($this->achat !== false) {
                return false;
            }

            if ($this->nombre_visite !== 0) {
                return false;
            }

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

            $this->utilisateur_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->produit_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->note = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->achat = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->nombre_visite = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 5; // 5 = UtilisateurProduitPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating UtilisateurProduit object", $e);
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

        if ($this->aUtilisateur !== null && $this->utilisateur_id !== $this->aUtilisateur->getId()) {
            $this->aUtilisateur = null;
        }
        if ($this->aInteret !== null && $this->produit_id !== $this->aInteret->getId()) {
            $this->aInteret = null;
        }
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
            $con = Propel::getConnection(UtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UtilisateurProduitPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aInteret = null;
            $this->aUtilisateur = null;
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
            $con = Propel::getConnection(UtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UtilisateurProduitQuery::create()
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
            $con = Propel::getConnection(UtilisateurProduitPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                UtilisateurProduitPeer::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aInteret !== null) {
                if ($this->aInteret->isModified() || $this->aInteret->isNew()) {
                    $affectedRows += $this->aInteret->save($con);
                }
                $this->setInteret($this->aInteret);
            }

            if ($this->aUtilisateur !== null) {
                if ($this->aUtilisateur->isModified() || $this->aUtilisateur->isNew()) {
                    $affectedRows += $this->aUtilisateur->save($con);
                }
                $this->setUtilisateur($this->aUtilisateur);
            }

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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UtilisateurProduitPeer::UTILISATEUR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`utilisateur_id`';
        }
        if ($this->isColumnModified(UtilisateurProduitPeer::PRODUIT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`produit_id`';
        }
        if ($this->isColumnModified(UtilisateurProduitPeer::NOTE)) {
            $modifiedColumns[':p' . $index++]  = '`note`';
        }
        if ($this->isColumnModified(UtilisateurProduitPeer::ACHAT)) {
            $modifiedColumns[':p' . $index++]  = '`achat`';
        }
        if ($this->isColumnModified(UtilisateurProduitPeer::NOMBRE_VISITE)) {
            $modifiedColumns[':p' . $index++]  = '`nombre_visite`';
        }

        $sql = sprintf(
            'INSERT INTO `utilisateur_produit` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`utilisateur_id`':
                        $stmt->bindValue($identifier, $this->utilisateur_id, PDO::PARAM_INT);
                        break;
                    case '`produit_id`':
                        $stmt->bindValue($identifier, $this->produit_id, PDO::PARAM_INT);
                        break;
                    case '`note`':
                        $stmt->bindValue($identifier, $this->note, PDO::PARAM_INT);
                        break;
                    case '`achat`':
                        $stmt->bindValue($identifier, (int) $this->achat, PDO::PARAM_INT);
                        break;
                    case '`nombre_visite`':
                        $stmt->bindValue($identifier, $this->nombre_visite, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aInteret !== null) {
                if (!$this->aInteret->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aInteret->getValidationFailures());
                }
            }

            if ($this->aUtilisateur !== null) {
                if (!$this->aUtilisateur->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUtilisateur->getValidationFailures());
                }
            }


            if (($retval = UtilisateurProduitPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = UtilisateurProduitPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUtilisateurId();
                break;
            case 1:
                return $this->getProduitId();
                break;
            case 2:
                return $this->getNote();
                break;
            case 3:
                return $this->getAchat();
                break;
            case 4:
                return $this->getNombreVisite();
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
        if (isset($alreadyDumpedObjects['UtilisateurProduit'][serialize($this->getPrimaryKey())])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UtilisateurProduit'][serialize($this->getPrimaryKey())] = true;
        $keys = UtilisateurProduitPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUtilisateurId(),
            $keys[1] => $this->getProduitId(),
            $keys[2] => $this->getNote(),
            $keys[3] => $this->getAchat(),
            $keys[4] => $this->getNombreVisite(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aInteret) {
                $result['Interet'] = $this->aInteret->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUtilisateur) {
                $result['Utilisateur'] = $this->aUtilisateur->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = UtilisateurProduitPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUtilisateurId($value);
                break;
            case 1:
                $this->setProduitId($value);
                break;
            case 2:
                $this->setNote($value);
                break;
            case 3:
                $this->setAchat($value);
                break;
            case 4:
                $this->setNombreVisite($value);
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
        $keys = UtilisateurProduitPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setUtilisateurId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setProduitId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setNote($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setAchat($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setNombreVisite($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UtilisateurProduitPeer::DATABASE_NAME);

        if ($this->isColumnModified(UtilisateurProduitPeer::UTILISATEUR_ID)) $criteria->add(UtilisateurProduitPeer::UTILISATEUR_ID, $this->utilisateur_id);
        if ($this->isColumnModified(UtilisateurProduitPeer::PRODUIT_ID)) $criteria->add(UtilisateurProduitPeer::PRODUIT_ID, $this->produit_id);
        if ($this->isColumnModified(UtilisateurProduitPeer::NOTE)) $criteria->add(UtilisateurProduitPeer::NOTE, $this->note);
        if ($this->isColumnModified(UtilisateurProduitPeer::ACHAT)) $criteria->add(UtilisateurProduitPeer::ACHAT, $this->achat);
        if ($this->isColumnModified(UtilisateurProduitPeer::NOMBRE_VISITE)) $criteria->add(UtilisateurProduitPeer::NOMBRE_VISITE, $this->nombre_visite);

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
        $criteria = new Criteria(UtilisateurProduitPeer::DATABASE_NAME);
        $criteria->add(UtilisateurProduitPeer::UTILISATEUR_ID, $this->utilisateur_id);
        $criteria->add(UtilisateurProduitPeer::PRODUIT_ID, $this->produit_id);

        return $criteria;
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getUtilisateurId();
        $pks[1] = $this->getProduitId();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setUtilisateurId($keys[0]);
        $this->setProduitId($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return (null === $this->getUtilisateurId()) && (null === $this->getProduitId());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of UtilisateurProduit (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUtilisateurId($this->getUtilisateurId());
        $copyObj->setProduitId($this->getProduitId());
        $copyObj->setNote($this->getNote());
        $copyObj->setAchat($this->getAchat());
        $copyObj->setNombreVisite($this->getNombreVisite());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return UtilisateurProduit Clone of current object.
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
     * @return UtilisateurProduitPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UtilisateurProduitPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Interet object.
     *
     * @param                  Interet $v
     * @return UtilisateurProduit The current object (for fluent API support)
     * @throws PropelException
     */
    public function setInteret(Interet $v = null)
    {
        if ($v === null) {
            $this->setProduitId(NULL);
        } else {
            $this->setProduitId($v->getId());
        }

        $this->aInteret = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Interet object, it will not be re-added.
        if ($v !== null) {
            $v->addUtilisateurProduit($this);
        }


        return $this;
    }


    /**
     * Get the associated Interet object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Interet The associated Interet object.
     * @throws PropelException
     */
    public function getInteret(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aInteret === null && ($this->produit_id !== null) && $doQuery) {
            $this->aInteret = InteretQuery::create()->findPk($this->produit_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aInteret->addUtilisateurProduits($this);
             */
        }

        return $this->aInteret;
    }

    /**
     * Declares an association between this object and a Utilisateur object.
     *
     * @param                  Utilisateur $v
     * @return UtilisateurProduit The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUtilisateur(Utilisateur $v = null)
    {
        if ($v === null) {
            $this->setUtilisateurId(NULL);
        } else {
            $this->setUtilisateurId($v->getId());
        }

        $this->aUtilisateur = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Utilisateur object, it will not be re-added.
        if ($v !== null) {
            $v->addUtilisateurProduit($this);
        }


        return $this;
    }


    /**
     * Get the associated Utilisateur object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Utilisateur The associated Utilisateur object.
     * @throws PropelException
     */
    public function getUtilisateur(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUtilisateur === null && ($this->utilisateur_id !== null) && $doQuery) {
            $this->aUtilisateur = UtilisateurQuery::create()->findPk($this->utilisateur_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUtilisateur->addUtilisateurProduits($this);
             */
        }

        return $this->aUtilisateur;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->utilisateur_id = null;
        $this->produit_id = null;
        $this->note = null;
        $this->achat = null;
        $this->nombre_visite = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->aInteret instanceof Persistent) {
              $this->aInteret->clearAllReferences($deep);
            }
            if ($this->aUtilisateur instanceof Persistent) {
              $this->aUtilisateur->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aInteret = null;
        $this->aUtilisateur = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UtilisateurProduitPeer::DEFAULT_STRING_FORMAT);
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
