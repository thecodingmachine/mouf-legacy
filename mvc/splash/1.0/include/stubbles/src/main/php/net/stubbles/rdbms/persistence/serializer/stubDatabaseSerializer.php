<?php
/**
 * Serializer to store objects in database tables.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_serializer
 * @version     $Id: stubDatabaseSerializer.php 1926 2008-11-10 23:50:23Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::types::stubDate',
                      'net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::criteria::stubEqualCriterion',
                      'net::stubbles::rdbms::persistence::stubPersistenceHelper',
                      'net::stubbles::rdbms::persistence::stubSetterMethodHelper',
                      'net::stubbles::rdbms::persistence::serializer::stubDatabaseSerializerException',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseTableRow'
);
/**
 * Serializer to store objects in database tables.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_serializer
 */
class stubDatabaseSerializer extends stubPersistenceHelper
{
    /**
     * list of serializer instances
     *
     * @var  array<string,stubDatabaseSerializer>
     */
    protected static $instances = array();
    /**
     * the connection to use for making the object persistent
     *
     * @var  stubDatabaseConnection
     */
    protected $connection;
    /**
     * data has been inserted
     */
    const INSERT                = 'insert';
    /**
     * data has been updated
     */
    const UPDATE                = 'update';

    /**
     * constructor
     *
     * @param  stubDatabaseConnection    $connection
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
     * @return  stubDatabaseSerializer
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
     * @throws  stubDatabaseSerializerException
     */
    protected final function __clone()
    {
        throw new stubDatabaseSerializerException('Cloning ' . $this->getClassName() . ' is not allowed.');
    }

    /**
     * takes an entity and inserts it into the database
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubIllegalArgumentException
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function insert($entity)
    {
        if (is_object($entity) === false) {
            throw new stubIllegalArgumentException('Can only serialize objects.');
        }
        
        $entityClass = (($entity instanceof stubObject) ? ($entity->getClass()) : (new stubReflectionObject($entity)));
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $stuff = $this->processEntity($entityClass, $entity, self::INSERT);
        try {
            $this->processInsertQueries($this->getInsertQuery($stuff['tableRow'], $entity, $stuff['defaultValues']), $entity, array_shift($stuff['primaryKeys']));
        } catch (stubDatabaseException $dbe) {
            throw new stubDatabaseSerializerException('Can not persist ' . $entityClass->getFullQualifiedClassName() . ': a database error occured.', $dbe);
        }
        
        return self::INSERT;
    }

    /**
     * takes an entity and updates its database entry
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubIllegalArgumentException
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function update($entity)
    {
        if (is_object($entity) === false) {
            throw new stubIllegalArgumentException('Can only serialize objects.');
        }
        
        $entityClass = (($entity instanceof stubObject) ? ($entity->getClass()) : (new stubReflectionObject($entity)));
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $stuff = $this->processEntity($entityClass, $entity, self::UPDATE);
        try {
            $this->processUpdateQueries($this->getUpdateQuery($stuff['tableRow'], $stuff['defaultValues']));
        } catch (stubDatabaseException $dbe) {
            throw new stubDatabaseSerializerException('Can not persist ' . $entityClass->getFullQualifiedClassName() . ': a database error occured.', $dbe);
        }
            
        return self::UPDATE;
    }

    /**
     * takes an entity and serializes it into the database
     *
     * @param   object                           $entity
     * @return  string
     * @throws  stubIllegalArgumentException
     * @throws  stubDatabaseSerializerException
     * @throws  stubPersistenceException
     */
    public function serialize($entity)
    {
        if (is_object($entity) === false) {
            throw new stubIllegalArgumentException('Can only serialize objects.');
        }
        
        $entityClass = (($entity instanceof stubObject) ? ($entity->getClass()) : (new stubReflectionObject($entity)));
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $stuff = $this->processEntity($entityClass, $entity);
        if (count($stuff['primaryKeys']) > 1) {
            throw new stubDatabaseSerializerException('Persistence error for ' . $entityClass->getFullQualifiedClassName() . ': only one primary key can be null, but at least two primary keys are null: ' . join(', ', array_keys($stuff['primaryKeys'])));
        }
        
        if ($stuff['tableRow']->hasCriterion() === true) {
            try {
                $this->processUpdateQueries($this->getUpdateQuery($stuff['tableRow'], $stuff['defaultValues']));
            } catch (stubDatabaseException $dbe) {
                throw new stubDatabaseSerializerException('Can not persist ' . $entityClass->getFullQualifiedClassName() . ': a database error occured.', $dbe);
            }
            
            return self::UPDATE;
        }
        
        try {
            $this->processInsertQueries($this->getInsertQuery($stuff['tableRow'], $entity, $stuff['defaultValues']), $entity, array_shift($stuff['primaryKeys']));
        } catch (stubDatabaseException $dbe) {
            throw new stubDatabaseSerializerException('Can not persist ' . $entityClass->getFullQualifiedClassName() . ': a database error occured.', $dbe);
        }
        
        return self::INSERT;
    }

