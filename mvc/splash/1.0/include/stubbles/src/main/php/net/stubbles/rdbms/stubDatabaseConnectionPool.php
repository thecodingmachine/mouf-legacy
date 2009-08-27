<?php
/**
 * Pool for database connections.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::stubDatabaseConnectionData',
                      'net::stubbles::rdbms::stubDatabaseException'
);
/**
 * Pool for database connections.
 *
 * @static
 * @package     stubbles
 * @subpackage  rdbms
 */
class stubDatabaseConnectionPool
{
    /**
     * a set of database connections
     *
     * @var  array<string,stubDatabaseConnection>
     */
    protected static $connections   = array();
    /**
     * list of known connection data
     * 
     * @var  array<string,stubDatabaseConnectionData>
     */
    protected static $connectionData = array();

    /**
     * returns a connection
     * 
     * If the connection has not been established before it tries to create a
     * new connection if the connection data is known.
     *
     * @param   string                  $id  optional  id of the connection  to retrieve
     * @return  stubDatabaseConnection
     * @throws  stubDatabaseException
     */
    public static function getConnection($id = stubDatabaseConnectionData::DEFAULT_ID)
    {
        if (isset(self::$connections[$id]) == false) {
            if (isset(self::$connectionData[$id]) == false) {
                throw new stubDatabaseException('No connection and no dsn known for connection associated with id ' . $id);
            }
            
            $nqClassName = stubClassLoader::getNonQualifiedClassName(self::$connectionData[$id]->getConnectionClassName());
            if (class_exists($nqClassName, false) == false) {
                stubClassLoader::load(self::$connectionData[$id]->getConnectionClassName());
            }
             
            $connection = new $nqClassName(self::$connectionData[$id]);
            if (($connection instanceof stubDatabaseConnection) == false) {
                throw new stubDatabaseException(self::$connectionData[$id]->getConnectionClassName() . ' is not an instance of net::stubbles::rdbms::stubDatabaseConnection.');
            }
            
            self::$connections[$id] = $connection;
        }
        
        return self::$connections[$id];
    }

    /**
     * adds connection data to the list of known connection data
     *
     * @param  stubDatabaseConnectionData  $connectionData
     */
    public static function addConnectionData(stubDatabaseConnectionData $connectionData)
    {
        self::$connectionData[$connectionData->getId()] = $connectionData;
    }

    /**
     * checks whether connection data for a given id is available
     *
     * @param   string  $id  optional  id of the connection data
     * @return  bool
     */
    public static function hasConnectionData($id = stubDatabaseConnectionData::DEFAULT_ID)
    {
        return isset(self::$connectionData[$id]);
    }

    /**
     * returns the connection data for a given id
     * 
     * @param   string  $id  optional  id of the connection data
     * @return  stubDatabaseConnectionData
     */
    public static function getConnectionData($id = stubDatabaseConnectionData::DEFAULT_ID)
    {
        if (isset(self::$connectionData[$id]) == true) {
            return self::$connectionData[$id];
        }
        
        return null;
    }

    /**
     * returns a list of all ids of all connection data objects in the pool
     *
     * @return  array<string>
     */
    public static function getConnectionDataIds()
    {
        return array_keys(self::$connectionData);
    }

    /**
     * removes the connection data with the given id
     *
     * @param  string  $id  optional  id of the connection data to remove
     */
    public static function removeConnectionData($id = stubDatabaseConnectionData::DEFAULT_ID)
    {
        if (isset(self::$connectionData[$id]) == true) {
            self::$connectionData[$id] = null;
            unset(self::$connectionData[$id]);
        }
    }

    /**
     * closes the connection with the given id
     * 
     * Does nothing if no connection with the given id exists.
     *
     * @param  string  $id  optional  id of the connection to close
     */
    public static function closeConnection($id = stubDatabaseConnectionData::DEFAULT_ID)
    {
        if (isset(self::$connections[$id]) == true) {
            self::$connections[$id]->disconnect();
            self::$connections[$id] = null;
        }
    }

    /**
     * sets a connection for the given id
     *
     * @param  stubDatabaseConnection  $connection  the connection to use for the given id
     */
    public static function setConnection(stubDatabaseConnection $connection)
    {
        self::$connections[$connection->getConnectionData()->getId()] = $connection;
    }
}
?>