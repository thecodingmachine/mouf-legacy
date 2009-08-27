<?php
/**
 * Class for creating the table description for an entity.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_creator
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::persistence::stubPersistenceHelper',
                      'net::stubbles::rdbms::persistence::creator::stubDatabaseCreatorException',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory'
);
/**
 * Class for creating the table description for an entity.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_creator
 */
class stubDatabaseCreator extends stubPersistenceHelper
{
    /**
     * list of creator instances
     *
     * @var  array<string,stubDatabaseCreator>
     */
    protected static $instances = array();
    /**
     * the connection to use for creating the tables
     *
     * @var  stubDatabaseConnection
     */
    protected $connection;

    /**
     * constructor
     *
     * @param  stubDatabaseConnection  $connection  connection to use for creating the tables
     */
    protected final function __construct(stubDatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * method to return instances of the creator depending of the connection
     * 
     * Because the creator itself is stateless and only bound to the connection
     * this factory methods prevents that a creator for a specific connection is
     * created more than once.
     *
     * @param   stubDatabaseConnection  $connection  connection to use for creating the tables
     * @param   bool                    $refresh     optional  set to true to recreate the instance
     * @return  stubDatabaseCreator
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
     * @throws  stubDatabaseCreatorException
     */
    protected final function __clone()
    {
        throw new stubDatabaseCreatorException('Cloning ' . $this->getClassName() . ' is not allowed.');
    }

    /**
     * creates the table description from the given entity class
     *
     * @param   stubBaseReflectionClass       $entityClass
     * @throws  stubDatabaseCreatorException
     * @throws  stubPersistenceException
     */
    public function createTable(stubBaseReflectionClass $entityClass)
    {
        if ($entityClass->hasAnnotation('Entity') === false) {
            throw new stubPersistenceException('Class ' . $entityClass->getFullQualifiedClassName() . ' is not an entity.');
        }
        
        $tableDescription = $this->getTableDescription($entityClass);
        foreach ($this->getColumns($entityClass) as $column) {
            $tableDescription->addColumn($column);
        }
        
        try {
            $this->connection->query(stubDatabaseQueryBuilderFactory::create($this->connection)->createTable($tableDescription));
        } catch (stubException $se) {
            throw new stubDatabaseCreatorException('Can not create table for ' . $entityClass->getFullQualifiedClassName(), $se);
        }
    }

    /**
     * returns a unique hash code for the class
     * 
     * Two creators are equal if they use the same connection.
     *
     * @return  string
     */
    public function hashCode()
    {
        return 'creator:' . $this->connection->hashCode();
    }
}
?>