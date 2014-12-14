<?php

namespace Moteur\RecommendationBundle\Model\om;

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
use Moteur\ProduitBundle\Model\MotQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduitQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\RecommendationBundle\Model\RequetePeer;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;

abstract class BaseRequete extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Moteur\\RecommendationBundle\\Model\\RequetePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        RequetePeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the requete_id field.
     * @var        int
     */
    protected $requete_id;

    /**
     * The value for the mot_id field.
     * @var        int
     */
    protected $mot_id;

    /**
     * The value for the utilisateur_id field.
     * @var        int
     */
    protected $utilisateur_id;

    /**
     * @var        Mot
     */
    protected $aMot;

    /**
     * @var        Utilisateur
     */
    protected $aUtilisateur;

    /**
     * @var        PropelObjectCollection|ProfilScoreRequeteProduit[] Collection to store aggregation of ProfilScoreRequeteProduit objects.
     */
    protected $collProfilScoreRequeteProduits;
    protected $collProfilScoreRequeteProduitsPartial;

    /**
     * @var        PropelObjectCollection|ProfilScoreRequeteUtilisateurProduit[] Collection to store aggregation of ProfilScoreRequeteUtilisateurProduit objects.
     */
    protected $collProfilScoreRequeteUtilisateurProduits;
    protected $collProfilScoreRequeteUtilisateurProduitsPartial;

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
    protected $profilScoreRequeteProduitsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $profilScoreRequeteUtilisateurProduitsScheduledForDeletion = null;

    /**
     * Get the [requete_id] column value.
     *
     * @return int
     */
    public function getRequeteId()
    {

        return $this->requete_id;
    }

    /**
     * Get the [mot_id] column value.
     *
     * @return int
     */
    public function getMotId()
    {

        return $this->mot_id;
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
     * Set the value of [requete_id] column.
     *
     * @param  int $v new value
     * @return Requete The current object (for fluent API support)
     */
    public function setRequeteId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->requete_id !== $v) {
            $this->requete_id = $v;
            $this->modifiedColumns[] = RequetePeer::REQUETE_ID;
        }


        return $this;
    } // setRequeteId()

    /**
     * Set the value of [mot_id] column.
     *
     * @param  int $v new value
     * @return Requete The current object (for fluent API support)
     */
    public function setMotId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->mot_id !== $v) {
            $this->mot_id = $v;
            $this->modifiedColumns[] = RequetePeer::MOT_ID;
        }

        if ($this->aMot !== null && $this->aMot->getId() !== $v) {
            $this->aMot = null;
        }


        return $this;
    } // setMotId()

    /**
     * Set the value of [utilisateur_id] column.
     *
     * @param  int $v new value
     * @return Requete The current object (for fluent API support)
     */
    public function setUtilisateurId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->utilisateur_id !== $v) {
            $this->utilisateur_id = $v;
            $this->modifiedColumns[] = RequetePeer::UTILISATEUR_ID;
        }

        if ($this->aUtilisateur !== null && $this->aUtilisateur->getId() !== $v) {
            $this->aUtilisateur = null;
        }


        return $this;
    } // setUtilisateurId()

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

            $this->requete_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->mot_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->utilisateur_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 3; // 3 = RequetePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Requete object", $e);
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

        if ($this->aMot !== null && $this->mot_id !== $this->aMot->getId()) {
            $this->aMot = null;
        }
        if ($this->aUtilisateur !== null && $this->utilisateur_id !== $this->aUtilisateur->getId()) {
            $this->aUtilisateur = null;
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
            $con = Propel::getConnection(RequetePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = RequetePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMot = null;
            $this->aUtilisateur = null;
            $this->collProfilScoreRequeteProduits = null;

            $this->collProfilScoreRequeteUtilisateurProduits = null;

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
            $con = Propel::getConnection(RequetePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = RequeteQuery::create()
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
            $con = Propel::getConnection(RequetePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                RequetePeer::addInstanceToPool($this);
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

            if ($this->aMot !== null) {
                if ($this->aMot->isModified() || $this->aMot->isNew()) {
                    $affectedRows += $this->aMot->save($con);
                }
                $this->setMot($this->aMot);
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

            if ($this->profilScoreRequeteProduitsScheduledForDeletion !== null) {
                if (!$this->profilScoreRequeteProduitsScheduledForDeletion->isEmpty()) {
                    ProfilScoreRequeteProduitQuery::create()
                        ->filterByPrimaryKeys($this->profilScoreRequeteProduitsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->profilScoreRequeteProduitsScheduledForDeletion = null;
                }
            }

            if ($this->collProfilScoreRequeteProduits !== null) {
                foreach ($this->collProfilScoreRequeteProduits as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion !== null) {
                if (!$this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion->isEmpty()) {
                    ProfilScoreRequeteUtilisateurProduitQuery::create()
                        ->filterByPrimaryKeys($this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion = null;
                }
            }

            if ($this->collProfilScoreRequeteUtilisateurProduits !== null) {
                foreach ($this->collProfilScoreRequeteUtilisateurProduits as $referrerFK) {
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RequetePeer::REQUETE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`requete_id`';
        }
        if ($this->isColumnModified(RequetePeer::MOT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`mot_id`';
        }
        if ($this->isColumnModified(RequetePeer::UTILISATEUR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`utilisateur_id`';
        }

        $sql = sprintf(
            'INSERT INTO `requete` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`requete_id`':
                        $stmt->bindValue($identifier, $this->requete_id, PDO::PARAM_INT);
                        break;
                    case '`mot_id`':
                        $stmt->bindValue($identifier, $this->mot_id, PDO::PARAM_INT);
                        break;
                    case '`utilisateur_id`':
                        $stmt->bindValue($identifier, $this->utilisateur_id, PDO::PARAM_INT);
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

            if ($this->aMot !== null) {
                if (!$this->aMot->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aMot->getValidationFailures());
                }
            }

            if ($this->aUtilisateur !== null) {
                if (!$this->aUtilisateur->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUtilisateur->getValidationFailures());
                }
            }


            if (($retval = RequetePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collProfilScoreRequeteProduits !== null) {
                    foreach ($this->collProfilScoreRequeteProduits as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProfilScoreRequeteUtilisateurProduits !== null) {
                    foreach ($this->collProfilScoreRequeteUtilisateurProduits as $referrerFK) {
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
        $pos = RequetePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getRequeteId();
                break;
            case 1:
                return $this->getMotId();
                break;
            case 2:
                return $this->getUtilisateurId();
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
        if (isset($alreadyDumpedObjects['Requete'][serialize($this->getPrimaryKey())])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Requete'][serialize($this->getPrimaryKey())] = true;
        $keys = RequetePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getRequeteId(),
            $keys[1] => $this->getMotId(),
            $keys[2] => $this->getUtilisateurId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aMot) {
                $result['Mot'] = $this->aMot->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUtilisateur) {
                $result['Utilisateur'] = $this->aUtilisateur->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collProfilScoreRequeteProduits) {
                $result['ProfilScoreRequeteProduits'] = $this->collProfilScoreRequeteProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreRequeteUtilisateurProduits) {
                $result['ProfilScoreRequeteUtilisateurProduits'] = $this->collProfilScoreRequeteUtilisateurProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = RequetePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setRequeteId($value);
                break;
            case 1:
                $this->setMotId($value);
                break;
            case 2:
                $this->setUtilisateurId($value);
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
        $keys = RequetePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setRequeteId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setMotId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setUtilisateurId($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(RequetePeer::DATABASE_NAME);

        if ($this->isColumnModified(RequetePeer::REQUETE_ID)) $criteria->add(RequetePeer::REQUETE_ID, $this->requete_id);
        if ($this->isColumnModified(RequetePeer::MOT_ID)) $criteria->add(RequetePeer::MOT_ID, $this->mot_id);
        if ($this->isColumnModified(RequetePeer::UTILISATEUR_ID)) $criteria->add(RequetePeer::UTILISATEUR_ID, $this->utilisateur_id);

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
        $criteria = new Criteria(RequetePeer::DATABASE_NAME);
        $criteria->add(RequetePeer::REQUETE_ID, $this->requete_id);
        $criteria->add(RequetePeer::MOT_ID, $this->mot_id);
        $criteria->add(RequetePeer::UTILISATEUR_ID, $this->utilisateur_id);

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
        $pks[0] = $this->getRequeteId();
        $pks[1] = $this->getMotId();
        $pks[2] = $this->getUtilisateurId();

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
        $this->setRequeteId($keys[0]);
        $this->setMotId($keys[1]);
        $this->setUtilisateurId($keys[2]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return (null === $this->getRequeteId()) && (null === $this->getMotId()) && (null === $this->getUtilisateurId());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Requete (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setRequeteId($this->getRequeteId());
        $copyObj->setMotId($this->getMotId());
        $copyObj->setUtilisateurId($this->getUtilisateurId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getProfilScoreRequeteProduits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProfilScoreRequeteProduit($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProfilScoreRequeteUtilisateurProduits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProfilScoreRequeteUtilisateurProduit($relObj->copy($deepCopy));
                }
            }

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
     * @return Requete Clone of current object.
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
     * @return RequetePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new RequetePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Mot object.
     *
     * @param                  Mot $v
     * @return Requete The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMot(Mot $v = null)
    {
        if ($v === null) {
            $this->setMotId(NULL);
        } else {
            $this->setMotId($v->getId());
        }

        $this->aMot = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Mot object, it will not be re-added.
        if ($v !== null) {
            $v->addRequete($this);
        }


        return $this;
    }


    /**
     * Get the associated Mot object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Mot The associated Mot object.
     * @throws PropelException
     */
    public function getMot(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aMot === null && ($this->mot_id !== null) && $doQuery) {
            $this->aMot = MotQuery::create()->findPk($this->mot_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMot->addRequetes($this);
             */
        }

        return $this->aMot;
    }

    /**
     * Declares an association between this object and a Utilisateur object.
     *
     * @param                  Utilisateur $v
     * @return Requete The current object (for fluent API support)
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
            $v->addRequete($this);
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
                $this->aUtilisateur->addRequetes($this);
             */
        }

        return $this->aUtilisateur;
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
        if ('ProfilScoreRequeteProduit' == $relationName) {
            $this->initProfilScoreRequeteProduits();
        }
        if ('ProfilScoreRequeteUtilisateurProduit' == $relationName) {
            $this->initProfilScoreRequeteUtilisateurProduits();
        }
    }

    /**
     * Clears out the collProfilScoreRequeteProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Requete The current object (for fluent API support)
     * @see        addProfilScoreRequeteProduits()
     */
    public function clearProfilScoreRequeteProduits()
    {
        $this->collProfilScoreRequeteProduits = null; // important to set this to null since that means it is uninitialized
        $this->collProfilScoreRequeteProduitsPartial = null;

        return $this;
    }

    /**
     * reset is the collProfilScoreRequeteProduits collection loaded partially
     *
     * @return void
     */
    public function resetPartialProfilScoreRequeteProduits($v = true)
    {
        $this->collProfilScoreRequeteProduitsPartial = $v;
    }

    /**
     * Initializes the collProfilScoreRequeteProduits collection.
     *
     * By default this just sets the collProfilScoreRequeteProduits collection to an empty array (like clearcollProfilScoreRequeteProduits());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProfilScoreRequeteProduits($overrideExisting = true)
    {
        if (null !== $this->collProfilScoreRequeteProduits && !$overrideExisting) {
            return;
        }
        $this->collProfilScoreRequeteProduits = new PropelObjectCollection();
        $this->collProfilScoreRequeteProduits->setModel('ProfilScoreRequeteProduit');
    }

    /**
     * Gets an array of ProfilScoreRequeteProduit objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Requete is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProfilScoreRequeteProduit[] List of ProfilScoreRequeteProduit objects
     * @throws PropelException
     */
    public function getProfilScoreRequeteProduits($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreRequeteProduitsPartial && !$this->isNew();
        if (null === $this->collProfilScoreRequeteProduits || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreRequeteProduits) {
                // return empty collection
                $this->initProfilScoreRequeteProduits();
            } else {
                $collProfilScoreRequeteProduits = ProfilScoreRequeteProduitQuery::create(null, $criteria)
                    ->filterByRequete($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProfilScoreRequeteProduitsPartial && count($collProfilScoreRequeteProduits)) {
                      $this->initProfilScoreRequeteProduits(false);

                      foreach ($collProfilScoreRequeteProduits as $obj) {
                        if (false == $this->collProfilScoreRequeteProduits->contains($obj)) {
                          $this->collProfilScoreRequeteProduits->append($obj);
                        }
                      }

                      $this->collProfilScoreRequeteProduitsPartial = true;
                    }

                    $collProfilScoreRequeteProduits->getInternalIterator()->rewind();

                    return $collProfilScoreRequeteProduits;
                }

                if ($partial && $this->collProfilScoreRequeteProduits) {
                    foreach ($this->collProfilScoreRequeteProduits as $obj) {
                        if ($obj->isNew()) {
                            $collProfilScoreRequeteProduits[] = $obj;
                        }
                    }
                }

                $this->collProfilScoreRequeteProduits = $collProfilScoreRequeteProduits;
                $this->collProfilScoreRequeteProduitsPartial = false;
            }
        }

        return $this->collProfilScoreRequeteProduits;
    }

    /**
     * Sets a collection of ProfilScoreRequeteProduit objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $profilScoreRequeteProduits A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Requete The current object (for fluent API support)
     */
    public function setProfilScoreRequeteProduits(PropelCollection $profilScoreRequeteProduits, PropelPDO $con = null)
    {
        $profilScoreRequeteProduitsToDelete = $this->getProfilScoreRequeteProduits(new Criteria(), $con)->diff($profilScoreRequeteProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreRequeteProduitsScheduledForDeletion = clone $profilScoreRequeteProduitsToDelete;

        foreach ($profilScoreRequeteProduitsToDelete as $profilScoreRequeteProduitRemoved) {
            $profilScoreRequeteProduitRemoved->setRequete(null);
        }

        $this->collProfilScoreRequeteProduits = null;
        foreach ($profilScoreRequeteProduits as $profilScoreRequeteProduit) {
            $this->addProfilScoreRequeteProduit($profilScoreRequeteProduit);
        }

        $this->collProfilScoreRequeteProduits = $profilScoreRequeteProduits;
        $this->collProfilScoreRequeteProduitsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProfilScoreRequeteProduit objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProfilScoreRequeteProduit objects.
     * @throws PropelException
     */
    public function countProfilScoreRequeteProduits(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreRequeteProduitsPartial && !$this->isNew();
        if (null === $this->collProfilScoreRequeteProduits || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreRequeteProduits) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProfilScoreRequeteProduits());
            }
            $query = ProfilScoreRequeteProduitQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRequete($this)
                ->count($con);
        }

        return count($this->collProfilScoreRequeteProduits);
    }

    /**
     * Method called to associate a ProfilScoreRequeteProduit object to this object
     * through the ProfilScoreRequeteProduit foreign key attribute.
     *
     * @param    ProfilScoreRequeteProduit $l ProfilScoreRequeteProduit
     * @return Requete The current object (for fluent API support)
     */
    public function addProfilScoreRequeteProduit(ProfilScoreRequeteProduit $l)
    {
        if ($this->collProfilScoreRequeteProduits === null) {
            $this->initProfilScoreRequeteProduits();
            $this->collProfilScoreRequeteProduitsPartial = true;
        }

        if (!in_array($l, $this->collProfilScoreRequeteProduits->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProfilScoreRequeteProduit($l);

            if ($this->profilScoreRequeteProduitsScheduledForDeletion and $this->profilScoreRequeteProduitsScheduledForDeletion->contains($l)) {
                $this->profilScoreRequeteProduitsScheduledForDeletion->remove($this->profilScoreRequeteProduitsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProfilScoreRequeteProduit $profilScoreRequeteProduit The profilScoreRequeteProduit object to add.
     */
    protected function doAddProfilScoreRequeteProduit($profilScoreRequeteProduit)
    {
        $this->collProfilScoreRequeteProduits[]= $profilScoreRequeteProduit;
        $profilScoreRequeteProduit->setRequete($this);
    }

    /**
     * @param	ProfilScoreRequeteProduit $profilScoreRequeteProduit The profilScoreRequeteProduit object to remove.
     * @return Requete The current object (for fluent API support)
     */
    public function removeProfilScoreRequeteProduit($profilScoreRequeteProduit)
    {
        if ($this->getProfilScoreRequeteProduits()->contains($profilScoreRequeteProduit)) {
            $this->collProfilScoreRequeteProduits->remove($this->collProfilScoreRequeteProduits->search($profilScoreRequeteProduit));
            if (null === $this->profilScoreRequeteProduitsScheduledForDeletion) {
                $this->profilScoreRequeteProduitsScheduledForDeletion = clone $this->collProfilScoreRequeteProduits;
                $this->profilScoreRequeteProduitsScheduledForDeletion->clear();
            }
            $this->profilScoreRequeteProduitsScheduledForDeletion[]= clone $profilScoreRequeteProduit;
            $profilScoreRequeteProduit->setRequete(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Requete is new, it will return
     * an empty collection; or if this Requete has previously
     * been saved, it will retrieve related ProfilScoreRequeteProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Requete.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreRequeteProduit[] List of ProfilScoreRequeteProduit objects
     */
    public function getProfilScoreRequeteProduitsJoinProduit($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreRequeteProduitQuery::create(null, $criteria);
        $query->joinWith('Produit', $join_behavior);

        return $this->getProfilScoreRequeteProduits($query, $con);
    }

    /**
     * Clears out the collProfilScoreRequeteUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Requete The current object (for fluent API support)
     * @see        addProfilScoreRequeteUtilisateurProduits()
     */
    public function clearProfilScoreRequeteUtilisateurProduits()
    {
        $this->collProfilScoreRequeteUtilisateurProduits = null; // important to set this to null since that means it is uninitialized
        $this->collProfilScoreRequeteUtilisateurProduitsPartial = null;

        return $this;
    }

    /**
     * reset is the collProfilScoreRequeteUtilisateurProduits collection loaded partially
     *
     * @return void
     */
    public function resetPartialProfilScoreRequeteUtilisateurProduits($v = true)
    {
        $this->collProfilScoreRequeteUtilisateurProduitsPartial = $v;
    }

    /**
     * Initializes the collProfilScoreRequeteUtilisateurProduits collection.
     *
     * By default this just sets the collProfilScoreRequeteUtilisateurProduits collection to an empty array (like clearcollProfilScoreRequeteUtilisateurProduits());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProfilScoreRequeteUtilisateurProduits($overrideExisting = true)
    {
        if (null !== $this->collProfilScoreRequeteUtilisateurProduits && !$overrideExisting) {
            return;
        }
        $this->collProfilScoreRequeteUtilisateurProduits = new PropelObjectCollection();
        $this->collProfilScoreRequeteUtilisateurProduits->setModel('ProfilScoreRequeteUtilisateurProduit');
    }

    /**
     * Gets an array of ProfilScoreRequeteUtilisateurProduit objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Requete is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProfilScoreRequeteUtilisateurProduit[] List of ProfilScoreRequeteUtilisateurProduit objects
     * @throws PropelException
     */
    public function getProfilScoreRequeteUtilisateurProduits($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreRequeteUtilisateurProduitsPartial && !$this->isNew();
        if (null === $this->collProfilScoreRequeteUtilisateurProduits || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreRequeteUtilisateurProduits) {
                // return empty collection
                $this->initProfilScoreRequeteUtilisateurProduits();
            } else {
                $collProfilScoreRequeteUtilisateurProduits = ProfilScoreRequeteUtilisateurProduitQuery::create(null, $criteria)
                    ->filterByRequete($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProfilScoreRequeteUtilisateurProduitsPartial && count($collProfilScoreRequeteUtilisateurProduits)) {
                      $this->initProfilScoreRequeteUtilisateurProduits(false);

                      foreach ($collProfilScoreRequeteUtilisateurProduits as $obj) {
                        if (false == $this->collProfilScoreRequeteUtilisateurProduits->contains($obj)) {
                          $this->collProfilScoreRequeteUtilisateurProduits->append($obj);
                        }
                      }

                      $this->collProfilScoreRequeteUtilisateurProduitsPartial = true;
                    }

                    $collProfilScoreRequeteUtilisateurProduits->getInternalIterator()->rewind();

                    return $collProfilScoreRequeteUtilisateurProduits;
                }

                if ($partial && $this->collProfilScoreRequeteUtilisateurProduits) {
                    foreach ($this->collProfilScoreRequeteUtilisateurProduits as $obj) {
                        if ($obj->isNew()) {
                            $collProfilScoreRequeteUtilisateurProduits[] = $obj;
                        }
                    }
                }

                $this->collProfilScoreRequeteUtilisateurProduits = $collProfilScoreRequeteUtilisateurProduits;
                $this->collProfilScoreRequeteUtilisateurProduitsPartial = false;
            }
        }

        return $this->collProfilScoreRequeteUtilisateurProduits;
    }

    /**
     * Sets a collection of ProfilScoreRequeteUtilisateurProduit objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $profilScoreRequeteUtilisateurProduits A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Requete The current object (for fluent API support)
     */
    public function setProfilScoreRequeteUtilisateurProduits(PropelCollection $profilScoreRequeteUtilisateurProduits, PropelPDO $con = null)
    {
        $profilScoreRequeteUtilisateurProduitsToDelete = $this->getProfilScoreRequeteUtilisateurProduits(new Criteria(), $con)->diff($profilScoreRequeteUtilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion = clone $profilScoreRequeteUtilisateurProduitsToDelete;

        foreach ($profilScoreRequeteUtilisateurProduitsToDelete as $profilScoreRequeteUtilisateurProduitRemoved) {
            $profilScoreRequeteUtilisateurProduitRemoved->setRequete(null);
        }

        $this->collProfilScoreRequeteUtilisateurProduits = null;
        foreach ($profilScoreRequeteUtilisateurProduits as $profilScoreRequeteUtilisateurProduit) {
            $this->addProfilScoreRequeteUtilisateurProduit($profilScoreRequeteUtilisateurProduit);
        }

        $this->collProfilScoreRequeteUtilisateurProduits = $profilScoreRequeteUtilisateurProduits;
        $this->collProfilScoreRequeteUtilisateurProduitsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProfilScoreRequeteUtilisateurProduit objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProfilScoreRequeteUtilisateurProduit objects.
     * @throws PropelException
     */
    public function countProfilScoreRequeteUtilisateurProduits(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreRequeteUtilisateurProduitsPartial && !$this->isNew();
        if (null === $this->collProfilScoreRequeteUtilisateurProduits || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreRequeteUtilisateurProduits) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProfilScoreRequeteUtilisateurProduits());
            }
            $query = ProfilScoreRequeteUtilisateurProduitQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRequete($this)
                ->count($con);
        }

        return count($this->collProfilScoreRequeteUtilisateurProduits);
    }

    /**
     * Method called to associate a ProfilScoreRequeteUtilisateurProduit object to this object
     * through the ProfilScoreRequeteUtilisateurProduit foreign key attribute.
     *
     * @param    ProfilScoreRequeteUtilisateurProduit $l ProfilScoreRequeteUtilisateurProduit
     * @return Requete The current object (for fluent API support)
     */
    public function addProfilScoreRequeteUtilisateurProduit(ProfilScoreRequeteUtilisateurProduit $l)
    {
        if ($this->collProfilScoreRequeteUtilisateurProduits === null) {
            $this->initProfilScoreRequeteUtilisateurProduits();
            $this->collProfilScoreRequeteUtilisateurProduitsPartial = true;
        }

        if (!in_array($l, $this->collProfilScoreRequeteUtilisateurProduits->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProfilScoreRequeteUtilisateurProduit($l);

            if ($this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion and $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion->contains($l)) {
                $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion->remove($this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProfilScoreRequeteUtilisateurProduit $profilScoreRequeteUtilisateurProduit The profilScoreRequeteUtilisateurProduit object to add.
     */
    protected function doAddProfilScoreRequeteUtilisateurProduit($profilScoreRequeteUtilisateurProduit)
    {
        $this->collProfilScoreRequeteUtilisateurProduits[]= $profilScoreRequeteUtilisateurProduit;
        $profilScoreRequeteUtilisateurProduit->setRequete($this);
    }

    /**
     * @param	ProfilScoreRequeteUtilisateurProduit $profilScoreRequeteUtilisateurProduit The profilScoreRequeteUtilisateurProduit object to remove.
     * @return Requete The current object (for fluent API support)
     */
    public function removeProfilScoreRequeteUtilisateurProduit($profilScoreRequeteUtilisateurProduit)
    {
        if ($this->getProfilScoreRequeteUtilisateurProduits()->contains($profilScoreRequeteUtilisateurProduit)) {
            $this->collProfilScoreRequeteUtilisateurProduits->remove($this->collProfilScoreRequeteUtilisateurProduits->search($profilScoreRequeteUtilisateurProduit));
            if (null === $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion) {
                $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion = clone $this->collProfilScoreRequeteUtilisateurProduits;
                $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion->clear();
            }
            $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion[]= clone $profilScoreRequeteUtilisateurProduit;
            $profilScoreRequeteUtilisateurProduit->setRequete(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Requete is new, it will return
     * an empty collection; or if this Requete has previously
     * been saved, it will retrieve related ProfilScoreRequeteUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Requete.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreRequeteUtilisateurProduit[] List of ProfilScoreRequeteUtilisateurProduit objects
     */
    public function getProfilScoreRequeteUtilisateurProduitsJoinUtilisateur($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreRequeteUtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Utilisateur', $join_behavior);

        return $this->getProfilScoreRequeteUtilisateurProduits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Requete is new, it will return
     * an empty collection; or if this Requete has previously
     * been saved, it will retrieve related ProfilScoreRequeteUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Requete.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreRequeteUtilisateurProduit[] List of ProfilScoreRequeteUtilisateurProduit objects
     */
    public function getProfilScoreRequeteUtilisateurProduitsJoinProduit($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreRequeteUtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Produit', $join_behavior);

        return $this->getProfilScoreRequeteUtilisateurProduits($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->requete_id = null;
        $this->mot_id = null;
        $this->utilisateur_id = null;
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
            if ($this->collProfilScoreRequeteProduits) {
                foreach ($this->collProfilScoreRequeteProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProfilScoreRequeteUtilisateurProduits) {
                foreach ($this->collProfilScoreRequeteUtilisateurProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aMot instanceof Persistent) {
              $this->aMot->clearAllReferences($deep);
            }
            if ($this->aUtilisateur instanceof Persistent) {
              $this->aUtilisateur->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collProfilScoreRequeteProduits instanceof PropelCollection) {
            $this->collProfilScoreRequeteProduits->clearIterator();
        }
        $this->collProfilScoreRequeteProduits = null;
        if ($this->collProfilScoreRequeteUtilisateurProduits instanceof PropelCollection) {
            $this->collProfilScoreRequeteUtilisateurProduits->clearIterator();
        }
        $this->collProfilScoreRequeteUtilisateurProduits = null;
        $this->aMot = null;
        $this->aUtilisateur = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RequetePeer::DEFAULT_STRING_FORMAT);
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
