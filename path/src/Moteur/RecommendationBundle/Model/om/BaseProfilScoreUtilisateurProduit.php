<?php

namespace Moteur\RecommendationBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use Moteur\ProduitBundle\Model\Produit;
use Moteur\ProduitBundle\Model\ProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitPeer;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;

abstract class BaseProfilScoreUtilisateurProduit extends BaseObject
{
    /**
     * Peer class name
     */
    const PEER = 'Moteur\\RecommendationBundle\\Model\\ProfilScoreUtilisateurProduitPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ProfilScoreUtilisateurProduitPeer
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
     * The value for the score field.
     * @var        int
     */
    protected $score;

    /**
     * @var        Utilisateur
     */
    protected $aUtilisateur;

    /**
     * @var        Produit
     */
    protected $aProduit;

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
     * Get the [score] column value.
     *
     * @return int
     */
    public function getScore()
    {

        return $this->score;
    }

    /**
     * Set the value of [utilisateur_id] column.
     *
     * @param  int $v new value
     * @return ProfilScoreUtilisateurProduit The current object (for fluent API support)
     */
    public function setUtilisateurId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->utilisateur_id !== $v) {
            $this->utilisateur_id = $v;
            $this->modifiedColumns[] = ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID;
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
     * @return ProfilScoreUtilisateurProduit The current object (for fluent API support)
     */
    public function setProduitId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->produit_id !== $v) {
            $this->produit_id = $v;
            $this->modifiedColumns[] = ProfilScoreUtilisateurProduitPeer::PRODUIT_ID;
        }

        if ($this->aProduit !== null && $this->aProduit->getId() !== $v) {
            $this->aProduit = null;
        }


        return $this;
    } // setProduitId()

    /**
     * Set the value of [score] column.
     *
     * @param  int $v new value
     * @return ProfilScoreUtilisateurProduit The current object (for fluent API support)
     */
    public function setScore($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->score !== $v) {
            $this->score = $v;
            $this->modifiedColumns[] = ProfilScoreUtilisateurProduitPeer::SCORE;
        }


        return $this;
    } // setScore()

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

            $this->utilisateur_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->produit_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->score = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 3; // 3 = ProfilScoreUtilisateurProduitPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating ProfilScoreUtilisateurProduit object", $e);
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
        if ($this->aProduit !== null && $this->produit_id !== $this->aProduit->getId()) {
            $this->aProduit = null;
        }
    } // ensureConsistency

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

            if ($this->aUtilisateur !== null) {
                if (!$this->aUtilisateur->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUtilisateur->getValidationFailures());
                }
            }

            if ($this->aProduit !== null) {
                if (!$this->aProduit->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aProduit->getValidationFailures());
                }
            }


            if (($retval = ProfilScoreUtilisateurProduitPeer::doValidate($this, $columns)) !== true) {
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
        $pos = ProfilScoreUtilisateurProduitPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getScore();
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
        if (isset($alreadyDumpedObjects['ProfilScoreUtilisateurProduit'][serialize($this->getPrimaryKey())])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ProfilScoreUtilisateurProduit'][serialize($this->getPrimaryKey())] = true;
        $keys = ProfilScoreUtilisateurProduitPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUtilisateurId(),
            $keys[1] => $this->getProduitId(),
            $keys[2] => $this->getScore(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUtilisateur) {
                $result['Utilisateur'] = $this->aUtilisateur->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aProduit) {
                $result['Produit'] = $this->aProduit->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);

        if ($this->isColumnModified(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID)) $criteria->add(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $this->utilisateur_id);
        if ($this->isColumnModified(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID)) $criteria->add(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $this->produit_id);
        if ($this->isColumnModified(ProfilScoreUtilisateurProduitPeer::SCORE)) $criteria->add(ProfilScoreUtilisateurProduitPeer::SCORE, $this->score);

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
        $criteria = new Criteria(ProfilScoreUtilisateurProduitPeer::DATABASE_NAME);
        $criteria->add(ProfilScoreUtilisateurProduitPeer::UTILISATEUR_ID, $this->utilisateur_id);
        $criteria->add(ProfilScoreUtilisateurProduitPeer::PRODUIT_ID, $this->produit_id);
        $criteria->add(ProfilScoreUtilisateurProduitPeer::SCORE, $this->score);

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
        $pks[2] = $this->getScore();

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
        $this->setScore($keys[2]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return (null === $this->getUtilisateurId()) && (null === $this->getProduitId()) && (null === $this->getScore());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of ProfilScoreUtilisateurProduit (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUtilisateurId($this->getUtilisateurId());
        $copyObj->setProduitId($this->getProduitId());
        $copyObj->setScore($this->getScore());

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
     * @return ProfilScoreUtilisateurProduit Clone of current object.
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
     * @return ProfilScoreUtilisateurProduitPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ProfilScoreUtilisateurProduitPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Utilisateur object.
     *
     * @param                  Utilisateur $v
     * @return ProfilScoreUtilisateurProduit The current object (for fluent API support)
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
            $v->addProfilScoreUtilisateurProduit($this);
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
                $this->aUtilisateur->addProfilScoreUtilisateurProduits($this);
             */
        }

        return $this->aUtilisateur;
    }

    /**
     * Declares an association between this object and a Produit object.
     *
     * @param                  Produit $v
     * @return ProfilScoreUtilisateurProduit The current object (for fluent API support)
     * @throws PropelException
     */
    public function setProduit(Produit $v = null)
    {
        if ($v === null) {
            $this->setProduitId(NULL);
        } else {
            $this->setProduitId($v->getId());
        }

        $this->aProduit = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Produit object, it will not be re-added.
        if ($v !== null) {
            $v->addProfilScoreUtilisateurProduit($this);
        }


        return $this;
    }


    /**
     * Get the associated Produit object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Produit The associated Produit object.
     * @throws PropelException
     */
    public function getProduit(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aProduit === null && ($this->produit_id !== null) && $doQuery) {
            $this->aProduit = ProduitQuery::create()->findPk($this->produit_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aProduit->addProfilScoreUtilisateurProduits($this);
             */
        }

        return $this->aProduit;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->utilisateur_id = null;
        $this->produit_id = null;
        $this->score = null;
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
            if ($this->aUtilisateur instanceof Persistent) {
              $this->aUtilisateur->clearAllReferences($deep);
            }
            if ($this->aProduit instanceof Persistent) {
              $this->aProduit->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aUtilisateur = null;
        $this->aProduit = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ProfilScoreUtilisateurProduitPeer::DEFAULT_STRING_FORMAT);
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
