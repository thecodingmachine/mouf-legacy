<?php
/**
 * Class for finding the data of an entity object within a database.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_finder
 * @version     $Id: stubDatabaseFinder.php 1926 2008-11-10 23:50:23Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::criteria::stubCriterion',
                      'net::stubbles::rdbms::criteria::stubEqualCriterion',
                      'net::stubbles::rdbms::persistence::stubPersistenceHelper',
                      'net::stubbles::rdbms::persistence::stubSetterMethodHelper',
                      'net::stubbles::rdbms::persistence::finder::stubDatabaseFinderException',
                      'net::stubbles::rdbms::persistence::finder::stubDatabaseFinderResult',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseSelect'
);
/**
 * Class for finding the data of an entity object within a database.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_finder
 */
class stubDatabaseFinder extends stubPersistenceHelper
{
    /**
     * list of finder instances
     *
     * @var  array<string,stubDatabaseFinder>
     */
    protected static $instances = array();
    /**
     * the connection to use for finding the data
     *
     * @var  stubDatabaseConnection
     */
    protected $connection;

    /**
     * constructor
     *
     * @param  stubDatabaseConnection  $connection  connection to use for finding the data
     */
    protected final function __construct(stubDatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * method to return instances of the finder depending of the connection
     * 
     * Because the finder itself is stateless and only bound to the connection
     * this factory methods prevents that a finder for a specific connection is
     * created more than once.
     *
     * @param   stubDatabaseConnection  $connection  connection to use for finding the data
     * @param   bool                    $refresh     optional  set to true to recreate the instance
     * @return  stubDatabaseFinder
     */
    public static function getInstance(stubDatabaseConnection $connection, $refresh = false)
    {
        if (isset(self::$instances[$connection->hashCode()]) === false || true === $refresh) {
            self::$instances[$connection->hashCode()] = new self($connection);
        }
        
        return self::$instances[$connection->hashCode()];
    }

    /**
     * cloning is forbidden
     *
     * @throws  stubDatabaseFinderException
     */
    protected final function __clone()
    {
        throw new stubDatabaseFinderException('Cloning ' . $this->getClassName() . ' is not allowed.');
    }

    /**
     * get an entity from database by its primary keys
     *
     * @param   stubBaseReflectionClass   $entityClass  class information about the entity
     * @param   array                     $primaryKeys  list of primary keys (name => value)
     * @return  object
     * @throws  stubPersistenceException
     */
    public function findByPrimaryKeys(stubBaseReflectionClass $entityClass, array $primaryKeys)
    {
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $setterMethodHelper = new stubSetterMethodHelper($entityClass->getName());
        $data               = $this->fetchData($entityClass, $setterMethodHelper, null, $primaryKeys);
        if (count($data) === 0) {
            return null;
        }
        
        $entity = $entityClass->newInstance();
        $setterMethodHelper->applySetterMethods($entity, $data);
        return $entity;
    }

    /**
     * finds all instances of $entityClass by given criterion
     *
     * @param   stubCriterion             $criterion
     * @param   string                    $entityClass  entity class to find instances of
     * @param   string                    $orderBy      optional  overrule default order of entity
     * @param   int                       $offset       optional  overrule to start selection at given offset
     * @param   int                       $amount       optional  overrule to limit selection to given amount
     * @return  stubDatabaseFinderResult  list of instances of $entityClass found with $criterion
     * @throws  stubPersistenceException
     */
    public function findByCriterion(stubCriterion $criterion, stubBaseReflectionClass $entityClass, $orderBy = null, $offset = null, $amount = null)
    {
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $setterMethodHelper = new stubSetterMethodHelper($entityClass->getName());
        $data               = $this->fetchData($entityClass, $setterMethodHelper, $criterion, null, $orderBy, $offset, $amount);
        $finderResult       = new stubDatabaseFinderResult($entityClass, $data, $setterMethodHelper);
        return $finderResult;
    }

    /**
     * finds all instances of $entityClass
     *
     * @param   string                    $entityClass  entity class to find instances of
     * @param   string                    $orderBy      optional  overrule default order of entity
     * @param   int                       $offset       optional  overrule to start selection at given offset
     * @param   int                       $amount       optional  overrule to limit selection to given amount
     * @return  stubDatabaseFinderResult  list of instances of $entityClass found
     * @throws  stubPersistenceException
     */
    public function findAll(stubBaseReflectionClass $entityClass, $orderBy = null, $offset = null, $amount = null)
    {
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $setterMethodHelper = new stubSetterMethodHelper($entityClass->getName());
        $data               = $this->fetchData($entityClass, $setterMethodHelper, null, null, $orderBy, $offset, $amount);
        $finderResult       = new stubDatabaseFinderResult($entityClass, $data, $setterMethodHelper);
        return $finderResult;
    }

    /**
     * helper method to retrieve the data for a given select instance
     *
     * @param   stubBaseReflectionClass  $entityClass
     * @param   stubSetterMethodHelper   $setterMethodHelper
     * @param   stubCriterion            $criterion           optional
     * @param   array                    $primaryKeys         optional
     * @param   string                   $orderBy             optional  overrule default order of entity
     * @param   int                      $offset              optional  overrule to start selection at given offset
     * @param   int                      $amount              optional  overrule to limit selection to given amount
     * @return  array
     * @throws  stubDatabaseFinderException
     */
    protected function fetchData(stubBaseReflectionClass $entityClass, stubSetterMethodHelper $setterMethodHelper, stubCriterion $criterion = null, array $primaryKeys = null, $orderBy = null, $offset = null, $amount = null)
    {
        $select = $this->createSelect($entityClass, $setterMethodHelper, ((null === $primaryKeys) ? (array()) : ($primaryKeys)));
        if (null !== $criterion) {
            $select->addCriterion($criterion);
        }
        
        if (null !== $orderBy) {
            $select->orderBy($orderBy);
        } else {
            $entityAnnotation = $entityClass->getAnnotation('Entity');
            if ($entityAnnotation->hasDefaultOrder() === true) {
                $select->orderBy($entityAnnotation->getDefaultOrder());
            }
        }
        
        $select->limitBy($offset, $amount);
        
        try {
            $result = $this->connection->query(stubDatabaseQueryBuilderFactory::create($this->connection)->createSelect($select));
            if (null !== $primaryKeys) {
                $data = $result->fetch();
            } else {
                $data = $result->fetchAll();
            }
            
            $result->free();
        } catch (stubDatabaseException $se) {
            $exceptionInfo = null;
            if (null !== $criterion) {
                $exceptionInfo = ' by criterion ' . $criterion;
            } elseif (null !== $primaryKeys) {
                $exceptionInfo = ' by its primary keys.';
            }
            
            throw new stubDatabaseFinderException('Can not find any instance of ' . $entityClass->getFullQualifiedClassName() . $exceptionInfo, $se);
        }
        
        if (false === $data) {
            return array();
        }
        
        return $data;
    }

    /**
     * reads annotations and returns data in a usable format
     *
     * @param   stubBaseReflectionClass      $entityClass
     * @param   stubSetterMethodHelper       $setterMethodHelper
     * @param   array                        $primaryKeys         optional
     * @return  stubDatabaseSelect
     * @throws  stubDatabaseFinderException
     */
    protected function createSelect(stubBaseReflectionClass $entityClass, stubSetterMethodHelper $setterMethodHelper, array $primaryKeys = array())
    {
        $select  = new stubDatabaseSelect($this->getTableDescription($entityClass));
        foreach ($this->getColumns($entityClass) as $method => $column) {
            if ($column->isPrimaryKey() === true && isset($primaryKeys[$this->getPropertyName($method)]) === true) {
                $select->addCriterion(new stubEqualCriterion($column->getName(), $primaryKeys[$this->getPropertyName($method)], $select->getBaseTableName()));
            } elseif ($column->isPrimaryKey() === true && isset($primaryKeys[$column->getName()]) === true) {
                $select->addCriterion(new stubEqualCriterion($column->getName(), $primaryKeys[$column->getName()], $select->getBaseTableName()));
            }
            
            $setterMethodHelper->addSetterMethod($column, $method);
        }
        
        return $select;
    }

    /**
     * returns a unique hash code for the class
     * 
     * Two finders are equal if they use the same connection.
     *
     * @return  string
     */
    public function hashCode()
    {
        return 'finder:' . $this->connection->hashCode();
    }
}
?>