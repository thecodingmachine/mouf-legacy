<?php
/**
 * Factory to create database specific query builders.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_querybuilder
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilder',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderException'
);
/**
 * Factory to create database specific query builders.
 *
 * @static
 * @package     stubbles
 * @subpackage  rdbms_querybuilder
 */
class stubDatabaseQueryBuilderFactory
{
    /**
     * list of created query builders
     *
     * @var  array<string,stubDatabaseQueryBuilder>
     */
    protected static $instantiatedQueryBuilders = array();
    /**
     * list of available query builders
     *
     * @var  array<string,string>
     */
    protected static $availableQueryBuilders    = array('mysql' => 'net::stubbles::rdbms::querybuilder::stubDatabaseMySQLQueryBuilder');

    /**
     * creates a query builder for the given connection
     *
     * @param   stubDatabaseConnection    $connection
     * @return  stubDatabaseQueryBuilder
     * @throws  stubDatabaseQueryBuilderException
     */
    public static function create(stubDatabaseConnection $connection)
    {
        $database = strtolower($connection->getDatabase());
        if (isset(self::$instantiatedQueryBuilders[$database]) == false) {
            self::createInstance($database);
        }
        
        return self::$instantiatedQueryBuilders[$database];
    }

    /**
     * checks whether a query builder for the specified database type is available
     *
     * @param   stzring  $database  type of the database the querybuilder is registered for
     * @return  bool
     */
    public static function isQueryBuilderAvailable($database)
    {
        return isset(self::$availableQueryBuilders[strtolower($database)]);
    }

    /**
     * set the query builder to use for the given database type
     *
     * @param  string  $database     type of the database (e.g. MySQL, PostgrSQL)
     * @param  string  $fqClassName  full qualified classname of the querybuilder
     */
    public static function setAvailableQueryBuilder($database, $fqClassName)
    {
        self::$availableQueryBuilders[strtolower($database)] = $fqClassName;
    }

    /**
     * removes a query builder from the list of available query builders
     *
     * @param  string  $database  type of the database the querybuilder to remove is registered for
     */
    public static function removeAvailableQueryBuilder($database)
    {
        if (isset(self::$availableQueryBuilders[strtolower($database)]) == true) {
            unset(self::$availableQueryBuilders[strtolower($database)]);
        }
    }

    /**
     * checks whether an instance of the query builder for the specified database type exists
     *
     * @param   string  $database  type of the database the querybuilder is registered for
     * @return  bool
     */
    public static function isQueryBuilderInstantiated($database)
    {
        return isset(self::$instantiatedQueryBuilders[strtolower($database)]);
    }

    /**
     * setsan instantiated query builder to use for the given database type
     *
     * @param  string                    $database      type of the database (e.g. MySQL, PostgrSQL)
     * @param  stubDatabaseQueryBuilder  $queryBuilder  the querybuilder to use
     */
    public static function setInstantiatedQueryBuilder($database, stubDatabaseQueryBuilder $queryBuilder)
    {
        self::$instantiatedQueryBuilders[strtolower($database)] = $queryBuilder;
        self::$availableQueryBuilders[strtolower($database)]    = $queryBuilder->getClassName();
    }

    /**
     * removes a query builder from the list of instantiated query builders
     *
     * @param  string  $database  type of the database the querybuilder to remove is registered for
     */
    public static function removeInstantiatedQueryBuilder($database)
    {
        if (isset(self::$instantiatedQueryBuilders[strtolower($database)]) == true) {
            unset(self::$instantiatedQueryBuilders[strtolower($database)]);
            unset(self::$availableQueryBuilders[strtolower($database)]);
        }
    }

    /**
     * creates the instance
     *
     * @param   string  $database  type of the database (MySQL, PostgreSQL, etc.)
     * @throws  stubDatabaseQueryBuilderException
     */
    protected static function createInstance($database)
    {
        if (isset(self::$availableQueryBuilders[$database]) == false) {
            throw new stubDatabaseQueryBuilderException('Could not find QueryBuilder for database ' . $database);
        }
        
        $queryBuilderClassName = stubClassLoader::getNonQualifiedClassName(self::$availableQueryBuilders[$database]);;
        if (class_exists($queryBuilderClassName, false) == false) {
            stubClassLoader::load(self::$availableQueryBuilders[$database]);
        }
        
        $queryBuilder = new $queryBuilderClassName();
        if (($queryBuilder instanceof stubDatabaseQueryBuilder) == false) {
            throw new stubDatabaseQueryBuilderException('Configured QueryBuilder for database ' . $database . ' is not an instance of net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilder.');
        }
        
        self::$instantiatedQueryBuilders[$database] = $queryBuilder;
    }
}
?>