    /**
     * processes the entity: create another presentation of data
     *
     * @param   stubBaseReflectionClass  $entityClass
     * @param   object                   $entity
     * @param   string                   $type         optional
     * @return  array
     * @throws  stubDatabaseSerializerException
     */
    protected function processEntity(stubBaseReflectionClass $entityClass, $entity, $type = null)
    {
        $tableRow      = new stubDatabaseTableRow($this->getTableDescription($entityClass)->getName());
        $primaryKeys   = array();
        $defaultValues = array();
        foreach ($this->getColumns($entityClass) as $method => $column) {
            $value = $entity->$method();
            if ($value instanceof stubDate) {
                $value = $value->format('Y-m-d H:i:s');
            }
            
            if ($column->isPrimaryKey() === true) {
                if (null === $value && self::UPDATE === $type) {
                    throw new stubDatabaseSerializerException('Persistence error for ' . $entityClass->getFullQualifiedClassName() . ': should be updated, but one primary key column is null: ' . $method);
                } elseif (null === $value) {
                    $primaryKeys[$method] = array('setterMethod' => stubSetterMethodHelper::getSetterMethodName($column, $entityClass->getName(), $method),
                                                  'tableName'    => $tableRow->getTableName()
                                            );
                } elseif (self::INSERT === $type) {
                    $tableRow->setColumn($column->getName(), $value);
                } else {
                    $tableRow->addCriterion(new stubEqualCriterion($column->getName(), $value, $tableRow->getTableName()));
                }
            } elseif (null === $value) {
                $defaultValue = $column->getDefaultValue();
                if ($column->isNullable() === false && null === $defaultValue) {
                    throw new stubDatabaseSerializerException('Persistence error for ' . $entityClass->getFullQualifiedClassName() . ': column ' . $column->getName() . ' is not allowed to be null but return value from method ' . $method . ' and default value are both null.');
                }
                
                $defaultValues[] = array('setterMethod' => stubSetterMethodHelper::getSetterMethodName($column, $entityClass->getName(), $method),
                                         'value'        => $value,
                                         'defaultValue' => $defaultValue,
                                         'column'       => $column->getName()
                                   );
            } else {
                $tableRow->setColumn($column->getName(), $value);
            }
        }
        
        return array('tableRow'      => $tableRow,
                     'defaultValues' => $defaultValues,
                     'primaryKeys'   => $primaryKeys
               );
    }

    /**
     * creates the queries required to process the insert
     *
     * @param   stubDatabaseTableRow  $tableRow
     * @param   object                $entity
     * @param   array                 $defaultValues
     * @return  array<string>
     * @throws  stubDatabaseSerializerException
     */
    protected function getInsertQuery(stubDatabaseTableRow $tableRow, $entity, array $defaultValues)
    {
        $queryBuilder = stubDatabaseQueryBuilderFactory::create($this->connection);
        try {
            // fill default values into entity and table row
            foreach ($defaultValues as $defaultValue) {
                // only reset entity with default value if default value is not null
                if (null !== $defaultValue['defaultValue']) {
                    $setterMethodName = $defaultValue['setterMethod'];
                    $entity->$setterMethodName($defaultValue['defaultValue']);
                }
                
                $tableRow->setColumn($defaultValue['column'], $defaultValue['defaultValue']);
            }

            return $queryBuilder->createInsert(array($tableRow->getTableName() => $tableRow));
        } catch (stubIllegalArgumentException $iae) {
            throw new stubDatabaseSerializerException('Creating the queries failed.', $iae);
        }
    }

    /**
     * creates the queries required to process the update
     *
     * @param   stubDatabaseTableRow  $tableRow
     * @param   array                 $defaultValues
     * @return  array<string>
     * @throws  stubDatabaseSerializerException
     */
    protected function getUpdateQuery(stubDatabaseTableRow $tableRow, array $defaultValues)
    {
        $queryBuilder = stubDatabaseQueryBuilderFactory::create($this->connection);
        try {
            foreach ($defaultValues as $defaultValue) {
                $tableRow->setColumn($defaultValue['column'], $defaultValue['value']);
            }
            
            return $queryBuilder->createUpdate(array($tableRow->getTableName() => $tableRow));
        } catch (stubIllegalArgumentException $iae) {
            throw new stubDatabaseSerializerException('Creating the queries failed.', $iae);
        }
    }

    /**
     * process insert queries
     *
     * @param   array<string,string>   $queries           list of queries to process
     * @param   object                 $entity            the entity to process the queries for
     * @param   array<string,string>   $singlePrimaryKey  optional  information about the single primary key
     * @throws  stubDatabaseException
     */
    protected function processInsertQueries(array $queries, $entity, array $singlePrimaryKey = null)
    {
        foreach ($queries as $tableName => $query) {
            $this->connection->exec($query);
            if (null !== $singlePrimaryKey && $singlePrimaryKey['tableName'] == $tableName) {
                $setterMethodName = $singlePrimaryKey['setterMethod'];
                $entity->$setterMethodName($this->connection->getLastInsertId());
            }
        }
    }

    /**
     * process update queries
     *
     * @param   array<string,string>   $queries           list of queries to process
     * @throws  stubDatabaseException
     */
    protected function processUpdateQueries(array $queries)
    {
        foreach ($queries as $tableName => $query) {
            $this->connection->exec($query);
        }
    }

    /**
     * returns a unique hash code for the class
     * 
     * Two serializers are equal if they use the same connection.
     *
     * @return  string
     */
    public function hashCode()
    {
        return 'serializer:' . $this->connection->hashCode();
    }
}
?>