<?php
/**
 * Class for erasing the data of an entity object within a database.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_eraser
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::criteria::stubCriterion',
                      'net::stubbles::rdbms::criteria::stubAndCriterion',
                      'net::stubbles::rdbms::criteria::stubEqualCriterion',
                      'net::stubbles::rdbms::persistence::stubPersistenceHelper',
                      'net::stubbles::rdbms::persistence::eraser::stubDatabaseEraserException',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory'
);
/**
 * Class for erasing the data of an entity object within a database.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_eraser
 */
class stubDatabaseEraser extends stubPersistenceHelper
{
    /**
     * list of eraser instances
     *
     * @var  array<string,stubDatabaseEraser>
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
     * @param  stubDatabaseConnection  $connection  connection to use for erasing the data
     */
    protected final function __construct(stubDatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * method to return instances of the eraser depending of the connection
     * 
     * Because the eraser itself is stateless and only bound to the connection
     * this factory methods prevents that an eraser for a specific connection is
     * created more than once.
     *
     * @param   stubDatabaseConnection  $connection  connection to use for erasing the data
     * @param   bool                    $refresh     optional  set to true to recreate the instance
     * @return  stubDatabaseEraser
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
     * @throws  stubDatabaseEraserException
     */
    protected final function __clone()
    {
        throw new stubDatabaseEraserException('Cloning ' . $this->getClassName() . ' is not allowed.');
    }

    /**
     * delete an entity from database by its primary keys
     *
     * @param   object                       $entity
     * @throws  stubIllegalArgumentException
     * @throws  stubDatabaseEraserException
     * @throws  stubPersistenceException
     */
    public function deleteByPrimaryKeys($entity)
    {
        if (is_object($entity) === false) {
            throw new stubIllegalArgumentException('Can only delete objects.');
        }
        
        $entityClass = (($entity instanceof stubObject) ? ($entity->getClass()) : (new stubReflectionObject($entity)));
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $table     = $this->getTableDescription($entityClass)->getName();
        $criterion = new stubAndCriterion();
        foreach ($this->getColumns($entityClass) as $method => $column) {
            if ($column->isPrimaryKey() === false) {
                continue;
            }
            
            $criterion->addCriterion(new stubEqualCriterion($column->getName(), $entity->$method(), $table));
        }
        
        if ($criterion->hasCriterion() === false) {
            throw new stubDatabaseEraserException('Can not delete instance of ' . $entityClass->getFullQualifiedClassName() . ' by its primary keys as it has no primary key.');
        }
        
        try {
            $result = $this->connection->query(stubDatabaseQueryBuilderFactory::create($this->connection)->createDelete($table, $criterion));
            $result->free();
        } catch (stubException $se) {
            throw new stubDatabaseEraserException('Can not delete instance of ' . $entityClass->getFullQualifiedClassName() . ' by its primary keys.', $se);
        }
    }

    /**
     * deletes all instances of an entity by given criterion
     *
     * @param   stubCriterion                $criterion    the criterion that denotes all instances to delete
     * @param   stubBaseReflectionClass      $entityClass
     * @return  int                          amount of erased instances
     * @throws  stubPersistenceException
     * @throws  stubDatabaseEraserException
     */
    public function deleteByCriterion(stubCriterion $criterion, stubBaseReflectionClass $entityClass)
    {
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $table = $this->getTableDescription($entityClass)->getName();
        try {
            $result      = $this->connection->query(stubDatabaseQueryBuilderFactory::create($this->connection)->createDelete($table, $criterion));
            $deletedRows = $result->count();
            $result->free();
        } catch (stubDatabaseException $se) {
            throw new stubDatabaseEraserException('Can not delete any instance of ' . $entityClass->getFullQualifiedClassName() . ' by criterion ' . $criterion, $se);
        }
        
        return $deletedRows;
    }

    /**
     * returns a unique hash code for the class
     * 
     * Two erasers are equal if they use the same connection.
     *
     * @return  string
     */
    public function hashCode()
    {
        return 'eraser:' . $this->connection->hashCode();
    }
}
?>