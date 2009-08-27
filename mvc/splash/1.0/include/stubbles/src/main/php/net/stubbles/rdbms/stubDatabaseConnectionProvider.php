<?php
/**
 * IOC provider for database connections.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms
 */
stubClassLoader::load('net::stubbles::ioc::stubInjectionProvider',
                      'net::stubbles::rdbms::stubDatabaseConnectionData',
                      'net::stubbles::rdbms::stubDatabaseConnectionPool',
                      'net::stubbles::rdbms::stubDatabaseException'
);
/**
 * IOC provider for database connections.
 *
 * @package     stubbles
 * @subpackage  rdbms
 */
class stubDatabaseConnectionProvider extends stubBaseObject implements stubInjectionProvider
{
    /**
     * switch whether to fallback to default connection if no named connection exists
     *
     * @var  bool
     */
    protected $fallback = true;

    /**
     * constructor
     *
     * @param  bool  $fallback  whether to fallback to default connection if no named connection exists
     */
    public function __construct($fallback = true)
    {
        $this->fallback = $fallback;
    }

    /**
     * returns the connection to be injected
     *
     * If a name is provided and a condition with this name exists this
     * connection will be returned. If fallback is enabled and the named
     * connection does not exist the default connection will be returned, if
     * fallback is disabled a stubDatabaseException will be thrown.
     *
     * If no name is provided the default connection will be returned.
     *
     * @param   string                  $type
     * @param   string                  $name  optional
     * @return  stubDatabaseConnection
     * @throws  stubDatabaseException
     */
    public function get($type, $name = null)
    {
        if (null !== $name) {
            if (stubDatabaseConnectionPool::hasConnectionData($name) === true) {
                return stubDatabaseConnectionPool::getConnection($name);
            }
            
            if (false === $this->fallback) {
                throw new stubDatabaseException('No connection and no dsn known for connection associated with id ' . $name);
            }
        }
        
        return stubDatabaseConnectionPool::getConnection(stubDatabaseConnectionData::DEFAULT_ID);
    }
}
?>