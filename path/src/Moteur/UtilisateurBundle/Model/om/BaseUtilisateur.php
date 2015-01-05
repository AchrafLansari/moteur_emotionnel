<?php

namespace Moteur\UtilisateurBundle\Model\om;

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
use Moteur\ProduitBundle\Model\UtilisateurProduit;
use Moteur\ProduitBundle\Model\UtilisateurProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreRequeteUtilisateurProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateur;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduit;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurProduitQuery;
use Moteur\RecommendationBundle\Model\ProfilScoreUtilisateurQuery;
use Moteur\RecommendationBundle\Model\Requete;
use Moteur\RecommendationBundle\Model\RequeteQuery;
use Moteur\UtilisateurBundle\Model\Ip;
use Moteur\UtilisateurBundle\Model\IpQuery;
use Moteur\UtilisateurBundle\Model\Utilisateur;
use Moteur\UtilisateurBundle\Model\UtilisateurInteret;
use Moteur\UtilisateurBundle\Model\UtilisateurInteretQuery;
use Moteur\UtilisateurBundle\Model\UtilisateurPeer;
use Moteur\UtilisateurBundle\Model\UtilisateurQuery;

abstract class BaseUtilisateur extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Moteur\\UtilisateurBundle\\Model\\UtilisateurPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UtilisateurPeer
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
     * The value for the nom field.
     * @var        string
     */
    protected $nom;

    /**
     * The value for the prenom field.
     * @var        string
     */
    protected $prenom;

    /**
     * The value for the mail field.
     * @var        string
     */
    protected $mail;

    /**
     * The value for the age field.
     * @var        int
     */
    protected $age;

    /**
     * The value for the ville field.
     * @var        string
     */
    protected $ville;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the ip_utilisateur field.
     * @var        string
     */
    protected $ip_utilisateur;

    /**
     * The value for the ip_id field.
     * @var        int
     */
    protected $ip_id;

    /**
     * @var        Ip
     */
    protected $aIp;

    /**
     * @var        PropelObjectCollection|UtilisateurProduit[] Collection to store aggregation of UtilisateurProduit objects.
     */
    protected $collUtilisateurProduits;
    protected $collUtilisateurProduitsPartial;

    /**
     * @var        PropelObjectCollection|ProfilScoreUtilisateur[] Collection to store aggregation of ProfilScoreUtilisateur objects.
     */
    protected $collProfilScoreUtilisateursRelatedByUtilisateurAId;
    protected $collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial;

    /**
     * @var        PropelObjectCollection|ProfilScoreUtilisateur[] Collection to store aggregation of ProfilScoreUtilisateur objects.
     */
    protected $collProfilScoreUtilisateursRelatedByUtilisateurBId;
    protected $collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial;

    /**
     * @var        PropelObjectCollection|Requete[] Collection to store aggregation of Requete objects.
     */
    protected $collRequetes;
    protected $collRequetesPartial;

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
     * @var        PropelObjectCollection|UtilisateurInteret[] Collection to store aggregation of UtilisateurInteret objects.
     */
    protected $collUtilisateurInterets;
    protected $collUtilisateurInteretsPartial;

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
    protected $utilisateurProduitsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $requetesScheduledForDeletion = null;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $utilisateurInteretsScheduledForDeletion = null;

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
     * Get the [nom] column value.
     *
     * @return string
     */
    public function getNom()
    {

        return $this->nom;
    }

    /**
     * Get the [prenom] column value.
     *
     * @return string
     */
    public function getPrenom()
    {

        return $this->prenom;
    }

    /**
     * Get the [mail] column value.
     *
     * @return string
     */
    public function getMail()
    {

        return $this->mail;
    }

    /**
     * Get the [age] column value.
     *
     * @return int
     */
    public function getAge()
    {

        return $this->age;
    }

    /**
     * Get the [ville] column value.
     *
     * @return string
     */
    public function getVille()
    {

        return $this->ville;
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
     * Get the [ip_utilisateur] column value.
     *
     * @return string
     */
    public function getIpUtilisateur()
    {

        return $this->ip_utilisateur;
    }

    /**
     * Get the [ip_id] column value.
     *
     * @return int
     */
    public function getIpId()
    {

        return $this->ip_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UtilisateurPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [nom] column.
     *
     * @param  string $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setNom($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nom !== $v) {
            $this->nom = $v;
            $this->modifiedColumns[] = UtilisateurPeer::NOM;
        }


        return $this;
    } // setNom()

    /**
     * Set the value of [prenom] column.
     *
     * @param  string $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setPrenom($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->prenom !== $v) {
            $this->prenom = $v;
            $this->modifiedColumns[] = UtilisateurPeer::PRENOM;
        }


        return $this;
    } // setPrenom()

    /**
     * Set the value of [mail] column.
     *
     * @param  string $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setMail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mail !== $v) {
            $this->mail = $v;
            $this->modifiedColumns[] = UtilisateurPeer::MAIL;
        }


        return $this;
    } // setMail()

    /**
     * Set the value of [age] column.
     *
     * @param  int $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setAge($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->age !== $v) {
            $this->age = $v;
            $this->modifiedColumns[] = UtilisateurPeer::AGE;
        }


        return $this;
    } // setAge()

    /**
     * Set the value of [ville] column.
     *
     * @param  string $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setVille($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ville !== $v) {
            $this->ville = $v;
            $this->modifiedColumns[] = UtilisateurPeer::VILLE;
        }


        return $this;
    } // setVille()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = UtilisateurPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [ip_utilisateur] column.
     *
     * @param  string $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setIpUtilisateur($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ip_utilisateur !== $v) {
            $this->ip_utilisateur = $v;
            $this->modifiedColumns[] = UtilisateurPeer::IP_UTILISATEUR;
        }


        return $this;
    } // setIpUtilisateur()

    /**
     * Set the value of [ip_id] column.
     *
     * @param  int $v new value
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setIpId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->ip_id !== $v) {
            $this->ip_id = $v;
            $this->modifiedColumns[] = UtilisateurPeer::IP_ID;
        }

        if ($this->aIp !== null && $this->aIp->getId() !== $v) {
            $this->aIp = null;
        }


        return $this;
    } // setIpId()

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
            $this->nom = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->prenom = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->mail = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->age = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->ville = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->description = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->ip_utilisateur = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->ip_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = UtilisateurPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Utilisateur object", $e);
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

        if ($this->aIp !== null && $this->ip_id !== $this->aIp->getId()) {
            $this->aIp = null;
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
            $con = Propel::getConnection(UtilisateurPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UtilisateurPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aIp = null;
            $this->collUtilisateurProduits = null;

            $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = null;

            $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = null;

            $this->collRequetes = null;

            $this->collProfilScoreUtilisateurProduits = null;

            $this->collProfilScoreRequeteUtilisateurProduits = null;

            $this->collUtilisateurInterets = null;

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
            $con = Propel::getConnection(UtilisateurPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UtilisateurQuery::create()
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
            $con = Propel::getConnection(UtilisateurPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                UtilisateurPeer::addInstanceToPool($this);
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

            if ($this->aIp !== null) {
                if ($this->aIp->isModified() || $this->aIp->isNew()) {
                    $affectedRows += $this->aIp->save($con);
                }
                $this->setIp($this->aIp);
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

            if ($this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion !== null) {
                if (!$this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion->isEmpty()) {
                    ProfilScoreUtilisateurQuery::create()
                        ->filterByPrimaryKeys($this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion = null;
                }
            }

            if ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId !== null) {
                foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion !== null) {
                if (!$this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion->isEmpty()) {
                    ProfilScoreUtilisateurQuery::create()
                        ->filterByPrimaryKeys($this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion = null;
                }
            }

            if ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId !== null) {
                foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId as $referrerFK) {
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

            if ($this->utilisateurInteretsScheduledForDeletion !== null) {
                if (!$this->utilisateurInteretsScheduledForDeletion->isEmpty()) {
                    UtilisateurInteretQuery::create()
                        ->filterByPrimaryKeys($this->utilisateurInteretsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->utilisateurInteretsScheduledForDeletion = null;
                }
            }

            if ($this->collUtilisateurInterets !== null) {
                foreach ($this->collUtilisateurInterets as $referrerFK) {
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

        $this->modifiedColumns[] = UtilisateurPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UtilisateurPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UtilisateurPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UtilisateurPeer::NOM)) {
            $modifiedColumns[':p' . $index++]  = '`nom`';
        }
        if ($this->isColumnModified(UtilisateurPeer::PRENOM)) {
            $modifiedColumns[':p' . $index++]  = '`prenom`';
        }
        if ($this->isColumnModified(UtilisateurPeer::MAIL)) {
            $modifiedColumns[':p' . $index++]  = '`mail`';
        }
        if ($this->isColumnModified(UtilisateurPeer::AGE)) {
            $modifiedColumns[':p' . $index++]  = '`age`';
        }
        if ($this->isColumnModified(UtilisateurPeer::VILLE)) {
            $modifiedColumns[':p' . $index++]  = '`ville`';
        }
        if ($this->isColumnModified(UtilisateurPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(UtilisateurPeer::IP_UTILISATEUR)) {
            $modifiedColumns[':p' . $index++]  = '`ip_utilisateur`';
        }
        if ($this->isColumnModified(UtilisateurPeer::IP_ID)) {
            $modifiedColumns[':p' . $index++]  = '`ip_id`';
        }

        $sql = sprintf(
            'INSERT INTO `utilisateur` (%s) VALUES (%s)',
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
                    case '`nom`':
                        $stmt->bindValue($identifier, $this->nom, PDO::PARAM_STR);
                        break;
                    case '`prenom`':
                        $stmt->bindValue($identifier, $this->prenom, PDO::PARAM_STR);
                        break;
                    case '`mail`':
                        $stmt->bindValue($identifier, $this->mail, PDO::PARAM_STR);
                        break;
                    case '`age`':
                        $stmt->bindValue($identifier, $this->age, PDO::PARAM_INT);
                        break;
                    case '`ville`':
                        $stmt->bindValue($identifier, $this->ville, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`ip_utilisateur`':
                        $stmt->bindValue($identifier, $this->ip_utilisateur, PDO::PARAM_STR);
                        break;
                    case '`ip_id`':
                        $stmt->bindValue($identifier, $this->ip_id, PDO::PARAM_INT);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aIp !== null) {
                if (!$this->aIp->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aIp->getValidationFailures());
                }
            }


            if (($retval = UtilisateurPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collUtilisateurProduits !== null) {
                    foreach ($this->collUtilisateurProduits as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId !== null) {
                    foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId !== null) {
                    foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId as $referrerFK) {
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

                if ($this->collUtilisateurInterets !== null) {
                    foreach ($this->collUtilisateurInterets as $referrerFK) {
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
        $pos = UtilisateurPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getNom();
                break;
            case 2:
                return $this->getPrenom();
                break;
            case 3:
                return $this->getMail();
                break;
            case 4:
                return $this->getAge();
                break;
            case 5:
                return $this->getVille();
                break;
            case 6:
                return $this->getDescription();
                break;
            case 7:
                return $this->getIpUtilisateur();
                break;
            case 8:
                return $this->getIpId();
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
        if (isset($alreadyDumpedObjects['Utilisateur'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Utilisateur'][$this->getPrimaryKey()] = true;
        $keys = UtilisateurPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNom(),
            $keys[2] => $this->getPrenom(),
            $keys[3] => $this->getMail(),
            $keys[4] => $this->getAge(),
            $keys[5] => $this->getVille(),
            $keys[6] => $this->getDescription(),
            $keys[7] => $this->getIpUtilisateur(),
            $keys[8] => $this->getIpId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aIp) {
                $result['Ip'] = $this->aIp->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUtilisateurProduits) {
                $result['UtilisateurProduits'] = $this->collUtilisateurProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreUtilisateursRelatedByUtilisateurAId) {
                $result['ProfilScoreUtilisateursRelatedByUtilisateurAId'] = $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreUtilisateursRelatedByUtilisateurBId) {
                $result['ProfilScoreUtilisateursRelatedByUtilisateurBId'] = $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRequetes) {
                $result['Requetes'] = $this->collRequetes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreUtilisateurProduits) {
                $result['ProfilScoreUtilisateurProduits'] = $this->collProfilScoreUtilisateurProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProfilScoreRequeteUtilisateurProduits) {
                $result['ProfilScoreRequeteUtilisateurProduits'] = $this->collProfilScoreRequeteUtilisateurProduits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUtilisateurInterets) {
                $result['UtilisateurInterets'] = $this->collUtilisateurInterets->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = UtilisateurPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setNom($value);
                break;
            case 2:
                $this->setPrenom($value);
                break;
            case 3:
                $this->setMail($value);
                break;
            case 4:
                $this->setAge($value);
                break;
            case 5:
                $this->setVille($value);
                break;
            case 6:
                $this->setDescription($value);
                break;
            case 7:
                $this->setIpUtilisateur($value);
                break;
            case 8:
                $this->setIpId($value);
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
        $keys = UtilisateurPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setNom($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPrenom($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setMail($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAge($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setVille($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDescription($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIpUtilisateur($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setIpId($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UtilisateurPeer::DATABASE_NAME);

        if ($this->isColumnModified(UtilisateurPeer::ID)) $criteria->add(UtilisateurPeer::ID, $this->id);
        if ($this->isColumnModified(UtilisateurPeer::NOM)) $criteria->add(UtilisateurPeer::NOM, $this->nom);
        if ($this->isColumnModified(UtilisateurPeer::PRENOM)) $criteria->add(UtilisateurPeer::PRENOM, $this->prenom);
        if ($this->isColumnModified(UtilisateurPeer::MAIL)) $criteria->add(UtilisateurPeer::MAIL, $this->mail);
        if ($this->isColumnModified(UtilisateurPeer::AGE)) $criteria->add(UtilisateurPeer::AGE, $this->age);
        if ($this->isColumnModified(UtilisateurPeer::VILLE)) $criteria->add(UtilisateurPeer::VILLE, $this->ville);
        if ($this->isColumnModified(UtilisateurPeer::DESCRIPTION)) $criteria->add(UtilisateurPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(UtilisateurPeer::IP_UTILISATEUR)) $criteria->add(UtilisateurPeer::IP_UTILISATEUR, $this->ip_utilisateur);
        if ($this->isColumnModified(UtilisateurPeer::IP_ID)) $criteria->add(UtilisateurPeer::IP_ID, $this->ip_id);

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
        $criteria = new Criteria(UtilisateurPeer::DATABASE_NAME);
        $criteria->add(UtilisateurPeer::ID, $this->id);

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
     * @param object $copyObj An object of Utilisateur (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNom($this->getNom());
        $copyObj->setPrenom($this->getPrenom());
        $copyObj->setMail($this->getMail());
        $copyObj->setAge($this->getAge());
        $copyObj->setVille($this->getVille());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setIpUtilisateur($this->getIpUtilisateur());
        $copyObj->setIpId($this->getIpId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getUtilisateurProduits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUtilisateurProduit($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProfilScoreUtilisateursRelatedByUtilisateurAId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProfilScoreUtilisateurRelatedByUtilisateurAId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProfilScoreUtilisateursRelatedByUtilisateurBId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProfilScoreUtilisateurRelatedByUtilisateurBId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRequetes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRequete($relObj->copy($deepCopy));
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

            foreach ($this->getUtilisateurInterets() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUtilisateurInteret($relObj->copy($deepCopy));
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
     * @return Utilisateur Clone of current object.
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
     * @return UtilisateurPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UtilisateurPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Ip object.
     *
     * @param                  Ip $v
     * @return Utilisateur The current object (for fluent API support)
     * @throws PropelException
     */
    public function setIp(Ip $v = null)
    {
        if ($v === null) {
            $this->setIpId(NULL);
        } else {
            $this->setIpId($v->getId());
        }

        $this->aIp = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Ip object, it will not be re-added.
        if ($v !== null) {
            $v->addUtilisateur($this);
        }


        return $this;
    }


    /**
     * Get the associated Ip object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Ip The associated Ip object.
     * @throws PropelException
     */
    public function getIp(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aIp === null && ($this->ip_id !== null) && $doQuery) {
            $this->aIp = IpQuery::create()->findPk($this->ip_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aIp->addUtilisateurs($this);
             */
        }

        return $this->aIp;
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
        if ('UtilisateurProduit' == $relationName) {
            $this->initUtilisateurProduits();
        }
        if ('ProfilScoreUtilisateurRelatedByUtilisateurAId' == $relationName) {
            $this->initProfilScoreUtilisateursRelatedByUtilisateurAId();
        }
        if ('ProfilScoreUtilisateurRelatedByUtilisateurBId' == $relationName) {
            $this->initProfilScoreUtilisateursRelatedByUtilisateurBId();
        }
        if ('Requete' == $relationName) {
            $this->initRequetes();
        }
        if ('ProfilScoreUtilisateurProduit' == $relationName) {
            $this->initProfilScoreUtilisateurProduits();
        }
        if ('ProfilScoreRequeteUtilisateurProduit' == $relationName) {
            $this->initProfilScoreRequeteUtilisateurProduits();
        }
        if ('UtilisateurInteret' == $relationName) {
            $this->initUtilisateurInterets();
        }
    }

    /**
     * Clears out the collUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
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
     * If this Utilisateur is new, it will return
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
                    ->filterByUtilisateur($this)
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
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setUtilisateurProduits(PropelCollection $utilisateurProduits, PropelPDO $con = null)
    {
        $utilisateurProduitsToDelete = $this->getUtilisateurProduits(new Criteria(), $con)->diff($utilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->utilisateurProduitsScheduledForDeletion = clone $utilisateurProduitsToDelete;

        foreach ($utilisateurProduitsToDelete as $utilisateurProduitRemoved) {
            $utilisateurProduitRemoved->setUtilisateur(null);
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
                ->filterByUtilisateur($this)
                ->count($con);
        }

        return count($this->collUtilisateurProduits);
    }

    /**
     * Method called to associate a UtilisateurProduit object to this object
     * through the UtilisateurProduit foreign key attribute.
     *
     * @param    UtilisateurProduit $l UtilisateurProduit
     * @return Utilisateur The current object (for fluent API support)
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
        $utilisateurProduit->setUtilisateur($this);
    }

    /**
     * @param	UtilisateurProduit $utilisateurProduit The utilisateurProduit object to remove.
     * @return Utilisateur The current object (for fluent API support)
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
            $utilisateurProduit->setUtilisateur(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Utilisateur is new, it will return
     * an empty collection; or if this Utilisateur has previously
     * been saved, it will retrieve related UtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Utilisateur.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UtilisateurProduit[] List of UtilisateurProduit objects
     */
    public function getUtilisateurProduitsJoinProduit($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Produit', $join_behavior);

        return $this->getUtilisateurProduits($query, $con);
    }

    /**
     * Clears out the collProfilScoreUtilisateursRelatedByUtilisateurAId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
     * @see        addProfilScoreUtilisateursRelatedByUtilisateurAId()
     */
    public function clearProfilScoreUtilisateursRelatedByUtilisateurAId()
    {
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = null; // important to set this to null since that means it is uninitialized
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial = null;

        return $this;
    }

    /**
     * reset is the collProfilScoreUtilisateursRelatedByUtilisateurAId collection loaded partially
     *
     * @return void
     */
    public function resetPartialProfilScoreUtilisateursRelatedByUtilisateurAId($v = true)
    {
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial = $v;
    }

    /**
     * Initializes the collProfilScoreUtilisateursRelatedByUtilisateurAId collection.
     *
     * By default this just sets the collProfilScoreUtilisateursRelatedByUtilisateurAId collection to an empty array (like clearcollProfilScoreUtilisateursRelatedByUtilisateurAId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProfilScoreUtilisateursRelatedByUtilisateurAId($overrideExisting = true)
    {
        if (null !== $this->collProfilScoreUtilisateursRelatedByUtilisateurAId && !$overrideExisting) {
            return;
        }
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = new PropelObjectCollection();
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->setModel('ProfilScoreUtilisateur');
    }

    /**
     * Gets an array of ProfilScoreUtilisateur objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Utilisateur is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProfilScoreUtilisateur[] List of ProfilScoreUtilisateur objects
     * @throws PropelException
     */
    public function getProfilScoreUtilisateursRelatedByUtilisateurAId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial && !$this->isNew();
        if (null === $this->collProfilScoreUtilisateursRelatedByUtilisateurAId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreUtilisateursRelatedByUtilisateurAId) {
                // return empty collection
                $this->initProfilScoreUtilisateursRelatedByUtilisateurAId();
            } else {
                $collProfilScoreUtilisateursRelatedByUtilisateurAId = ProfilScoreUtilisateurQuery::create(null, $criteria)
                    ->filterByUtilisateurRelatedByUtilisateurAId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial && count($collProfilScoreUtilisateursRelatedByUtilisateurAId)) {
                      $this->initProfilScoreUtilisateursRelatedByUtilisateurAId(false);

                      foreach ($collProfilScoreUtilisateursRelatedByUtilisateurAId as $obj) {
                        if (false == $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->contains($obj)) {
                          $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->append($obj);
                        }
                      }

                      $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial = true;
                    }

                    $collProfilScoreUtilisateursRelatedByUtilisateurAId->getInternalIterator()->rewind();

                    return $collProfilScoreUtilisateursRelatedByUtilisateurAId;
                }

                if ($partial && $this->collProfilScoreUtilisateursRelatedByUtilisateurAId) {
                    foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId as $obj) {
                        if ($obj->isNew()) {
                            $collProfilScoreUtilisateursRelatedByUtilisateurAId[] = $obj;
                        }
                    }
                }

                $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = $collProfilScoreUtilisateursRelatedByUtilisateurAId;
                $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial = false;
            }
        }

        return $this->collProfilScoreUtilisateursRelatedByUtilisateurAId;
    }

    /**
     * Sets a collection of ProfilScoreUtilisateurRelatedByUtilisateurAId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $profilScoreUtilisateursRelatedByUtilisateurAId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setProfilScoreUtilisateursRelatedByUtilisateurAId(PropelCollection $profilScoreUtilisateursRelatedByUtilisateurAId, PropelPDO $con = null)
    {
        $profilScoreUtilisateursRelatedByUtilisateurAIdToDelete = $this->getProfilScoreUtilisateursRelatedByUtilisateurAId(new Criteria(), $con)->diff($profilScoreUtilisateursRelatedByUtilisateurAId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion = clone $profilScoreUtilisateursRelatedByUtilisateurAIdToDelete;

        foreach ($profilScoreUtilisateursRelatedByUtilisateurAIdToDelete as $profilScoreUtilisateurRelatedByUtilisateurAIdRemoved) {
            $profilScoreUtilisateurRelatedByUtilisateurAIdRemoved->setUtilisateurRelatedByUtilisateurAId(null);
        }

        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = null;
        foreach ($profilScoreUtilisateursRelatedByUtilisateurAId as $profilScoreUtilisateurRelatedByUtilisateurAId) {
            $this->addProfilScoreUtilisateurRelatedByUtilisateurAId($profilScoreUtilisateurRelatedByUtilisateurAId);
        }

        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = $profilScoreUtilisateursRelatedByUtilisateurAId;
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProfilScoreUtilisateur objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProfilScoreUtilisateur objects.
     * @throws PropelException
     */
    public function countProfilScoreUtilisateursRelatedByUtilisateurAId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial && !$this->isNew();
        if (null === $this->collProfilScoreUtilisateursRelatedByUtilisateurAId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreUtilisateursRelatedByUtilisateurAId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProfilScoreUtilisateursRelatedByUtilisateurAId());
            }
            $query = ProfilScoreUtilisateurQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUtilisateurRelatedByUtilisateurAId($this)
                ->count($con);
        }

        return count($this->collProfilScoreUtilisateursRelatedByUtilisateurAId);
    }

    /**
     * Method called to associate a ProfilScoreUtilisateur object to this object
     * through the ProfilScoreUtilisateur foreign key attribute.
     *
     * @param    ProfilScoreUtilisateur $l ProfilScoreUtilisateur
     * @return Utilisateur The current object (for fluent API support)
     */
    public function addProfilScoreUtilisateurRelatedByUtilisateurAId(ProfilScoreUtilisateur $l)
    {
        if ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId === null) {
            $this->initProfilScoreUtilisateursRelatedByUtilisateurAId();
            $this->collProfilScoreUtilisateursRelatedByUtilisateurAIdPartial = true;
        }

        if (!in_array($l, $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProfilScoreUtilisateurRelatedByUtilisateurAId($l);

            if ($this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion and $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion->contains($l)) {
                $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion->remove($this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProfilScoreUtilisateurRelatedByUtilisateurAId $profilScoreUtilisateurRelatedByUtilisateurAId The profilScoreUtilisateurRelatedByUtilisateurAId object to add.
     */
    protected function doAddProfilScoreUtilisateurRelatedByUtilisateurAId($profilScoreUtilisateurRelatedByUtilisateurAId)
    {
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId[]= $profilScoreUtilisateurRelatedByUtilisateurAId;
        $profilScoreUtilisateurRelatedByUtilisateurAId->setUtilisateurRelatedByUtilisateurAId($this);
    }

    /**
     * @param	ProfilScoreUtilisateurRelatedByUtilisateurAId $profilScoreUtilisateurRelatedByUtilisateurAId The profilScoreUtilisateurRelatedByUtilisateurAId object to remove.
     * @return Utilisateur The current object (for fluent API support)
     */
    public function removeProfilScoreUtilisateurRelatedByUtilisateurAId($profilScoreUtilisateurRelatedByUtilisateurAId)
    {
        if ($this->getProfilScoreUtilisateursRelatedByUtilisateurAId()->contains($profilScoreUtilisateurRelatedByUtilisateurAId)) {
            $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->remove($this->collProfilScoreUtilisateursRelatedByUtilisateurAId->search($profilScoreUtilisateurRelatedByUtilisateurAId));
            if (null === $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion) {
                $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion = clone $this->collProfilScoreUtilisateursRelatedByUtilisateurAId;
                $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion->clear();
            }
            $this->profilScoreUtilisateursRelatedByUtilisateurAIdScheduledForDeletion[]= clone $profilScoreUtilisateurRelatedByUtilisateurAId;
            $profilScoreUtilisateurRelatedByUtilisateurAId->setUtilisateurRelatedByUtilisateurAId(null);
        }

        return $this;
    }

    /**
     * Clears out the collProfilScoreUtilisateursRelatedByUtilisateurBId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
     * @see        addProfilScoreUtilisateursRelatedByUtilisateurBId()
     */
    public function clearProfilScoreUtilisateursRelatedByUtilisateurBId()
    {
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = null; // important to set this to null since that means it is uninitialized
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial = null;

        return $this;
    }

    /**
     * reset is the collProfilScoreUtilisateursRelatedByUtilisateurBId collection loaded partially
     *
     * @return void
     */
    public function resetPartialProfilScoreUtilisateursRelatedByUtilisateurBId($v = true)
    {
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial = $v;
    }

    /**
     * Initializes the collProfilScoreUtilisateursRelatedByUtilisateurBId collection.
     *
     * By default this just sets the collProfilScoreUtilisateursRelatedByUtilisateurBId collection to an empty array (like clearcollProfilScoreUtilisateursRelatedByUtilisateurBId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProfilScoreUtilisateursRelatedByUtilisateurBId($overrideExisting = true)
    {
        if (null !== $this->collProfilScoreUtilisateursRelatedByUtilisateurBId && !$overrideExisting) {
            return;
        }
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = new PropelObjectCollection();
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->setModel('ProfilScoreUtilisateur');
    }

    /**
     * Gets an array of ProfilScoreUtilisateur objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Utilisateur is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProfilScoreUtilisateur[] List of ProfilScoreUtilisateur objects
     * @throws PropelException
     */
    public function getProfilScoreUtilisateursRelatedByUtilisateurBId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial && !$this->isNew();
        if (null === $this->collProfilScoreUtilisateursRelatedByUtilisateurBId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreUtilisateursRelatedByUtilisateurBId) {
                // return empty collection
                $this->initProfilScoreUtilisateursRelatedByUtilisateurBId();
            } else {
                $collProfilScoreUtilisateursRelatedByUtilisateurBId = ProfilScoreUtilisateurQuery::create(null, $criteria)
                    ->filterByUtilisateurRelatedByUtilisateurBId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial && count($collProfilScoreUtilisateursRelatedByUtilisateurBId)) {
                      $this->initProfilScoreUtilisateursRelatedByUtilisateurBId(false);

                      foreach ($collProfilScoreUtilisateursRelatedByUtilisateurBId as $obj) {
                        if (false == $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->contains($obj)) {
                          $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->append($obj);
                        }
                      }

                      $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial = true;
                    }

                    $collProfilScoreUtilisateursRelatedByUtilisateurBId->getInternalIterator()->rewind();

                    return $collProfilScoreUtilisateursRelatedByUtilisateurBId;
                }

                if ($partial && $this->collProfilScoreUtilisateursRelatedByUtilisateurBId) {
                    foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId as $obj) {
                        if ($obj->isNew()) {
                            $collProfilScoreUtilisateursRelatedByUtilisateurBId[] = $obj;
                        }
                    }
                }

                $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = $collProfilScoreUtilisateursRelatedByUtilisateurBId;
                $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial = false;
            }
        }

        return $this->collProfilScoreUtilisateursRelatedByUtilisateurBId;
    }

    /**
     * Sets a collection of ProfilScoreUtilisateurRelatedByUtilisateurBId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $profilScoreUtilisateursRelatedByUtilisateurBId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setProfilScoreUtilisateursRelatedByUtilisateurBId(PropelCollection $profilScoreUtilisateursRelatedByUtilisateurBId, PropelPDO $con = null)
    {
        $profilScoreUtilisateursRelatedByUtilisateurBIdToDelete = $this->getProfilScoreUtilisateursRelatedByUtilisateurBId(new Criteria(), $con)->diff($profilScoreUtilisateursRelatedByUtilisateurBId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion = clone $profilScoreUtilisateursRelatedByUtilisateurBIdToDelete;

        foreach ($profilScoreUtilisateursRelatedByUtilisateurBIdToDelete as $profilScoreUtilisateurRelatedByUtilisateurBIdRemoved) {
            $profilScoreUtilisateurRelatedByUtilisateurBIdRemoved->setUtilisateurRelatedByUtilisateurBId(null);
        }

        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = null;
        foreach ($profilScoreUtilisateursRelatedByUtilisateurBId as $profilScoreUtilisateurRelatedByUtilisateurBId) {
            $this->addProfilScoreUtilisateurRelatedByUtilisateurBId($profilScoreUtilisateurRelatedByUtilisateurBId);
        }

        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = $profilScoreUtilisateursRelatedByUtilisateurBId;
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProfilScoreUtilisateur objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProfilScoreUtilisateur objects.
     * @throws PropelException
     */
    public function countProfilScoreUtilisateursRelatedByUtilisateurBId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial && !$this->isNew();
        if (null === $this->collProfilScoreUtilisateursRelatedByUtilisateurBId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProfilScoreUtilisateursRelatedByUtilisateurBId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProfilScoreUtilisateursRelatedByUtilisateurBId());
            }
            $query = ProfilScoreUtilisateurQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUtilisateurRelatedByUtilisateurBId($this)
                ->count($con);
        }

        return count($this->collProfilScoreUtilisateursRelatedByUtilisateurBId);
    }

    /**
     * Method called to associate a ProfilScoreUtilisateur object to this object
     * through the ProfilScoreUtilisateur foreign key attribute.
     *
     * @param    ProfilScoreUtilisateur $l ProfilScoreUtilisateur
     * @return Utilisateur The current object (for fluent API support)
     */
    public function addProfilScoreUtilisateurRelatedByUtilisateurBId(ProfilScoreUtilisateur $l)
    {
        if ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId === null) {
            $this->initProfilScoreUtilisateursRelatedByUtilisateurBId();
            $this->collProfilScoreUtilisateursRelatedByUtilisateurBIdPartial = true;
        }

        if (!in_array($l, $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProfilScoreUtilisateurRelatedByUtilisateurBId($l);

            if ($this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion and $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion->contains($l)) {
                $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion->remove($this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProfilScoreUtilisateurRelatedByUtilisateurBId $profilScoreUtilisateurRelatedByUtilisateurBId The profilScoreUtilisateurRelatedByUtilisateurBId object to add.
     */
    protected function doAddProfilScoreUtilisateurRelatedByUtilisateurBId($profilScoreUtilisateurRelatedByUtilisateurBId)
    {
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId[]= $profilScoreUtilisateurRelatedByUtilisateurBId;
        $profilScoreUtilisateurRelatedByUtilisateurBId->setUtilisateurRelatedByUtilisateurBId($this);
    }

    /**
     * @param	ProfilScoreUtilisateurRelatedByUtilisateurBId $profilScoreUtilisateurRelatedByUtilisateurBId The profilScoreUtilisateurRelatedByUtilisateurBId object to remove.
     * @return Utilisateur The current object (for fluent API support)
     */
    public function removeProfilScoreUtilisateurRelatedByUtilisateurBId($profilScoreUtilisateurRelatedByUtilisateurBId)
    {
        if ($this->getProfilScoreUtilisateursRelatedByUtilisateurBId()->contains($profilScoreUtilisateurRelatedByUtilisateurBId)) {
            $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->remove($this->collProfilScoreUtilisateursRelatedByUtilisateurBId->search($profilScoreUtilisateurRelatedByUtilisateurBId));
            if (null === $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion) {
                $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion = clone $this->collProfilScoreUtilisateursRelatedByUtilisateurBId;
                $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion->clear();
            }
            $this->profilScoreUtilisateursRelatedByUtilisateurBIdScheduledForDeletion[]= clone $profilScoreUtilisateurRelatedByUtilisateurBId;
            $profilScoreUtilisateurRelatedByUtilisateurBId->setUtilisateurRelatedByUtilisateurBId(null);
        }

        return $this;
    }

    /**
     * Clears out the collRequetes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
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
     * If this Utilisateur is new, it will return
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
                    ->filterByUtilisateur($this)
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
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setRequetes(PropelCollection $requetes, PropelPDO $con = null)
    {
        $requetesToDelete = $this->getRequetes(new Criteria(), $con)->diff($requetes);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->requetesScheduledForDeletion = clone $requetesToDelete;

        foreach ($requetesToDelete as $requeteRemoved) {
            $requeteRemoved->setUtilisateur(null);
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
                ->filterByUtilisateur($this)
                ->count($con);
        }

        return count($this->collRequetes);
    }

    /**
     * Method called to associate a Requete object to this object
     * through the Requete foreign key attribute.
     *
     * @param    Requete $l Requete
     * @return Utilisateur The current object (for fluent API support)
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
        $requete->setUtilisateur($this);
    }

    /**
     * @param	Requete $requete The requete object to remove.
     * @return Utilisateur The current object (for fluent API support)
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
            $requete->setUtilisateur(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Utilisateur is new, it will return
     * an empty collection; or if this Utilisateur has previously
     * been saved, it will retrieve related Requetes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Utilisateur.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Requete[] List of Requete objects
     */
    public function getRequetesJoinMot($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RequeteQuery::create(null, $criteria);
        $query->joinWith('Mot', $join_behavior);

        return $this->getRequetes($query, $con);
    }

    /**
     * Clears out the collProfilScoreUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
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
     * If this Utilisateur is new, it will return
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
                    ->filterByUtilisateur($this)
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
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setProfilScoreUtilisateurProduits(PropelCollection $profilScoreUtilisateurProduits, PropelPDO $con = null)
    {
        $profilScoreUtilisateurProduitsToDelete = $this->getProfilScoreUtilisateurProduits(new Criteria(), $con)->diff($profilScoreUtilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreUtilisateurProduitsScheduledForDeletion = clone $profilScoreUtilisateurProduitsToDelete;

        foreach ($profilScoreUtilisateurProduitsToDelete as $profilScoreUtilisateurProduitRemoved) {
            $profilScoreUtilisateurProduitRemoved->setUtilisateur(null);
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
                ->filterByUtilisateur($this)
                ->count($con);
        }

        return count($this->collProfilScoreUtilisateurProduits);
    }

    /**
     * Method called to associate a ProfilScoreUtilisateurProduit object to this object
     * through the ProfilScoreUtilisateurProduit foreign key attribute.
     *
     * @param    ProfilScoreUtilisateurProduit $l ProfilScoreUtilisateurProduit
     * @return Utilisateur The current object (for fluent API support)
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
        $profilScoreUtilisateurProduit->setUtilisateur($this);
    }

    /**
     * @param	ProfilScoreUtilisateurProduit $profilScoreUtilisateurProduit The profilScoreUtilisateurProduit object to remove.
     * @return Utilisateur The current object (for fluent API support)
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
            $profilScoreUtilisateurProduit->setUtilisateur(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Utilisateur is new, it will return
     * an empty collection; or if this Utilisateur has previously
     * been saved, it will retrieve related ProfilScoreUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Utilisateur.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProfilScoreUtilisateurProduit[] List of ProfilScoreUtilisateurProduit objects
     */
    public function getProfilScoreUtilisateurProduitsJoinProduit($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProfilScoreUtilisateurProduitQuery::create(null, $criteria);
        $query->joinWith('Produit', $join_behavior);

        return $this->getProfilScoreUtilisateurProduits($query, $con);
    }

    /**
     * Clears out the collProfilScoreRequeteUtilisateurProduits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
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
     * If this Utilisateur is new, it will return
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
                    ->filterByUtilisateur($this)
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
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setProfilScoreRequeteUtilisateurProduits(PropelCollection $profilScoreRequeteUtilisateurProduits, PropelPDO $con = null)
    {
        $profilScoreRequeteUtilisateurProduitsToDelete = $this->getProfilScoreRequeteUtilisateurProduits(new Criteria(), $con)->diff($profilScoreRequeteUtilisateurProduits);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->profilScoreRequeteUtilisateurProduitsScheduledForDeletion = clone $profilScoreRequeteUtilisateurProduitsToDelete;

        foreach ($profilScoreRequeteUtilisateurProduitsToDelete as $profilScoreRequeteUtilisateurProduitRemoved) {
            $profilScoreRequeteUtilisateurProduitRemoved->setUtilisateur(null);
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
                ->filterByUtilisateur($this)
                ->count($con);
        }

        return count($this->collProfilScoreRequeteUtilisateurProduits);
    }

    /**
     * Method called to associate a ProfilScoreRequeteUtilisateurProduit object to this object
     * through the ProfilScoreRequeteUtilisateurProduit foreign key attribute.
     *
     * @param    ProfilScoreRequeteUtilisateurProduit $l ProfilScoreRequeteUtilisateurProduit
     * @return Utilisateur The current object (for fluent API support)
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
        $profilScoreRequeteUtilisateurProduit->setUtilisateur($this);
    }

    /**
     * @param	ProfilScoreRequeteUtilisateurProduit $profilScoreRequeteUtilisateurProduit The profilScoreRequeteUtilisateurProduit object to remove.
     * @return Utilisateur The current object (for fluent API support)
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
            $profilScoreRequeteUtilisateurProduit->setUtilisateur(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Utilisateur is new, it will return
     * an empty collection; or if this Utilisateur has previously
     * been saved, it will retrieve related ProfilScoreRequeteUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Utilisateur.
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
     * Otherwise if this Utilisateur is new, it will return
     * an empty collection; or if this Utilisateur has previously
     * been saved, it will retrieve related ProfilScoreRequeteUtilisateurProduits from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Utilisateur.
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
     * Clears out the collUtilisateurInterets collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Utilisateur The current object (for fluent API support)
     * @see        addUtilisateurInterets()
     */
    public function clearUtilisateurInterets()
    {
        $this->collUtilisateurInterets = null; // important to set this to null since that means it is uninitialized
        $this->collUtilisateurInteretsPartial = null;

        return $this;
    }

    /**
     * reset is the collUtilisateurInterets collection loaded partially
     *
     * @return void
     */
    public function resetPartialUtilisateurInterets($v = true)
    {
        $this->collUtilisateurInteretsPartial = $v;
    }

    /**
     * Initializes the collUtilisateurInterets collection.
     *
     * By default this just sets the collUtilisateurInterets collection to an empty array (like clearcollUtilisateurInterets());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUtilisateurInterets($overrideExisting = true)
    {
        if (null !== $this->collUtilisateurInterets && !$overrideExisting) {
            return;
        }
        $this->collUtilisateurInterets = new PropelObjectCollection();
        $this->collUtilisateurInterets->setModel('UtilisateurInteret');
    }

    /**
     * Gets an array of UtilisateurInteret objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Utilisateur is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UtilisateurInteret[] List of UtilisateurInteret objects
     * @throws PropelException
     */
    public function getUtilisateurInterets($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUtilisateurInteretsPartial && !$this->isNew();
        if (null === $this->collUtilisateurInterets || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUtilisateurInterets) {
                // return empty collection
                $this->initUtilisateurInterets();
            } else {
                $collUtilisateurInterets = UtilisateurInteretQuery::create(null, $criteria)
                    ->filterByUtilisateur($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUtilisateurInteretsPartial && count($collUtilisateurInterets)) {
                      $this->initUtilisateurInterets(false);

                      foreach ($collUtilisateurInterets as $obj) {
                        if (false == $this->collUtilisateurInterets->contains($obj)) {
                          $this->collUtilisateurInterets->append($obj);
                        }
                      }

                      $this->collUtilisateurInteretsPartial = true;
                    }

                    $collUtilisateurInterets->getInternalIterator()->rewind();

                    return $collUtilisateurInterets;
                }

                if ($partial && $this->collUtilisateurInterets) {
                    foreach ($this->collUtilisateurInterets as $obj) {
                        if ($obj->isNew()) {
                            $collUtilisateurInterets[] = $obj;
                        }
                    }
                }

                $this->collUtilisateurInterets = $collUtilisateurInterets;
                $this->collUtilisateurInteretsPartial = false;
            }
        }

        return $this->collUtilisateurInterets;
    }

    /**
     * Sets a collection of UtilisateurInteret objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $utilisateurInterets A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Utilisateur The current object (for fluent API support)
     */
    public function setUtilisateurInterets(PropelCollection $utilisateurInterets, PropelPDO $con = null)
    {
        $utilisateurInteretsToDelete = $this->getUtilisateurInterets(new Criteria(), $con)->diff($utilisateurInterets);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->utilisateurInteretsScheduledForDeletion = clone $utilisateurInteretsToDelete;

        foreach ($utilisateurInteretsToDelete as $utilisateurInteretRemoved) {
            $utilisateurInteretRemoved->setUtilisateur(null);
        }

        $this->collUtilisateurInterets = null;
        foreach ($utilisateurInterets as $utilisateurInteret) {
            $this->addUtilisateurInteret($utilisateurInteret);
        }

        $this->collUtilisateurInterets = $utilisateurInterets;
        $this->collUtilisateurInteretsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UtilisateurInteret objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UtilisateurInteret objects.
     * @throws PropelException
     */
    public function countUtilisateurInterets(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUtilisateurInteretsPartial && !$this->isNew();
        if (null === $this->collUtilisateurInterets || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUtilisateurInterets) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUtilisateurInterets());
            }
            $query = UtilisateurInteretQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUtilisateur($this)
                ->count($con);
        }

        return count($this->collUtilisateurInterets);
    }

    /**
     * Method called to associate a UtilisateurInteret object to this object
     * through the UtilisateurInteret foreign key attribute.
     *
     * @param    UtilisateurInteret $l UtilisateurInteret
     * @return Utilisateur The current object (for fluent API support)
     */
    public function addUtilisateurInteret(UtilisateurInteret $l)
    {
        if ($this->collUtilisateurInterets === null) {
            $this->initUtilisateurInterets();
            $this->collUtilisateurInteretsPartial = true;
        }

        if (!in_array($l, $this->collUtilisateurInterets->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUtilisateurInteret($l);

            if ($this->utilisateurInteretsScheduledForDeletion and $this->utilisateurInteretsScheduledForDeletion->contains($l)) {
                $this->utilisateurInteretsScheduledForDeletion->remove($this->utilisateurInteretsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UtilisateurInteret $utilisateurInteret The utilisateurInteret object to add.
     */
    protected function doAddUtilisateurInteret($utilisateurInteret)
    {
        $this->collUtilisateurInterets[]= $utilisateurInteret;
        $utilisateurInteret->setUtilisateur($this);
    }

    /**
     * @param	UtilisateurInteret $utilisateurInteret The utilisateurInteret object to remove.
     * @return Utilisateur The current object (for fluent API support)
     */
    public function removeUtilisateurInteret($utilisateurInteret)
    {
        if ($this->getUtilisateurInterets()->contains($utilisateurInteret)) {
            $this->collUtilisateurInterets->remove($this->collUtilisateurInterets->search($utilisateurInteret));
            if (null === $this->utilisateurInteretsScheduledForDeletion) {
                $this->utilisateurInteretsScheduledForDeletion = clone $this->collUtilisateurInterets;
                $this->utilisateurInteretsScheduledForDeletion->clear();
            }
            $this->utilisateurInteretsScheduledForDeletion[]= clone $utilisateurInteret;
            $utilisateurInteret->setUtilisateur(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Utilisateur is new, it will return
     * an empty collection; or if this Utilisateur has previously
     * been saved, it will retrieve related UtilisateurInterets from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Utilisateur.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UtilisateurInteret[] List of UtilisateurInteret objects
     */
    public function getUtilisateurInteretsJoinInteret($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UtilisateurInteretQuery::create(null, $criteria);
        $query->joinWith('Interet', $join_behavior);

        return $this->getUtilisateurInterets($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->nom = null;
        $this->prenom = null;
        $this->mail = null;
        $this->age = null;
        $this->ville = null;
        $this->description = null;
        $this->ip_utilisateur = null;
        $this->ip_id = null;
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
            if ($this->collUtilisateurProduits) {
                foreach ($this->collUtilisateurProduits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId) {
                foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId) {
                foreach ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRequetes) {
                foreach ($this->collRequetes as $o) {
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
            if ($this->collUtilisateurInterets) {
                foreach ($this->collUtilisateurInterets as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aIp instanceof Persistent) {
              $this->aIp->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collUtilisateurProduits instanceof PropelCollection) {
            $this->collUtilisateurProduits->clearIterator();
        }
        $this->collUtilisateurProduits = null;
        if ($this->collProfilScoreUtilisateursRelatedByUtilisateurAId instanceof PropelCollection) {
            $this->collProfilScoreUtilisateursRelatedByUtilisateurAId->clearIterator();
        }
        $this->collProfilScoreUtilisateursRelatedByUtilisateurAId = null;
        if ($this->collProfilScoreUtilisateursRelatedByUtilisateurBId instanceof PropelCollection) {
            $this->collProfilScoreUtilisateursRelatedByUtilisateurBId->clearIterator();
        }
        $this->collProfilScoreUtilisateursRelatedByUtilisateurBId = null;
        if ($this->collRequetes instanceof PropelCollection) {
            $this->collRequetes->clearIterator();
        }
        $this->collRequetes = null;
        if ($this->collProfilScoreUtilisateurProduits instanceof PropelCollection) {
            $this->collProfilScoreUtilisateurProduits->clearIterator();
        }
        $this->collProfilScoreUtilisateurProduits = null;
        if ($this->collProfilScoreRequeteUtilisateurProduits instanceof PropelCollection) {
            $this->collProfilScoreRequeteUtilisateurProduits->clearIterator();
        }
        $this->collProfilScoreRequeteUtilisateurProduits = null;
        if ($this->collUtilisateurInterets instanceof PropelCollection) {
            $this->collUtilisateurInterets->clearIterator();
        }
        $this->collUtilisateurInterets = null;
        $this->aIp = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'nom' column
     */
    public function __toString()
    {
        return (string) $this->getNom();
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
