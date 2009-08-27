<?php
/**
 * Class for initializing the database connection data.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnectionPool',
                      'net::stubbles::rdbms::stubDatabaseInitializer',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Class for initializing the database connection data.
 *
 * @package     stubbles
 * @subpackage  rdbms
 */
class stubDatabaseXJConfInitializer extends stubXJConfAbstractInitializer implements stubDatabaseInitializer
{
    /**
     * descriptor to be used
     *
     * @var  string
     */
    protected $descriptor = 'rdbms';

    /**
     * sets the descriptor to be used
     *
     * @param  string  $descriptor
     */
    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        switch ($type) {
            case stubXJConfInitializer::DESCRIPTOR_CONFIG:
                return $this->descriptor;
            
            case stubXJConfInitializer::DESCRIPTOR_DEFINITION:
                // break omitted

            default:
                return 'rdbms';
        }
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array();
        foreach (stubDatabaseConnectionPool::getConnectionDataIds() as $connectionDataId) {
            $cacheData[$connectionDataId] = stubDatabaseConnectionPool::getConnectionData($connectionDataId)->getSerialized();
        }
        
        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        foreach ($cacheData as $serialized) {
            stubDatabaseConnectionPool::addConnectionData($serialized->getUnserialized());
        }
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        // intentionally empty
    }
}
?>