<?php
/**
 * Default implementation of an entity manager to hide the classes that do the real work.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence
 * @version     $Id: stubDefaultEntityManager.php 1901 2008-10-24 14:10:24Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::persistence::stubEntityManager'
);
/**
 * Default implementation of an entity manager to hide the classes that do the real work.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence
 */
// @codeCoverageIgnoreStart
class stubDefaultEntityManager extends stubBaseObject implements stubEntityManager
{
    /**
     * connection instance to use
     *
     * @var  stubDatabaseConnection
     */
    protected $connection;

    /**
     * constructor
     *
     * @param  stubDatabaseConnection  $connection
     * @Inject()
     */
    public function __construct(stubDatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * get an entity from database by its primary keys
     *
     * @param   stubBaseReflectionClass      $entityClass  class information about the entity
     * @param   array                        $primaryKeys  list of primary keys (name => value)
     * @return  object
     * @throws  stubDatabaseFinderException
     * @throws  stubPersistenceException
     */
    public function findByPrimaryKey(stubBaseReflectionClass $entityClass, array $primaryKeys)
    {
        return stubDatabaseFinder::getInstance($this->connection)
                                 ->findByPrimaryKeys($entityClass, $primaryKeys);
    }

    /**
     * finds all instances of $entityClass by given criterion
     *
     * @param   stubCriterion                $criterion
     * @param   string                       $entityClass  entity class to find instances of
     * @param   string                       $orderBy      optional  overrule default order of entity
     * @param   int                          $offset       optional  overrule to start selection at given offset
     * @param   int                          $amount       optional  overrule to limit selection to given amount
     * @return  stubDatabaseFinderResult     list of instances of $entityClass found with $criterion
     * @throws  stubDatabaseFinderException
     * @throws  stubPersistenceException
     */
    public function findByCriterion(stubCriterion $criterion, stubBaseReflectionClass $entityClass, $orderBy = null, $offset = null, $amount = null)
    {
        return stubDatabaseFinder::getInstance($this->connection)
                                 ->findByCriterion($criterion, $entityClass, $orderBy, $offset, $amount);
    }

    /**
     * finds all instances of $entityClass
     *
     * @param   string                       $entityClass  entity class to find instances of
     * @param   string                       $orderBy      optional  overrule default order of entity
     * @param   int                          $offset       optional  overrule to start selection at given offset
     * @param   int                          $amount       optional  overrule to limit selection to given amount
     * @return  stubDatabaseFinderResult     list of instances of $entityClass found
     * @throws  stubDatabaseFinderException
     * @throws  stubPersistenceException
     */
    public function findAll(stubBaseReflectionClass $entityClass, $orderBy = null, $offset = null, $amount = null)
    {
        return stubDatabaseFinder::getInstance($this->connection)
                                 ->findAll($entityClass, $orderBy, $offset, $amount);
    }

    /**
     * takes an entity and inserts it into the database
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function insert($entity)
    {
        return stubDatabaseSerializer::getInstance($this->connection)
                                     ->insert($entity);
    }

    /**
     * takes an entity and updates the database entry
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function update($entity)
    {
        return stubDatabaseSerializer::getInstance($this->connection)
                                     ->update($entity);
    }

    /**
     * takes an entity and serializes it into the database
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function serialize($entity)
    {
        return stubDatabaseSerializer::getInstance($this->connection)
                                     ->serialize($entity);
    }

    /**
     * delete an entity from database by its primary keys
     *
     * @param   object                       $entity
     * @throws  stubDatabaseEraserException
     * @throws  stubPersistenceException
     */
    public function deleteByPrimaryKeys($entity)
    {
        stubDatabaseEraser::getInstance($this->connection)
                          ->deleteByPrimaryKeys($entity);
    }

    /**
     * deletes all instances of an entity by given criterion
     *
     * @param   stubCriterion                $criterion    the criterion that denotes all instances to delete
     * @param   stubBaseReflectionClass      $entityClass
     * @return  int                          amount of erased instances
     * @throws  stubDatabaseEraserException
     */
    public function deleteByCriterion(stubCriterion $criterion, stubBaseReflectionClass $entityClass)
    {
        return stubDatabaseEraser::getInstance($this->connection)
                                 ->deleteByCriterion($criterion, $entityClass);
    }
}
// @codeCoverageIgnoreEnd
?>