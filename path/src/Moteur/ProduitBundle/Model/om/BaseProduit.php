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
use Moteur\ProduitBundle\Model\Produit;
use Moteur\ProduitBundle\Model\ProduitMotPoids;
use Moteur\ProduitBundle\Model\ProduitMotPoidsQuery;
use Moteur\ProduitBundle\Model\ProduitPeer;
use Moteur\ProduitBundle\Model\ProduitQuery;
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\ProduitBundle\Model\UtilisateurProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitQuery;

abstract class BaseProduit extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Moteur\\ProduitBundle\\Model\\ProduitPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ProduitPeer
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
     * The value for the titre field.
     * @var        string
     */
    protected $titre;

    /**
     * The value for the sous_titre field.
     * @var        string
     */
    protected $sous_titre;

    /**
     * The value for the auteur field.
     * @var        string
     */
    protected $auteur;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the image field.
     * @var        string
     */
    protected $image;

    /**
     * The value for the lien field.
     * @var        string
     */
    protected $lien;

    /**
     * @var        PropelObjectCollection|ProduitMotPoids[] Collection to store aggregation of ProduitMotPoids objects.
     */
    protected $collProduitMotPoidss;
    protected $collProduitMotPoidssPartial;

    /**
     * @var        PropelObjectCollection|UtilisateurProduit[] Collection to store aggregation of UtilisateurProduit objects.
     */
    protected $collUtilisateurProduits;
    protected $collUtilisateurProduitsPartial;

    /**
     * @var        PropelObjectCollection|ProfilScoreRequeteProduit[] Collection to store aggregation of ProfilScoreRequeteProduit objects.
     */
    protected $collProfilScoreRequeteProduits;
    protected $collProfilScoreRequeteProduitsPartial;

    /**
     * @var        PropelObjectCollection|ProfilScoreUtilisateurProduit[] Collection to store aggregation of ProfilScoreUtilisateurProduit objects.
     */
    protected $collProfilScoreUtilisateurProduits;
    protected $collProfilScoreUtilisateurProduitsPartial;

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
    protected $produitMotPoidssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $utilisateurProduitsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $profilScoreRequeteProduitsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $profilScoreUtilisateurProduitsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $profilScoreRequeteUtilisateurProduitsScheduledForDeletion = null;

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
     * Get the [titre] column value.
     *
     * @return string
     */
    public function getTitre()
    {

        return $this->titre;
    }

    /**
     * Get the [sous_titre] column value.
     *
     * @return string
     */
    public function getSousTitre()
    {

        return $this->sous_titre;
    }

    /**
     * Get the [auteur] column value.
     *
     * @return string
     */
    public function getAuteur()
    {

        return $this->auteur;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [image] column value.
     *
     * @return string
     */
    public function getImage()
    {

        return $this->image;
    }

    /**
     * Get the [lien] column value.
     *
     * @return string
     */
    public function getLien()
    {

        return $this->lien;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ProduitPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [titre] column.
     *
     * @param  string $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setTitre($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->titre !== $v) {
            $this->titre = $v;
            $this->modifiedColumns[] = ProduitPeer::TITRE;
        }


        return $this;
    } // setTitre()

    /**
     * Set the value of [sous_titre] column.
     *
     * @param  string $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setSousTitre($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sous_titre !== $v) {
            $this->sous_titre = $v;
            $this->modifiedColumns[] = ProduitPeer::SOUS_TITRE;
        }


        return $this;
    } // setSousTitre()

    /**
     * Set the value of [auteur] column.
     *
     * @param  string $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setAuteur($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->auteur !== $v) {
            $this->auteur = $v;
            $this->modifiedColumns[] = ProduitPeer::AUTEUR;
        }


        return $this;
    } // setAuteur()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = ProduitPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [image] column.
     *
     * @param  string $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image !== $v) {
            $this->image = $v;
            $this->modifiedColumns[] = ProduitPeer::IMAGE;
        }


        return $this;
    } // setImage()

    /**
     * Set the value of [lien] column.
     *
     * @param  string $v new value
     * @return Produit The current object (for fluent API support)
     */
    public function setLien($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lien !== $v) {
            $this->lien = $v;
            $this->modifiedColumns[] = ProduitPeer::LIEN;
        }


        return $this;
    } // setLien()

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
            $this->titre = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->sous_titre = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->auteur = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->image = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->lien = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = ProduitPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Produit object", $e);
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
            $con = Propel::getConnection(ProduitPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ProduitPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collProduitMotPoidss = null;

            $this->collUtilisateurProduits = null;

            $this->collProfilScoreRequeteProduits = null;

            $this->collProfilScoreUtilisateurProduits = null;

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
            $con = Propel::getConnection(ProduitPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ProduitQuery::create()
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
            $con = Propel::getConnection(ProduitPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                ProduitPeer::addInstanceToPool($this);
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

            if ($this->utilisateurProduitsScheduledForDeletion !== null) {
                if (!$this->utilisateurProduitsScheduledForDeletion->isEmpty()) {
                    UtilisateurProduitQuery::create()
                        ->filterByPrimaryKeys($this->utilisateurProduitsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->utilisateurProduitsScheduledForDeletion = null;
                }
            }

            if ($this->collUtilisateurProduits !== null) {
                foreach ($this->collUtilisateurProduits as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->profilScoreUtilisateurProduitsScheduledForDeletion !== null) {
                if (!$this->profilScoreUtilisateurProduitsScheduledForDeletion->isEmpty()) {
                    ProfilScoreUtilisateurProduitQuery::create()
                        ->filterByPrimaryKeys($this->profilScoreUtilisateurProduitsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->profilScoreUtilisateurProduitsScheduledForDeletion = null;
                }
            }

            if ($this->collProfilScoreUtilisateurProduits !== null) {
                foreach ($this->collProfilScoreUtilisateurProduits as $referrerFK) {
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

        $this->modifiedColumns[] = ProduitPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ProduitPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ProduitPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ProduitPeer::TITRE)) {
            $modifiedColumns[':p' . $index++]  = '`titre`';
        }
        if ($this->isColumnModified(ProduitPeer::SOUS_TITRE)) {
            $modifiedColumns[':p' . $index++]  = '`sous_titre`';
        }
        if ($this->isColumnModified(ProduitPeer::AUTEUR)) {
            $modifiedColumns[':p' . $index++]  = '`auteur`';
        }
        if ($this->isColumnModified(ProduitPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(ProduitPeer::IMAGE)) {
            $modifiedColumns[':p' . $index++]  = '`image`';
        }
        if ($this->isColumnModified(ProduitPeer::LIEN)) {
            $modifiedColumns[':p' . $index++]  = '`lien`';
        }

        $sql = sprintf(
            'INSERT INTO `produit` (%s) VALUES (%s)',
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
                    case '`titre`':
                        $stmt->bindValue($identifier, $this->titre, PDO::PARAM_STR);
                        break;
                    case '`sous_titre`':
                        $stmt->bindValue($identifier, $this->sous_titre, PDO::PARAM_STR);
                        break;
                    case '`auteur`':
                        $stmt->bindValue($identifier, $this->auteur, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`image`':
                        $stmt->bindValue($identifier, $this->image, PDO::PARAM_STR);
                        break;
                    case '`lien`':
                        $stmt->bindValue($identifier, $this->lien, PDO::PARAM_STR);
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


            if (($retval = ProduitPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collProduitMotPoidss !== null) {
                    foreach ($this->collProduitMotPoidss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUtilisateurProduits !== null) {
                    foreach ($this->collUtilisateurProduits as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProfilScoreRequeteProduits !== null) {
                    foreach ($this->collProfilScoreRequeteProduits as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProfilScoreUtilisateurProduits !== null) {
                    foreach ($this->collProfilScoreUtilisateurProduits as $referrerFK) {
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
        $pos = ProduitPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitre();
                break;
            case 2:
                return $this->getSousTitre();
                break;
            case 3:
                return $this->getAuteur();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getImage();
                break;
            case 6:
                return $this->getLien();
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
        if (isset($alreadyDumpedObjects['Produit'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Produit'][$this->getPrimaryKey()] = true;
        $keys = ProduitPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitre(),
            $keys[2] => $this->getSousTitre(),
            $keys[3] => $this->getAuteur(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getImage(),
            $keys[6] => $this->getLien(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collProduitMotPoidss) {
                $result['ProduitMotPoidss'] = $this->collProduitMotPoidss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUtilisateurProduits) {
                $result['UtilisateurProduits'] = $this->collUtilisateurProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreRequeteProduits) {
                $result['ProfilScoreRequeteProduits'] = $this->collProfilScoreRequeteProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreUtilisateurProduits) {
                $result['ProfilScoreUtilisateurProduits'] = $this->collProfilScoreUtilisateurProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ProduitPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitre($value);
                break;
            case 2:
                $this->setSousTitre($value);
                break;
            case 3:
                $this->setAuteur($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setImage($value);
                break;
            case 6:
                $this->setLien($value);
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
        $keys = ProduitPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitre($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSousTitre($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setAuteur($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setImage($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setLien($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ProduitPeer::DATABASE_NAME);

        if ($this->isColumnModified(ProduitPeer::ID)) $criteria->add(ProduitPeer::ID, $this->id);
        if ($this->isColumnModified(ProduitPeer::TITRE)) $criteria->add(ProduitPeer::TITRE, $this->titre);
        if ($this->isColumnModified(ProduitPeer::SOUS_TITRE)) $criteria->add(ProduitPeer::SOUS_TITRE, $this->sous_titre);
        if ($this->isColumnModified(ProduitPeer::AUTEUR)) $criteria->add(ProduitPeer::AUTEUR, $this->auteur);
        if ($this->isColumnModified(ProduitPeer::DESCRIPTION)) $criteria->add(ProduitPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(ProduitPeer::IMAGE)) $criteria->add(ProduitPeer::IMAGE, $this->image);
        if ($this->isColumnModified(ProduitPeer::LIEN)) $criteria->add(ProduitPeer::LIEN, $this->lien);

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
        $criteria = new Criteria(ProduitPeer::DATABASE_NAME);
        $criteria->add(ProduitPeer::ID, $this->id);

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
     * @param object $copyObj An object of Produit (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitre($this->getTitre());
        $copyObj->setSousTitre($this->getSousTitre());
        $copyObj->setAuteur($this->getAuteur());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setImage($this->getImage());
        $copyObj->setLien($this->getLien());

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

            foreach ($this->getUtilisateurProduits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUtilisateurProduit($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProfilScoreRequeteProduits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProfilScoreRequeteProduit($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProfilScoreUtilisateurProduits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProfilScoreUtilisateurProduit($relObj->copy($deepCopy));
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
     * @return Produit Clone of current object.
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
     * @return ProduitPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ProduitPeer();
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
        if ('UtilisateurProduit' == $relationName) {
            $this->initUtilisateurProduits();
        }
        if ('ProfilScoreRequeteProduit' == $relationName) {
            $this->initProfilScoreRequeteProduits();
        }
        if ('ProfilScoreUtilisateurProduit' == $relationName) {
            $this->initProfilScoreUtilisateurProduits();
        }
        if ('ProfilScoreRequeteUtilisateurProduit' == $relationName) {
            $this->initProfilScoreRequeteUtilisateurProduits();
        }
    }

    /**
     * Clears out the collProduitMotPoidss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Produit The current object (for fluent API support)
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
     * If this Produit is new, it will return
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
                    ->filterByProduit($this)
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
     * @return Produit The current object (for fluent API support)
     */
    public function setProduitMotPoidss(PropelCollection $produitMotPoidss, PropelPDO $con = null)
    {
        $produitMotPoidssToDelete = $this->getProduitMotPoidss(new Criteria(), $con)->diff($produitMotPoidss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->produitMotPoidssScheduledForDeletion = clone $produitMotPoidssToDelete;

        foreach ($produitMotPoidssToDelete as $produitMotPoidsRemoved) {
            $produitMotPoidsRemoved->setProduit(null);
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
                ->filterByProduit($this)
                ->count($con);
        }

        return count($this->collProduitMotPoidss);
    }

    /**
     * Method called to associate a ProduitMotPoids object to this object
     * through the ProduitMotPoids foreign key attribute.
     *
     * @param    ProduitMotPoids $l ProduitMotPoids
     * @return Produit The current object (for fluent API support)
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
        $produitMotPoids->setProduit($this);
    }

    /**
     * @param	ProduitMotPoids $produitMotPoids The produitMotPoids object to remove.
     * @return Produit The current object (for fluent API support)
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
            $produitMotPoids->setProduit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Produit is new, it will return
     * an empty collection; or if this Produit has previously
     * been saved, it will retrieve related ProduitMotPoidss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Produit.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProduitMotPoids[] List of ProduitMotPoids objects
     */
    public function getProduitMotPoidssJoinMot($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProduitMotPoidsQuery::create(null, $criteria);
        $query->joinWith('Mot', $join_behavior);

        return $this->getProduitMotPoidss($query, $con);
    }

    /**
     * Clears out the collUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Produit The current object (for fluent API support)
     * @see        addUtilisateurProduits()
     */
    public function clearUtilisateurProduits()
    {
        $this->collUtilisateurProduits = null; // important to set this to null since that means it is uninitialized
        $this->collUtilisateurProduitsPartial = null;

        return $this;
    }

    /**
     * reset is the collUtilisateurProduits collection loaded partially
     *
     * @return void
     */
    public function resetPartialUtilisateurProduits($v = true)
    {
        $this->collUtilisateurProduitsPartial = $v;
    }

    /**
     * Initializes the collUtilisateurProduits collection.
     *
     * By default this just sets the collUtilisateurProduits collection to an empty array (like clearcollUtilisateurProduits());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUtilisateurProduits($overrideExisting = true)
    {
        if (null !== $this->collUtilisateurProduits && !$overrideExisting) {
            return;
        }
        $this->collUtilisateurProduits = new PropelObjectCollection();
        $this->collUtilisateurProduits->setModel('UtilisateurProduit');
    }

    /**
     * Gets an array of UtilisateurProduit objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Produit is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UtilisateurProduit[] List of UtilisateurProduit objects
     * @throws PropelException
     */
    public function getUtilisateurProduits($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUtilisateurProduitsPartial && !$this->isNew();
        if (null === $this->collUtilisateurProduits || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUtilisateurProduits) {
                // return empty collection
                $this->initUtilisateurProduits();
            } else {
                $collUtilisateurProduits = UtilisateurProduitQuery::create(null, $criteria)
                    ->filterByProduit($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUtilisateurProduitsPartial && count($collUtilisateurProduits)) {
                      $this->initUtilisateurProduits(false);

                      foreach ($collUtilisateurProduits as $obj) {
                        if (false == $this->collUtilisateurProduits->contains($obj)) {
                          $this->collUtilisateurProduits->append($obj);
                        }
                      }

                      $this->collUtilisateurProduitsPartial = true;
                    }

                    $collUtilisateurProduits->getInternalIterator()->rewind();

                    return $collUtilisateurProduits;
                }

                if ($partial && $this->collUtilisateurProduits) {
                    foreach ($this->collUtilisateurProduits as $obj) {
                        if ($obj->isNew()) {
                            $collUtilisateurProduits[] = $obj;
                        }
                    }
                }

                $this->collUtilisateurProduits = $collUtilisateurProduits;
                $this->collUtilisateurProduitsPartial = false;
            }
        }

        return $this->collUtilisateurProduits;
    }

    /**
     * Sets a collection of UtilisateurProduit objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $utilisateurProduits A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Produit The current object (for fluent API support)
     */
    public function setUtilisateurProduits(PropelCollection $utilisateurProduits, PropelPDO $con = null)
    {
        $utilisateurProduitsToDelete = $this->getUtilisateurProduits(new Criteria(), $con)->diff($utilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->utilisateurProduitsScheduledForDeletion = clone $utilisateurProduitsToDelete;

        foreach ($utilisateurProduitsToDelete as $utilisateurProduitRemoved) {
            $utilisateurProduitRemoved->setProduit(null);
        }

        $this->collUtilisateurProduits = null;
        foreach ($utilisateurProduits as $utilisateurProduit) {
            $this->addUtilisateurProduit($utilisateurProduit);
        }

        $this->collUtilisateurProduits = $utilisateurProduits;
        $this->collUtilisateurProduitsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UtilisateurProduit objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UtilisateurProduit objects.
     * @throws PropelException
     */
    public function countUtilisateurProduits(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUtilisateurProduitsPartial && !$this->isNew();
        if (null === $this->collUtilisateurProduits || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUtilisateurProduits) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUtilisateurProduits());
            }
            $query = UtilisateurProduitQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByProduit($this)
                ->count($con);
        }

        return count($this->collUtilisateurProduits);
    }

    /**
     * Method called to associate a UtilisateurProduit object to this object
     * through the UtilisateurProduit foreign key attribute.
     *
     * @param    UtilisateurProduit $l UtilisateurProduit
     * @return Produit The current object (for fluent API support)
     */
    public function addUtilisateurProduit(UtilisateurProduit $l)
    {
        if ($this->collUtilisateurProduits === null) {
            $this->initUtilisateurProduits();
            $this->collUtilisateurProduitsPartial = true;
        }

        if (!in_array($l, $this->collUtilisateurProduits->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUtilisateurProduit($l);

            if ($this->utilisateurProduitsScheduledForDeletion and $this->utilisateurProduitsScheduledForDeletion->contains($l)) {
                $this->utilisateurProduitsScheduledForDeletion->remove($this->utilisateurProduitsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UtilisateurProduit $utilisateurProduit The utilisateurProduit object to add.
     */
    protected function doAddUtilisateurProduit($utilisateurProduit)
    {
        $this->collUtilisateurProduits[]= $utilisateurProduit;
        $utilisateurProduit->setProduit($this);
    }

    /**
     * @param	UtilisateurProduit $utilisateurProduit The utilisateurProduit object to remove.
     * @return Produit The current object (for fluent API support)
     */
    public function removeUtilisateurProduit($utilisateurProduit)
    {
        if ($this->getUtilisateurProduits()->contains($utilisateurProduit)) {
            $this->collUtilisateurProduits->remove($this->collUtilisateurProduits->search($utilisateurProduit));
            if (null === $this->utilisateurProduitsScheduledForDeletion) {
                $this->utilisateurProduitsScheduledForDeletion = clone $this->collUtilisateurProduits;
                $this->utilisateurProduitsScheduledForDeletion->clear();
            }
            $this->utilisateurProduitsScheduledForDeletion[]= clone $utilisateurProduit;
            $utilisateurProduit->setProduit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Produit is new, it will return
     * an empty collection; or if this Produit has previously
     * been saved, it will retrieve related UtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Produit.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UtilisateurProduit[] List of UtilisateurProduit objects
     */
    public function getUtilisateurProduitsJoinUtilisateur($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Utilisateur', $join_behavior);

        return $this->getUtilisateurProduits($query, $con);
    }

    /**
     * Clears out the collProfilScoreRequeteProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Produit The current object (for fluent API support)
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
     * If this Produit is new, it will return
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
                    ->filterByProduit($this)
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
     * @return Produit The current object (for fluent API support)
     */
    public function setProfilScoreRequeteProduits(PropelCollection $profilScoreRequeteProduits, PropelPDO $con = null)
    {
        $profilScoreRequeteProduitsToDelete = $this->getProfilScoreRequeteProduits(new Criteria(), $con)->diff($profilScoreRequeteProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreRequeteProduitsScheduledForDeletion = clone $profilScoreRequeteProduitsToDelete;

        foreach ($profilScoreRequeteProduitsToDelete as $profilScoreRequeteProduitRemoved) {
            $profilScoreRequeteProduitRemoved->setProduit(null);
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
                ->filterByProduit($this)
                ->count($con);
        }

        return count($this->collProfilScoreRequeteProduits);
    }

    /**
     * Method called to associate a ProfilScoreRequeteProduit object to this object
     * through the ProfilScoreRequeteProduit foreign key attribute.
     *
     * @param    ProfilScoreRequeteProduit $l ProfilScoreRequeteProduit
     * @return Produit The current object (for fluent API support)
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
        $profilScoreRequeteProduit->setProduit($this);
    }

    /**
     * @param	ProfilScoreRequeteProduit $profilScoreRequeteProduit The profilScoreRequeteProduit object to remove.
     * @return Produit The current object (for fluent API support)
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
            $profilScoreRequeteProduit->setProduit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Produit is new, it will return
     * an empty collection; or if this Produit has previously
     * been saved, it will retrieve related ProfilScoreRequeteProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Produit.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreRequeteProduit[] List of ProfilScoreRequeteProduit objects
     */
    public function getProfilScoreRequeteProduitsJoinRequete($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreRequeteProduitQuery::create(null, $criteria);
        $query->joinWith('Requete', $join_behavior);

        return $this->getProfilScoreRequeteProduits($query, $con);
    }

    /**
     * Clears out the collProfilScoreUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Produit The current object (for fluent API support)
     * @see        addProfilScoreUtilisateurProduits()
     */
    public function clearProfilScoreUtilisateurProduits()
    {
        $this->collProfilScoreUtilisateurProduits = null; // important to set this to null since that means it is uninitialized
        $this->collProfilScoreUtilisateurProduitsPartial = null;

        return $this;
    }

    /**
     * reset is the collProfilScoreUtilisateurProduits collection loaded partially
     *
     * @return void
     */
    public function resetPartialProfilScoreUtilisateurProduits($v = true)
    {
        $this->collProfilScoreUtilisateurProduitsPartial = $v;
    }

    /**
     * Initializes the collProfilScoreUtilisateurProduits collection.
     *
     * By default this just sets the collProfilScoreUtilisateurProduits collection to an empty array (like clearcollProfilScoreUtilisateurProduits());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProfilScoreUtilisateurProduits($overrideExisting = true)
    {
        if (null !== $this->collProfilScoreUtilisateurProduits && !$overrideExisting) {
            return;
        }
        $this->collProfilScoreUtilisateurProduits = new PropelObjectCollection();
        $this->collProfilScoreUtilisateurProduits->setModel('ProfilScoreUtilisateurProduit');
    }

    /**
     * Gets an array of ProfilScoreUtilisateurProduit objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Produit is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProfilScoreUtilisateurProduit[] List of ProfilScoreUtilisateurProduit objects
     * @throws PropelException
     */
    public function getProfilScoreUtilisateurProduits($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreUtilisateurProduitsPartial && !$this->isNew();
        if (null === $this->collProfilScoreUtilisateurProduits || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreUtilisateurProduits) {
                // return empty collection
                $this->initProfilScoreUtilisateurProduits();
            } else {
                $collProfilScoreUtilisateurProduits = ProfilScoreUtilisateurProduitQuery::create(null, $criteria)
                    ->filterByProduit($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProfilScoreUtilisateurProduitsPartial && count($collProfilScoreUtilisateurProduits)) {
                      $this->initProfilScoreUtilisateurProduits(false);

                      foreach ($collProfilScoreUtilisateurProduits as $obj) {
                        if (false == $this->collProfilScoreUtilisateurProduits->contains($obj)) {
                          $this->collProfilScoreUtilisateurProduits->append($obj);
                        }
                      }

                      $this->collProfilScoreUtilisateurProduitsPartial = true;
                    }

                    $collProfilScoreUtilisateurProduits->getInternalIterator()->rewind();

                    return $collProfilScoreUtilisateurProduits;
                }

                if ($partial && $this->collProfilScoreUtilisateurProduits) {
                    foreach ($this->collProfilScoreUtilisateurProduits as $obj) {
                        if ($obj->isNew()) {
                            $collProfilScoreUtilisateurProduits[] = $obj;
                        }
                    }
                }

                $this->collProfilScoreUtilisateurProduits = $collProfilScoreUtilisateurProduits;
                $this->collProfilScoreUtilisateurProduitsPartial = false;
            }
        }

        return $this->collProfilScoreUtilisateurProduits;
    }

    /**
     * Sets a collection of ProfilScoreUtilisateurProduit objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $profilScoreUtilisateurProduits A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Produit The current object (for fluent API support)
     */
    public function setProfilScoreUtilisateurProduits(PropelCollection $profilScoreUtilisateurProduits, PropelPDO $con = null)
    {
        $profilScoreUtilisateurProduitsToDelete = $this->getProfilScoreUtilisateurProduits(new Criteria(), $con)->diff($profilScoreUtilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreUtilisateurProduitsScheduledForDeletion = clone $profilScoreUtilisateurProduitsToDelete;

        foreach ($profilScoreUtilisateurProduitsToDelete as $profilScoreUtilisateurProduitRemoved) {
            $profilScoreUtilisateurProduitRemoved->setProduit(null);
        }

        $this->collProfilScoreUtilisateurProduits = null;
        foreach ($profilScoreUtilisateurProduits as $profilScoreUtilisateurProduit) {
            $this->addProfilScoreUtilisateurProduit($profilScoreUtilisateurProduit);
        }

        $this->collProfilScoreUtilisateurProduits = $profilScoreUtilisateurProduits;
        $this->collProfilScoreUtilisateurProduitsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProfilScoreUtilisateurProduit objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProfilScoreUtilisateurProduit objects.
     * @throws PropelException
     */
    public function countProfilScoreUtilisateurProduits(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreUtilisateurProduitsPartial && !$this->isNew();
        if (null === $this->collProfilScoreUtilisateurProduits || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreUtilisateurProduits) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProfilScoreUtilisateurProduits());
            }
            $query = ProfilScoreUtilisateurProduitQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByProduit($this)
                ->count($con);
        }

        return count($this->collProfilScoreUtilisateurProduits);
    }

    /**
     * Method called to associate a ProfilScoreUtilisateurProduit object to this object
     * through the ProfilScoreUtilisateurProduit foreign key attribute.
     *
     * @param    ProfilScoreUtilisateurProduit $l ProfilScoreUtilisateurProduit
     * @return Produit The current object (for fluent API support)
     */
    public function addProfilScoreUtilisateurProduit(ProfilScoreUtilisateurProduit $l)
    {
        if ($this->collProfilScoreUtilisateurProduits === null) {
            $this->initProfilScoreUtilisateurProduits();
            $this->collProfilScoreUtilisateurProduitsPartial = true;
        }

        if (!in_array($l, $this->collProfilScoreUtilisateurProduits->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProfilScoreUtilisateurProduit($l);

            if ($this->profilScoreUtilisateurProduitsScheduledForDeletion and $this->profilScoreUtilisateurProduitsScheduledForDeletion->contains($l)) {
                $this->profilScoreUtilisateurProduitsScheduledForDeletion->remove($this->profilScoreUtilisateurProduitsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProfilScoreUtilisateurProduit $profilScoreUtilisateurProduit The profilScoreUtilisateurProduit object to add.
     */
    protected function doAddProfilScoreUtilisateurProduit($profilScoreUtilisateurProduit)
    {
        $this->collProfilScoreUtilisateurProduits[]= $profilScoreUtilisateurProduit;
        $profilScoreUtilisateurProduit->setProduit($this);
    }

    /**
     * @param	ProfilScoreUtilisateurProduit $profilScoreUtilisateurProduit The profilScoreUtilisateurProduit object to remove.
     * @return Produit The current object (for fluent API support)
     */
    public function removeProfilScoreUtilisateurProduit($profilScoreUtilisateurProduit)
    {
        if ($this->getProfilScoreUtilisateurProduits()->contains($profilScoreUtilisateurProduit)) {
            $this->collProfilScoreUtilisateurProduits->remove($this->collProfilScoreUtilisateurProduits->search($profilScoreUtilisateurProduit));
            if (null === $this->profilScoreUtilisateurProduitsScheduledForDeletion) {
                $this->profilScoreUtilisateurProduitsScheduledForDeletion = clone $this->collProfilScoreUtilisateurProduits;
                $this->profilScoreUtilisateurProduitsScheduledForDeletion->clear();
            }
            $this->profilScoreUtilisateurProduitsScheduledForDeletion[]= clone $profilScoreUtilisateurProduit;
            $profilScoreUtilisateurProduit->setProduit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Produit is new, it will return
     * an empty collection; or if this Produit has previously
     * been saved, it will retrieve related ProfilScoreUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Produit.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreUtilisateurProduit[] List of ProfilScoreUtilisateurProduit objects
     */
    public function getProfilScoreUtilisateurProduitsJoinUtilisateur($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreUtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Utilisateur', $join_behavior);

        return $this->getProfilScoreUtilisateurProduits($query, $con);
    }

    /**
     * Clears out the collProfilScoreRequeteUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Produit The current object (for fluent API support)
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
     * If this Produit is new, it will return
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
                    ->filterByProduit($this)
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
     * @return Produit The current object (for fluent API support)
     */
    public function setProfilScoreRequeteUtilisateurProduits(PropelCollection $profilScoreRequeteUtilisateurProduits, PropelPDO $con = null)
    {
        $profilScoreRequeteUtilisateurProduitsToDelete = $this->getProfilScoreRequeteUtilisateurProduits(new Criteria(), $con)->diff($profilScoreRequeteUtilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion = clone $profilScoreRequeteUtilisateurProduitsToDelete;

        foreach ($profilScoreRequeteUtilisateurProduitsToDelete as $profilScoreRequeteUtilisateurProduitRemoved) {
            $profilScoreRequeteUtilisateurProduitRemoved->setProduit(null);
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
                ->filterByProduit($this)
                ->count($con);
        }

        return count($this->collProfilScoreRequeteUtilisateurProduits);
    }

    /**
     * Method called to associate a ProfilScoreRequeteUtilisateurProduit object to this object
     * through the ProfilScoreRequeteUtilisateurProduit foreign key attribute.
     *
     * @param    ProfilScoreRequeteUtilisateurProduit $l ProfilScoreRequeteUtilisateurProduit
     * @return Produit The current object (for fluent API support)
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
        $profilScoreRequeteUtilisateurProduit->setProduit($this);
    }

    /**
     * @param	ProfilScoreRequeteUtilisateurProduit $profilScoreRequeteUtilisateurProduit The profilScoreRequeteUtilisateurProduit object to remove.
     * @return Produit The current object (for fluent API support)
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
            $profilScoreRequeteUtilisateurProduit->setProduit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Produit is new, it will return
     * an empty collection; or if this Produit has previously
     * been saved, it will retrieve related ProfilScoreRequeteUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Produit.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreRequeteUtilisateurProduit[] List of ProfilScoreRequeteUtilisateurProduit objects
     */
    public function getProfilScoreRequeteUtilisateurProduitsJoinRequete($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreRequeteUtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Requete', $join_behavior);

        return $this->getProfilScoreRequeteUtilisateurProduits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Produit is new, it will return
     * an empty collection; or if this Produit has previously
     * been saved, it will retrieve related ProfilScoreRequeteUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Produit.
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->titre = null;
        $this->sous_titre = null;
        $this->auteur = null;
        $this->description = null;
        $this->image = null;
        $this->lien = null;
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
            if ($this->collUtilisateurProduits) {
                foreach ($this->collUtilisateurProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProfilScoreRequeteProduits) {
                foreach ($this->collProfilScoreRequeteProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProfilScoreUtilisateurProduits) {
                foreach ($this->collProfilScoreUtilisateurProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProfilScoreRequeteUtilisateurProduits) {
                foreach ($this->collProfilScoreRequeteUtilisateurProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collProduitMotPoidss instanceof PropelCollection) {
            $this->collProduitMotPoidss->clearIterator();
        }
        $this->collProduitMotPoidss = null;
        if ($this->collUtilisateurProduits instanceof PropelCollection) {
            $this->collUtilisateurProduits->clearIterator();
        }
        $this->collUtilisateurProduits = null;
        if ($this->collProfilScoreRequeteProduits instanceof PropelCollection) {
            $this->collProfilScoreRequeteProduits->clearIterator();
        }
        $this->collProfilScoreRequeteProduits = null;
        if ($this->collProfilScoreUtilisateurProduits instanceof PropelCollection) {
            $this->collProfilScoreUtilisateurProduits->clearIterator();
        }
        $this->collProfilScoreUtilisateurProduits = null;
        if ($this->collProfilScoreRequeteUtilisateurProduits instanceof PropelCollection) {
            $this->collProfilScoreRequeteUtilisateurProduits->clearIterator();
        }
        $this->collProfilScoreRequeteUtilisateurProduits = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ProduitPeer::DEFAULT_STRING_FORMAT);
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
