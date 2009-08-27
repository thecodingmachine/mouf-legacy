<?php
/**
 * Interface for an entity manager to hide the classes that do the real work.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence
 * @version     $Id: stubEntityManager.php 1901 2008-10-24 14:10:24Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::criteria::stubCriterion',
                      'net::stubbles::rdbms::persistence::finder::stubDatabaseFinder',
                      'net::stubbles::rdbms::persistence::eraser::stubDatabaseEraser',
                      'net::stubbles::rdbms::persistence::serializer::stubDatabaseSerializer',
                      'net::stubbles::reflection::stubBaseReflectionClass'
);
/**
 * Interface for an entity manager to hide the classes that do the real work.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence
 * @ImplementedBy(net::stubbles::rdbms::persistence::stubDefaultEntityManager.class)
 */
interface stubEntityManager extends stubObject
{
    /**
     * get an entity from database by its primary keys
     *
     * @param   stubBaseReflectionClass      $entityClass  class information about the entity
     * @param   array                        $primaryKeys  list of primary keys (name => value)
     * @return  object
     * @throws  stubDatabaseFinderException
     * @throws  stubPersistenceException
     */
    public function findByPrimaryKey(stubBaseReflectionClass $entityClass, array $primaryKeys);

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
    public function findByCriterion(stubCriterion $criterion, stubBaseReflectionClass $entityClass, $orderBy = null, $offset = null, $amount = null);

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
    public function findAll(stubBaseReflectionClass $entityClass, $orderBy = null, $offset = null, $amount = null);

    /**
     * takes an entity and inserts it into the database
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function insert($entity);

    /**
     * takes an entity and updates the database entry
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function update($entity);

    /**
     * takes an entity and serializes it into the database
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function serialize($entity);

    /**
     * delete an entity from database by its primary keys
     *
     * @param   object                       $entity
     * @throws  stubDatabaseEraserException
     * @throws  stubPersistenceException
     */
    public function deleteByPrimaryKeys($entity);

    /**
     * deletes all instances of an entity by given criterion
     *
     * @param   stubCriterion                $criterion    the criterion that denotes all instances to delete
     * @param   stubBaseReflectionClass      $entityClass
     * @return  int                          amount of erased instances
     * @throws  stubDatabaseEraserException
     */
    public function deleteByCriterion(stubCriterion $criterion, stubBaseReflectionClass $entityClass);
}
?>